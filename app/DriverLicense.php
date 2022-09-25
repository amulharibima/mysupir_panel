<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * [Description DriverLicense]
 * Surat Izin Mengemudi
 * value: ['sim a', 'sim b', 'sim b+]
 */
class DriverLicense extends Model
{
    protected $table = 'driver_licenses';

    protected $fillable = [
        'name'
    ];

    public function car_type()
    {
        return $this->hasMany(CarType::class);
    }
}
