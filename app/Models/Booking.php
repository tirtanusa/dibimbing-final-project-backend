<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'barber_id',
        'service_id',
        'booking_date',
        'start_time',
        'end_time',
        'status',
        'notes',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'start_time' => 'string',
        'end_time' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function barber()
    {
        return $this->belongsTo(Barber::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function timeSlot()
    {
        return $this->hasOne(TimeSlot::class);
    }

    public function transaction()
    {
        return $this->hasOne(Transaction::class);
    }
}
