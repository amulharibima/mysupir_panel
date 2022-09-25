<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'identifier', // id for midtrans transaction
        'driver_id', 'user_id', 'car_type_id', 'conversation_id',
        'status', // [dibuat, menunggu supir, menunggu waktu order, sedang dalam perjalanan, selesai, dibatalkan]
        'notes', // catatan. nullable
        'cancel_notes', // alasan pembatalan order (harcoded on FE)
        'type', // [trip, time] based
        'later', // 1 for later null for now. nullable
        'start_datetime', // default `now()`
        'finish_datetime', // default `now()`
        'total_distance', // nullable if time-based
        'is_additional',
        'parent_order_id'
    ];

    public const CREATED_STATUS = 'dibuat';
    public const DRIVER_ACCEPTED_STATUS = 'diterima supir';
    public const WAITING_DRIVER_STATUS = 'menunggu supir';
    public const WAITING_ORDER_TIME_STATUS = 'menunggu waktu';
    public const ONGOING_STATUS = 'sedang berjalan';
    public const FINISHED_STATUS = 'selesai';
    public const CANCELED_STATUS = 'dibatalkan';

    protected $appends = [
        'order_id'
    ];

    protected $casts = [
        'is_additional' => 'boolean'
    ];

    public function cancelOrder($notes = null)
    {
        $this->status = self::CANCELED_STATUS;
        if (!empty($notes)) {
            $this->cancel_notes = $notes;
        }

        return $this->save();
    }

    // Helpers function
    public function isLaterOrder()
    {
        return $this->later ? true : false;
    }

    public function isAdditionalOrder()
    {
        return $this->is_additional ? true : false;
    }

    public function hasParent() : bool
    {
        return !empty($this->parent_order) ? true : false;
    }

    public function isPendingOrder()
    {
        return $this->status == self::CREATED_STATUS ? true : false;
    }

    public function isWaitingDriver()
    {
        return $this->status == self::WAITING_DRIVER_STATUS ? true : false;
    }

    public function isWaitingTime()
    {
        return $this->status == self::WAITING_ORDER_TIME_STATUS ? true : false;
    }

    public function isOnGoingOrder()
    {
        return $this->status == self::ONGOING_STATUS ? true : false;
    }

    public function isCanceledOrder()
    {
        return $this->status == self::CANCELED_STATUS ? true : false;
    }

    public function isOrderFinished()
    {
        return $this->status == self::FINISHED_STATUS ? true : false;
    }

    public function getOrderIdAttribute()
    {
        return $this->identifier;
    }

    public function getOrderType()
    {
        return $this->type;
    }

    public function getOrderIdentifier()
    {
        return $this->identifier;
    }

    public function getStartTime()
    {
        return $this->start_datetime ? Carbon::createFromFormat('Y-m-d H:i:s', $this->start_datetime) : null;
    }

    public function isHasDriver()
    {
        return $this->driver ? true : false;
    }

    // Scopes
    public function scopeOngoing($query)
    {
        return $query->where('status', self::ONGOING_STATUS)
        ->orWhere('status', self::WAITING_ORDER_TIME_STATUS)
        ->orWhere('status', self::WAITING_DRIVER_STATUS)
        ->orderBy('id', 'DESC');
    }

    public function scopeFinished($query)
    {
        return $query->where('status', self::FINISHED_STATUS)
        ->orderBy('id', 'DESC');
    }

    public function scopeHistory($query)
    {
        return $query->where('status', self::ONGOING_STATUS)
        ->orWhere('status', self::WAITING_ORDER_TIME_STATUS)
        ->orWhere('status', self::WAITING_DRIVER_STATUS)
        ->orWhere('status', self::FINISHED_STATUS)
        ->orWhere('status', self::CANCELED_STATUS)
        ->orderBy('id', 'DESC');
    }

    // Relationships
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }

    public function declined_drivers()
    {
        return $this->belongsToMany(Driver::class, 'driver_decline_orders', 'order_id', 'driver_id')->withTimestamps();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function car_type()
    {
        return $this->belongsTo(CarType::class, 'car_type_id');
    }

    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    public function locations()
    {
        return $this->hasMany(OrderLocation::class);
    }

    public function startLocation()
    {
        return $this->hasOne(OrderLocation::class)->where('start', 1);
    }

    public function finishLocation()
    {
        return $this->hasOne(OrderLocation::class)->where('finish', 1);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }

    public function crash_report()
    {
        return $this->hasOne(CrashReport::class);
    }

    public function initialPhoto()
    {
        return $this->hasOne(OrderReport::class)->where('initial', 1);
    }

    public function finalPhoto()
    {
        return $this->hasOne(OrderReport::class)->where('final', 1);
    }

    public function additional_orders()
    {
        return $this->hasMany(self::class, 'parent_order_id');
    }

    public function parent_order()
    {
        return $this->belongsTo(self::class, 'parent_order_id');
    }
}
