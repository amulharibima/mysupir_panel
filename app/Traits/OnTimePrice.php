<?php
namespace App\Traits;

use Carbon\Carbon;

trait OnTimePrice
{
    /**
     * Calculate time-based order price
     *
     * @param string $start_date
     * @param string $start_time
     * @param string $finish_date
     * @param string $finish_time
     *
     * @return [int]
     */
    public function getOnTimePrice($start_date, $start_time, $finish_date, $finish_time)
    {
        $price_per_hour = 100000;
        $price = 250000; // default price (less than 2 hour)

        $start = Carbon::createFromFormat('Y-m-d H:i', $start_date.' '.$start_time);
        $finish = Carbon::createFromFormat('Y-m-d H:i', $finish_date.' '.$finish_time);

        $duration = ceil($start->floatDiffInHours($finish)); // dibulatkan ke atas

        if (($duration >= 2) && ($duration < 6)) {
            $price_per_hour = 100000;
        } elseif (($duration >= 6) && ($duration < 8)) {
            $price_per_hour = 90000;
        } elseif ($duration >= 8) {
            $price_per_hour = 80000;
        }
        $price = $duration * $price_per_hour;

        return $price;
    }
}
