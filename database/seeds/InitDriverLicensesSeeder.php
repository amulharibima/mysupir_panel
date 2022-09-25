<?php

use App\DriverLicense;
use Illuminate\Database\Seeder;

class InitDriverLicensesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $driving_licenses = ['sim a', 'sim b i', 'sim b ii'];

        foreach ($driving_licenses as $license) {
            DriverLicense::firstOrCreate(['name' => $license]);
        }
    }
}
