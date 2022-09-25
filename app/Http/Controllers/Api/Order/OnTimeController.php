<?php

namespace App\Http\Controllers\Api\Order;

use App\CarType;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTimeBasedOrderRequest;
use App\Traits\OnTimePrice;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OnTimeController extends Controller
{
    use OnTimePrice;

    public function calculatePrice(CreateTimeBasedOrderRequest $request)
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

        return response()->json([
            'car_type' => $car_type,
            'start_location' => [
                'lat' => $request->lat_start,
                'long' => $request->long_start,
                'name' => $request->name_start
            ],
            'total_price' => $total_price,
            'order_date' => [
                'start' => $start_date_formatted->translatedFormat('D, j M Y, H:i'),
                'finish' => $finish_date_formatted->translatedFormat('D, j M Y, H:i')
            ]
        ]);
    }
}
