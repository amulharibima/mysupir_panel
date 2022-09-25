<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(InitRolesSeeder::class);
        $this->call(InitDriverLicensesSeeder::class);
        $this->call(InitCarTypeSeeder::class);
        $this->call(InitAdminSeeder::class);
    }
}
