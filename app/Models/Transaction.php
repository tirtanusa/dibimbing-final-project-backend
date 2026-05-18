<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    //
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'user_id',
        'booking_id',
        'subtotal_service',
        'subtotal_product',
        'total_payment',
        'payment_method',
        'status',
    ];
    protected $casts = [
        'subtotal_service' => 'decimal:2',
        'subtotal_product' => 'decimal:2',
        'total_payment' => 'decimal:2',
        'payment_method' => 'string',
        'status' => 'string',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function items()
    {
        return $this->hasMany(TransactionItem::class);
    }
}
