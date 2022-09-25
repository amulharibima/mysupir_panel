<?php

namespace App;

use App\Traits\AssetUrl;
use Illuminate\Database\Eloquent\Model;

class OrderReport extends Model
{
    use AssetUrl;

    protected $table = 'order_reports';

    protected $fillable = [
        'order_id',
        'photos',
        'initial',
        'final'
    ];

    protected $appends = [
        'photo_urls'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'photos' => 'boolean',
        'initial' => 'boolean',
        'final' => 'boolean',
        'photos' => 'array'
    ];

    public function isInitialPhotos() : bool
    {
        return $this->initial ? true : false;
    }

    public function isFinalPhotos() : bool
    {
        return $this->final ? true : false;
    }

    public function getPhotoUrlsAttribute()
    {
        $photo_urls = [];
        if (count($this->photos)) {
            foreach ($this->photos as $i => $photo) {
                $photo_urls[$i] = $this->getAssetUrl($photo);
            }
        }

        return $photo_urls;
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }
}
