<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * Jenis Mobil
 * e,g: Sedan, Pickup, Minibus, SUV
 */
class CarType extends Model
{
    protected $fillable = [
        'name', 'driver_license_id', 'foto',
    ];

    public function license()
    {
        return $this->belongsTo(DriverLicense::class, 'driver_license_id');
    }
}
