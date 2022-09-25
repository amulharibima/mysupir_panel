<?php

use App\CarType;
use App\DriverLicense;
use Illuminate\Database\Seeder;

class InitCarTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $car_types = [
            ['name' => 'sedan', 'driver_license_name' => 'sim a'],
            ['name' => 'pickup', 'driver_license_name' => 'sim b i'],
            ['name' => 'minibus', 'driver_license_name' => 'sim b i'],
            ['name' => 'berat', 'driver_license_name' => 'sim b ii'],
        ];

        foreach ($car_types as $type) {
            $driver_license = DriverLicense::where('name', $type['driver_license_name'])->first();

            CarType::firstOrCreate([
                'name' => $type['name'],
                'driver_license_id' => $driver_license->id
            ]);
        }
    }
}
