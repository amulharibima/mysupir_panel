<?php
namespace App\Traits;

use App\Driver;
use App\DriverLocation;
use App\Order;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

trait GetNearestDriver
{
    protected $max_radius = 2; // in km
    protected $interval_hour = 3; // waktu jeda driver dalam jam

    /**
     * @param mixed $lat
     * @param mixed $long
     * @param mixed $car_type_id
     *
     * @return [DriverLocation]
     */
    public function getNearestAvailableDriver($lat, $long, $car_type_id)
    {
        abort_if((empty($lat) || empty($long)), 404, 'Latitude or Longitude cannot be null');

        $drivers = DriverLocation::select('*', DB::raw("6371 * acos(cos(radians(" .$lat. ")) * cos(radians(driver_locations.latitude))* cos(radians(driver_locations.longitude) - radians(" .$long. "))+ sin(radians(" .$lat. "))* sin(radians(driver_locations.latitude))) AS distance"))
        ->having('distance', '<=', $this->max_radius)
        ->whereHas('driver', function (Builder $query) use ($car_type_id) {
            $query->where('order_status', 1)
            ->whereHas('licenses', function (Builder $query) use ($car_type_id) {
                $query->whereHas('car_type', function (Builder $query) use ($car_type_id) {
                    $query->where('id', $car_type_id);
                });
            });
        })
        ->orderBy('distance')
        ->get();

        return $drivers;
    }

    /**
     * Search available driver with filtered
     *
     * @param Order $order
     *
     * @return Driver
     */
    public function searchAvailableDriverWithOrder(Order $order)
    {
        $start_location = $order->startLocation;
        $driver_locations = $this->getNearestAvailableDriver($start_location->latitude, $start_location->longitude, $order->car_type_id);

        if (empty($driver_locations->first())) {
            return null;
        }

        if (count($order->declined_drivers)) {
            $driver_locations = $driver_locations->whereNotIn('driver_id', $order->declined_drivers->pluck('driver_id'))->all();
        }

        foreach ($driver_locations as $location) {
            $driver = $location->driver;
            if ($driver->canAcceptOrder($order)) {
                return $driver;
                break;
            }
        }

        return null;
    }
}
