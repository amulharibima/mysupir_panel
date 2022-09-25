<?php
namespace App\Traits;
use App\Price;

trait OnTripPrice
{
    public function getOnTripPrice($distance)
    {
        $price_data = Price::find(1);
        $price = $price_data->default_price; // default price (less than 10 km)

        if ($distance > 10) {
            $price = $price_data->price_per_km * $distance;
        }

        return $price;
    }
}
