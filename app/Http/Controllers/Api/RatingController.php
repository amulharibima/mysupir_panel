<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Order;
use App\Rating;
use App\Traits\CurrentDriver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RatingController extends Controller
{
    use CurrentDriver;

    public function addRating(Request $request, Order $order)
    {
        $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'notes' => 'nullable|string|max:255'
        ]);

        abort_if(!empty($order->rating), 403, 'Rating already exists.');
        abort_if($order->user_id != Auth::id(), 403, 'Access denied.');
        abort_if(! $order->isOrderFinished(), 403, 'Order still on going.');

        // if order is additional then rate to all additional orders
        if ($order->isAdditionalOrder()) {
            $parentOrder = $order->parent_order;

            Rating::create([
                'order_id' => $parentOrder->id,
                'driver_id' => $parentOrder->driver_id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'notes' => $request->notes
            ]);

            foreach ($parentOrder->additional_orders as $order) {
                Rating::create([
                    'order_id' => $order->id,
                    'driver_id' => $order->driver_id,
                    'user_id' => Auth::id(),
                    'rating' => $request->rating,
                    'notes' => $request->notes
                ]);
            }

            $driver_id = $parentOrder->driver_id;
            $driver = $parentOrder->driver()->first();

        } else {
            Rating::create([
                'order_id' => $order->id,
                'driver_id' => $order->driver_id,
                'user_id' => Auth::id(),
                'rating' => $request->rating,
                'notes' => $request->notes
            ]);

            $driver_id = $order->driver_id;
            $driver = $order->driver()->first();
        }

        $latest_3_ratings = Rating::where('driver_id', $driver_id)->latest()->take(3)->get(); 

        $count_rating_1 = $latest_3_ratings->filter(function($item){
            return $item->rating === 1;
        })->count();

        if($count_rating_1 === 3){
            $driver->is_suspended = true;
            $driver->alasan_suspend = 'Anda mendapat rating 1 sebanyak 3 kali berturut-turut';
            $driver->save();

            return response()->json(['message' => 'ok. driver disuspend karena rating 1 3x berturut2']);
        }

        return response()->json(['message' => 'ok']);
    }

    public function listReviews()
    {
        $driver = $this->getCurrentDriver();

        $ratings = Rating::where('driver_id', $driver->id)->get();

        return response()->json(['reviews' => $ratings]);
    }
}
