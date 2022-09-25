<?php

use App\User;
use Illuminate\Database\Seeder;

class FakeUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $phone_number = '6285717172111'; // For development purpose, change this to yours based on nexmo
        $initUser = factory(User::class, 1)->create([
            'phone_number' => $phone_number
        ]);
        $user = $initUser[0];
        if ($user) {
            $user->assignRole('user');
        }
    }
}
