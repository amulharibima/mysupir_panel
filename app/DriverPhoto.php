<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * Foto driver disertai dengan lokasi
 */
class DriverPhoto extends Model
{
    protected $table = 'driver_photos';

    protected $fillable = [
        'driver_id', 'photo', 'latitude', 'longitude', 'location_name'
    ];

    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function delete()
    {
        if (Storage::exists($this->photo)) {
            Storage::delete($this->photo);
        }

        return parent::delete();
    }
}
