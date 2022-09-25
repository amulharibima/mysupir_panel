<?php

namespace App\Http\Controllers\Api;

use App\CarType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CarTypeController extends Controller
{
    public function getAllType()
    {
        $car_types = CarType::with('license')->get();

        return response()->json([
            'car_types' => $car_types
        ]);
    }
}
