<?php

use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class InitAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            // 'password' => Hash::make('password'),
            'phone_number' => '81270135829'
        ]);

        if($user) {
            $user->assignRole('admin');
        }
    }
}
