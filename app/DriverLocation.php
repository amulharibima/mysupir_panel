<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DriverLocation extends Model
{
    protected $table = 'driver_locations';

    protected $fillable = [
        'driver_id', 'latitude', 'longitude'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
}
