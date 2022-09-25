<?php

use App\Driver;
use App\DriverLicense;
use App\Earning;
use App\User;
use Illuminate\Database\Seeder;

class FakeDriverSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phone_number = '6285717172111'; // For development purpose, change this to yours based on nexmo
        $address = 'Jl. Keadilan Raya Nomor 6, Graha Harmony Lt 2';
        $foto = 'https://lorempixel.com/640/480/?21391';
        $initUser = factory(User::class, 1)->create([
            'phone_number' => $phone_number
        ]);
        $user = $initUser[0];

        if (!empty($user)) {
            $user->assignRole('driver');
            $sim_a = DriverLicense::where('name', 'sim a')->first();

            $driver = Driver::create([
                'name' => $user->name,
                'email' => $user->email,
                'phone_number' => $user->phone_number,
                'address' => $address,
                'foto' => $foto,
                'user_id' => $user->id
            ]);

            if ($driver && $sim_a) {
                $driver->licenses()->sync($sim_a);

                // initialize driver's earning
                Earning::create([
                    'driver_id' => $driver->id,
                    'amount' => 0
                ]);
            }
        }
    }
}
