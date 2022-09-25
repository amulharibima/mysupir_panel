<?php

namespace App\Http\Controllers\Api\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTripBasedOrderRequest;
use App\Traits\OnTripPrice;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OnTripController extends Controller
{
    use OnTripPrice;

    public function calculatePrice(CreateTripBasedOrderRequest $request)
    {
        $total_price = $this->getOnTripPrice($request->total_distance);

        $start_location = [
          'lat' => $request->lat_start,
          'long' => $request->long_start,
          'name' => $request->name_start
        ];

        $finish_location = [];
        foreach ($request->lat_finish as $i => $lat) {
            $finish_location[$i] = [
                'lat' => $lat,
                'long' => $request->long_finish[$i],
                'name' => $request->name_finish[$i]
            ];
        }

        $later_date = !empty($request->later) ? Carbon::createFromFormat('Y-m-d H:i', $request->later_date.' '.$request->later_time)->translatedFormat('D, j M H:i') : null;

        return response()->json([
            'start_location' => $start_location,
            'finishh_location' => $finish_location,
            'total_price' => $total_price,
            'total_distance' => (int) $request->total_distance,
            'later_date' => $request->later ? $later_date : null
        ]);
    }
}
