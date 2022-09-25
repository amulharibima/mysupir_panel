<?php

namespace App\Http\Controllers\Api;

use App\CarType;
use App\Driver;
use App\Events\DriverAcceptOrder;
use App\Events\DriverDeclineOrder;
use App\Events\DriverFound;
use App\Events\DriverNotFound;
use App\Events\OrderEvent;
use App\Events\OrderEvents;
use App\Events\PaymentEvent;
use App\Events\PaymentReceiptCreated;
use App\Events\TransactionEvents;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateAdditionalTimeBasedOrderRequest;
use App\Http\Requests\CreateAdditionalTripOrderRequest;
use App\Http\Requests\CreateTimeBasedOrderRequest;
use App\Http\Requests\CreateTripBasedOrderRequest;
use App\Order;
use App\Traits\CurrentDriver;
use App\Traits\GetNearestDriver;
use App\Traits\MidtransPayment;
use App\Traits\OnTimePrice;
use App\Traits\OnTripPrice;
use App\Transaction;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    use OnTripPrice, OnTimePrice, CurrentDriver, GetNearestDriver, MidtransPayment;

    public function createTripBasedOrder(CreateTripBasedOrderRequest $request)
    {
        // validate distance and location point
        if (count($request->distance) >= (count($request->lat_finish) + 1)) {
            throw ValidationException::withMessages([
                'distance' => 'The distance must not have more than location items.'
            ]);
        }

        // validate distance and total_distance
        $sum_distance = 0;
        foreach ($request->distance as $distance) {
            $sum_distance += $distance;
        }
        if ($sum_distance > $request->total_distance) {
            throw ValidationException::withMessages([
                'distance' => 'The distance must not have more than total distance.'
            ]);
        }

        $total_price = $this->getOnTripPrice($request->total_distance);
        $car_type = CarType::findOrFail($request->car_type_id);
        $is_later_order = $request->filled('later') && ($request->later == 1);

        $new_order = Order::create([
            'user_id' => Auth::id(),
            'car_type_id' => $car_type->id,
            'status' => Order::CREATED_STATUS,
            'notes' => $request->notes,
            'later' =>  $is_later_order ? 1 : null,
            'start_datetime' => $is_later_order ? Carbon::createFromFormat('Y-m-d H:i', $request->later_date.' '.$request->later_time)->toDateTimeString() : now(),
            'finish_datetime' => $is_later_order ? Carbon::createFromFormat('Y-m-d H:i', $request->later_date.' '.$request->later_time)->toDateTimeString() : now(),
            'total_distance' => $request->total_distance,
            'type' => 'trip'
        ]);

        if ($new_order) {
            $finish_locations = [];
            foreach ($request->lat_finish as $i => $lat) {
                $finish_locations[$i] = [
                    'name' => $request->name_finish[$i],
                    'latitude' => $request->lat_finish[$i],
                    'longitude' => $request->long_finish[$i],
                    'finish' => ((count($request->lat_finish) - 1) == $i) ? true : null
                ];
            }

            $order_locations = Arr::prepend($finish_locations, [
                'name' => $request->name_start,
                'latitude' => $request->lat_start,
                'longitude' => $request->long_start,
                'start' => true
            ]);

            $new_order->locations()->createMany($order_locations);
            $new_order->transaction()->create([
                'total_price' => $total_price,
                'fee' => $total_price * Fee::find(1)->percentage_amount / 100,
                'status' => Transaction::MENUNGGU_PEMBAYARAN
            ]);
            $this->createOnTripDetailTransaction($new_order, $order_locations, $request->distance);

            event(OrderEvents::CREATED, new OrderEvent($new_order));
        }

        return response()->json([
            'order' => $new_order->load('startLocation'),
            'finsih_location' => $new_order->finishLocation,
            'total_price' => $total_price,
            'total_distance' => (int) $request->total_distance,
        ], 201);
    }

    public function createAdditionalTripBasedOrder(CreateAdditionalTripOrderRequest $request, Order $order)
    {
        // validate if previous order has final photo report
        throw_if(empty($order->finalPhoto), ValidationException::withMessages(['order' => 'Driver has not upload initial photo on previous order.']));

        // validate maximum additional orders (max: 2)
        $parent_order = $order;
        if ($order->hasParent()) {
            $parent_order = $order->parent_order;
        }
        throw_if((count($parent_order->additional_orders) >= 2), ValidationException::withMessages(['order' => 'You are reached maximum additional order.']));

        // validate distance and location point
        if (count($request->distance) >= (count($request->lat_finish) + 1)) {
            throw ValidationException::withMessages([
                'distance' => 'The distance must not have more than location items.'
            ]);
        }

        // validate distance and total_distance
        $sum_distance = 0;
        foreach ($request->distance as $distance) {
            $sum_distance += $distance;
        }
        if ($sum_distance > $request->total_distance) {
            throw ValidationException::withMessages([
                'distance' => 'The distance must not have more than total distance.'
            ]);
        }

        $total_price = $this->getOnTripPrice($request->total_distance);
        $parent_order = $order;
        if ($order->hasParent()) {
            $parent_order = $order->parent_order;
        }

        $new_order = Order::create([
            'user_id' => Auth::id(),
            'driver_id' => $parent_order->driver_id,
            'is_additional' => true,
            'parent_order_id' => $parent_order->id,
            'car_type_id' => $parent_order->car_type->id,
            'status' => Order::DRIVER_ACCEPTED_STATUS,
            'start_datetime' => now(),
            'finish_datetime' => now(),
            'total_distance' => $request->total_distance,
            'type' => 'trip'
        ]);

        if ($new_order) {
            $finish_locations = [];
            foreach ($request->lat_finish as $i => $lat) {
                $finish_locations[$i] = [
                    'name' => $request->name_finish[$i],
                    'latitude' => $request->lat_finish[$i],
                    'longitude' => $request->long_finish[$i],
                    'finish' => ((count($request->lat_finish) - 1) == $i) ? true : null
                ];
            }

            $order_locations = Arr::prepend($finish_locations, [
                'name' => $request->name_start,
                'latitude' => $request->lat_start,
                'longitude' => $request->long_start,
                'start' => true
            ]);

            $new_order->locations()->createMany($order_locations);
            $new_order->transaction()->create([
                'total_price' => $total_price,
                'fee' => $total_price * Fee::find(1)->percentage_amount / 100,
                'status' => Transaction::MENUNGGU_PEMBAYARAN
            ]);
            $this->createOnTripDetailTransaction($new_order, $order_locations, $request->distance);

            event(OrderEvents::CREATED, new OrderEvent($new_order));
        }

        return response()->json([
            'order' => $new_order->load('startLocation'),
            'finsih_location' => $new_order->finishLocation,
            'total_price' => $total_price,
            'total_distance' => (int) $request->total_distance,
        ], 201);
    }

    public function createTimeBasedOrder(CreateTimeBasedOrderRequest $request)
    {
        $start_date_formatted = Carbon::createFromFormat('Y-m-d H:i', $request->start_date.' '.$request->start_time);
        $finish_date_formatted = Carbon::createFromFormat('Y-m-d H:i', $request->finish_date.' '.$request->finish_time);
        if ($start_date_formatted->greaterThanOrEqualTo($finish_date_formatted)) {
            throw ValidationException::withMessages([
                'finish_time' => 'The finish time must be after start time.'
            ]);
        }

        $car_type = CarType::findOrFail($request->car_type_id);
        $total_price = $this->getOnTimePrice($request->start_date, $request->start_time, $request->finish_date, $request->finish_time);

        $new_order = Order::create([
            'user_id' => Auth::id(),
            'car_type_id' => $car_type->id,
            'status' => Order::CREATED_STATUS,
            'type' => 'time',
            'start_datetime' => $start_date_formatted->toDateTimeString(),
            'finish_datetime' => $finish_date_formatted->toDateTimeString()
        ]);

        if ($new_order) {
            $new_order->startLocation()->create([
                'name' => $request->name_start,
                'latitude' => $request->lat_start,
                'longitude' => $request->long_start,
                'start' => true
            ]);

            $new_order->transaction()->create([
                'total_price' => $total_price,
                'fee' => $total_price * Fee::find(1)->percentage_amount / 100,
                'status' => Transaction::MENUNGGU_PEMBAYARAN
            ]);

            $new_order->transaction->details()->create([
                'name' => 'Sewa Pengemudi',
                'price' => $total_price
            ]);

            event(OrderEvents::CREATED, new OrderEvent($new_order));
        }

        return response()->json([
            'order' => $new_order->load('transaction'),
            'total_price' => $total_price,
            'order_date' => [
                'start' => $start_date_formatted->translatedFormat('D, j M Y, H:i'),
                'finish' => $finish_date_formatted->translatedFormat('D, j M Y, H:i')
            ]
        ], 201);
    }

    public function createAdditionalTimeBasedOrder(CreateAdditionalTimeBasedOrderRequest $request, Order $order)
    {
        // validate if previous order has final photo report
        throw_if(empty($order->finalPhoto), ValidationException::withMessages(['order' => 'Driver has not upload initial photo on previous order.']));

        // validate maximum additional orders (max: 2)
        $parent_order = $order;
        if ($order->hasParent()) {
            $parent_order = $order->parent_order;
        }
        throw_if((count($parent_order->additional_orders) >= 2), ValidationException::withMessages(['order' => 'You are reached maximum additional order.']));

        $start_time = $order->finish_datetime;
        $finish_date_formatted = Carbon::createFromFormat('Y-m-d H:i', $request->finish_date.' '.$request->finish_time);

        $total_price = $this->getOnTimePrice(Carbon::createFromFormat('Y-m-d H:i:s', $start_time)->toDateString(), Carbon::createFromFormat('Y-m-d H:i:s', $start_time)->format('H:i'), $request->finish_date, $request->finish_time);

        $new_order = Order::create([
            'user_id' => Auth::id(),
            'driver_id' => $parent_order->driver_id,
            'is_additional' => true,
            'parent_order_id' => $parent_order->id,
            'car_type_id' => $parent_order->car_type->id,
            'status' => Order::DRIVER_ACCEPTED_STATUS,
            'type' => 'time',
            'start_datetime' => $start_time,
            'finish_datetime' => $finish_date_formatted->toDateTimeString()
        ]);

        if ($new_order) {
            $new_order->startLocation()->create([
                'name' => $request->name_start,
                'latitude' => $request->lat_start,
                'longitude' => $request->long_start,
                'start' => true
            ]);

            $new_order->transaction()->create([
                'total_price' => $total_price,
                'fee' => $total_price * Fee::find(1)->percentage_amount / 100,
                'status' => Transaction::MENUNGGU_PEMBAYARAN
            ]);

            $new_order->transaction->details()->create([
                'name' => 'Sewa Pengemudi',
                'price' => $total_price
            ]);

            event(OrderEvents::CREATED, new OrderEvent($new_order));
        }

        return response()->json([
            'order' => $new_order->load('transaction'),
            'total_price' => $total_price,
            'order_date' => [
                'start' => Carbon::createFromFormat('Y-m-d H:i:s', $start_time)->translatedFormat('D, j M Y, H:i'),
                'finish' => $finish_date_formatted->translatedFormat('D, j M Y, H:i')
            ]
        ], 201);
    }

    public function researchDriver(Order $order)
    {
        abort_if($order->user_id != Auth::id(), 403, 'Access denied.');

        $selectedDriver = $this->searchAvailableDriverWithOrder($order);

        if (!empty($selectedDriver)) {
            event(new DriverFound($selectedDriver, $order));
        } else {
            event(new DriverNotFound($order));
        }

        return response()->json(['message' => 'ok']);
    }

    public function acceptOrder(Order $order)
    {
        $driver = $this->getCurrentDriver();

        abort_if($order->isCanceledOrder(), 403, 'Order canceled by user or payment has expired.');
        abort_if(!empty($order->driver), 403, 'Driver already exists.');
        abort_if($order->declined_drivers->contains($driver), 403, 'You already decline this order.');

        $order->update([
            'driver_id' => $driver->id,
            'status' => Order::DRIVER_ACCEPTED_STATUS
        ]);

        if ($order->wasChanged('driver_id')) {
            if (! $order->isLaterOrder()) {
                $driver->update(['order_status' => Driver::ONGOING_STATUS]);
            }

            event(new DriverAcceptOrder($order, $driver));
        }

        return response()->json(['message' => 'ok']);
    }

    public function declineOrder(Order $order)
    {
        $driver = $this->getCurrentDriver();

        abort_if($order->isCanceledOrder(), 403, 'Order canceled by user or payment expired.');
        abort_if(!empty($order->driver), 403, 'Driver already exists.');
        abort_if($order->declined_drivers->contains($driver), 403, 'You already decline this order.');

        $order->declined_drivers()->attach($driver);
        event(new DriverDeclineOrder($driver, $order));

        return response()->json(['message' => 'ok']);
    }

    public function cancelSearchDriver(Order $order)
    {
        $this->validateOrderOwner($order);

        abort_if($order->isCanceledOrder(), 403, 'Order already canceled.');

        if ($order->cancelOrder('Pengemudi tidak ditemukan')) {
            event(OrderEvents::CANCELED, new OrderEvent($order));
        }

        return response()->json(['message' => 'ok']);
    }

    public function cancelOrder(Order $order, Request $request)
    {
        $this->validateOrderOwner($order);

        $request->validate([
            'cancel_notes' => 'required|string|max:255'
        ]);

        abort_if($order->isCanceledOrder(), 403, 'Order already canceled.');
        abort_if(!$order->isHasDriver(), 403, 'You cannot cancel order now.');
        abort_if($order->isOrderFinished(), 403, 'Order already finished.');

        if ($order->cancelOrder($request->cancel_notes)) {
            event(OrderEvents::CANCELED, new OrderEvent($order));
        }

        return response()->json(['message' => 'ok']);
    }

    public function triggerStart(Order $order)
    {
        abort_if($order->user_id != Auth::id(), 403, 'Acess denied.');
        abort_if($order->isCanceledOrder(), 403, 'Order has finished.');
        abort_if(!$order->isWaitingTime(), 403, 'Order status must be on waiting time.');

        $updateStatus = $order->update([
            'status' => Order::WAITING_DRIVER_STATUS
        ]);

        $updateStatusDriver = $order->driver->update([
            'order_status' => Driver::ONGOING_STATUS
        ]);

        if ($updateStatus && $updateStatusDriver) {
            event(OrderEvents::STARTING, new OrderEvent($order));
        }

        return response()->json(['message' => 'ok']);
    }

    public function triggerFinish(Order $order)
    {
        abort_if($order->user_id != Auth::id(), 403, 'Acess denied.');
        abort_if($order->isCanceledOrder(), 403, 'Order has finished.');
        abort_if(!$order->isOnGoingOrder(), 403, 'Order must be on going.');

        event(OrderEvents::FINISHING, new OrderEvent($order));

        return response()->json(['message' => 'ok']);
    }

    public function proceedPayment(Order $order)
    {
        abort_if(!$order->isHasDriver(), 403, 'Driver does not exists.');

        $snap_token = $this->createSnapToken($order);

        if ($snap_token instanceof Exception) {
            abort($snap_token->getCode(), $snap_token->getMessage());
        }

        $transaction = $order->transaction;

        $transaction->setStatusToWaitingPayment($snap_token->token);
        $web_pay_url = URL::temporarySignedRoute(
            'snap_url',
            Carbon::now()->addDay(1),
            ['snapToken' => $snap_token->token]
        );

        return response()->json([
            'snap_token' => $snap_token->token,
            'web_pay_url' => $web_pay_url
        ]);
    }

    public function getDetailOrder(Order $order)
    {
        $this->validateOrderOwner($order);

        return response()->json([
            'order' => $order->load(['locations', 'startLocation', 'finishLocation', 'rating', 'initialPhoto', 'finalPhoto', 'user']),
            'transaction' => $order->transaction->load('details'),
            'driver' => $order->driver_id ? [
                'detail' => $order->driver,
                'rating' => $order->driver->getAverageRating(),
                'licenses' => ($order->driver->licenses)->pluck('name'),
                'order_complete' => ($order->driver->finished_orders)->count()
            ] : null
        ]);
    }

    public function uploadPaymentReceipt(Order $order, Request $request)
    {
        $request->validate([
           'payment_receipt' => 'required|image|max:5120',
        ]);

        abort_if($order->isCanceledOrder(), 403, 'Order canceled by user or payment has expired.');
        abort_if($order->user_id != Auth::id(), 403, 'Access denied.');
        abort_if(!empty($order->transaction->payment_receipt), 403, 'Payment receipt already exists.');

        $file_path = $request->file('payment_receipt')->store('order/'.$order->getOrderIdentifier().'/receipt');

        $order->transaction->update([
            'payment_receipt' => $file_path
        ]);

        event(new PaymentReceiptCreated($order));

        return response()->json(['message' => 'ok']);
    }

    public function checkTransaction(Order $order)
    {
        $this->validateOrderOwner($order);

        $midtransStatus = $this->checkStatus($order->getOrderIdentifier());

        if ($midtransStatus instanceof Exception) {
            abort($midtransStatus->getCode(), $midtransStatus->getMessage());
        }

        $transactionStatus = $midtransStatus->transaction_status;
        $va_numbers = $midtransStatus->va_numbers[0] ?? null;

        $paymentEvent = new PaymentEvent(
            $order,
            $midtransStatus->transaction_id,
            $midtransStatus->transaction_time,
            $midtransStatus->payment_type,
            $va_numbers ? $va_numbers->va_number : null,
            $va_numbers ? $va_numbers->bank : null,
            $midtransStatus->bill_key ?? null,
            $midtransStatus->biller_code ?? null
        );

        if ($transactionStatus == 'pending') {
            if (! $order->transaction->isPaymentPending()) {
                event(TransactionEvents::PENDING, $paymentEvent);
            }
        } elseif ($transactionStatus == 'settlement') {
            if (! $order->transaction->isPaymentSettled()) {
                event(TransactionEvents::SETTLEMENT, $paymentEvent);
            }
        } elseif ($transactionStatus == 'expire') {
            if (! $order->transaction->isPaymentExpired()) {
                event(TransactionEvents::EXPIRE, $paymentEvent);
            }
        } else {
            //
        }

        return response()->json([
            'order' => $order->load('startLocation', 'locations', 'finishLocation'),
            'transaction' => $order->transaction->load('details')
        ]);
    }

    public function getFinishedOrders()
    {
        $user = Auth::user();

        if ($user->hasRole('driver')) {
            $driver = $this->getCurrentDriver();

            $orders = Order::finished()->where('driver_id', $driver->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        } else {
            $orders = Order::finished()->where('user_id', $user->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        }

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function getOngoingOrders()
    {
        $user = Auth::user();

        if ($user->hasRole('driver')) {
            $driver = $this->getCurrentDriver();

            $orders = Order::ongoing()->where('driver_id', $driver->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        } else {
            $orders = Order::ongoing()->where('user_id', $user->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        }

        return response()->json([
            'orders' => $orders
        ]);
    }

    public function getOrdersHistory()
    {
        $user = Auth::user();

        if ($user->hasRole('driver')) {
            $driver = $this->getCurrentDriver();

            $orders = Order::history()->where('driver_id', $driver->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        } else {
            $orders = Order::history()->where('user_id', $user->id)->with(['startLocation', 'finishLocation', 'transaction', 'user', 'driver', 'rating'])->get();
        }

        return response()->json([
            'orders' => $orders
        ]);
    }

    protected function validateOrderOwner(Order $order)
    {
        $user = Auth::user();

        if ($user->hasRole('driver')) {
            $driver = $this->getCurrentDriver();

            abort_if(($order->driver_id != $driver->id), 403, 'Access denied.');
        } else {
            abort_if(($order->user_id != $user->id), 403, 'Access denied.');
        }
    }

    protected function createOnTripDetailTransaction(Order $order, array $locations, array $ditances)
    {
        if (($order->getOrderType() == 'trip') && count($ditances)) {
            if (count($locations) > 2) {
                for ($i=0; $i < count($ditances); $i++) {
                    $details = $order->transaction->details()->create([
                        'name' => 'Tarif Perjalanan '.($i+1).' ('.$ditances[$i].'km)',
                        'price' => $this->getOnTripPrice($ditances[$i])
                    ]);
                }
            } else {
                $details = $order->transaction->details()->create([
                    'name' => 'Tarif Dasar ('.$ditances[0].'km)',
                    'price' => $this->getOnTripPrice($ditances[0])
                ]);
            }
        }

        return $details;
    }
}
