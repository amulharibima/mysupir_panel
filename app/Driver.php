<?php

namespace App;

use App\Traits\AssetUrl;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use AssetUrl;

    protected $table = 'drivers';

    protected $fillable = [
        'name', 'email', 'phone_number', 'address', 'foto',
        'order_status', // availble status for pick up order. 0 or null = notAvailable, 1 = available, 2 = on going
        'user_id', 'identifier'
    ];

    protected $appends = [
        'foto_url'
    ];

    public const UNAVAILABLE_STATUS = 0;
    public const AVAILABLE_STATUS = 1;
    public const ONGOING_STATUS = 2;

    /**
     * Scope a query to only available for pickup drivers.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('order_status', self::AVAILABLE_STATUS);
    }

    public function delete()
    {
        $this->user()->delete();
        $this->licenses()->detach();
        $this->location()->delete();
        $this->ratings()->delete();
        $this->driver_photos()->delete();
        $this->earning()->delete();
        $this->earning_histories()->delete();

        return parent::delete();
    }

    public function getFotoUrlAttribute()
    {
        return $this->foto ? $this->getAssetUrl($this->foto) : '';
    }

    public function getAverageRating()
    {
        $ratings = $this->ratings;

        return count($ratings) ? number_format($ratings->avg('rating'), 1) : number_format(5, 1); // default rating 5.0
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function licenses()
    {
        return $this->belongsToMany(DriverLicense::class, 'driver_license_pivot', 'driver_id', 'driver_license_id')->withTimestamps();
    }

    public function location()
    {
        return $this->hasOne(DriverLocation::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function declined_orders()
    {
        return $this->belongsToMany(Order::class, 'driver_decline_orders', 'driver_id', 'order_id')->withTimestamps();
    }

    public function awaiting_orders()
    {
        return $this->hasMany(Order::class)->where('status', Order::WAITING_ORDER_TIME_STATUS);
    }

    public function finished_orders()
    {
        return $this->hasMany(Order::class)->where('status', Order::FINISHED_STATUS);
    }

    public function driver_photos()
    {
        return $this->hasMany(DriverPhoto::class);
    }

    public function earning()
    {
        return $this->hasOne(Earning::class);
    }

    public function earning_histories()
    {
        return $this->hasMany(EarningHistory::class);
    }

    public function canAcceptOrder(Order $order, $interval_time = 3)
    {
        $starting_time = Carbon::now();
        if ($order->getOrderType() == 'time') {
            $starting_time = $order->getStartTime();
        }

        foreach ($this->awaiting_orders as $awaiting_order) {
            if ($awaiting_order->isPendingOrder()) {
                if ($starting_time->floatDiffInHours($awaiting_order->getStartTime()) <= $interval_time) {
                    return false;
                    break;
                }
            }
        }

        return true;
    }
}
