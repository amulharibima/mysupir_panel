<?php
namespace App\Traits;

use App\Driver;
use Illuminate\Support\Facades\Auth;

trait CurrentDriver
{
    /**
     * Get current authenticated driver
     *
     * @return Driver
     */
    protected function getCurrentDriver()
    {
        $driver = Driver::where('user_id', Auth::id())->first();

        abort_if(empty($driver), 404, 'Driver tidak ditemukan');

        return $driver;
    }
}
