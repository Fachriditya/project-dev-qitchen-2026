<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Delivery extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'delivery_id';

    protected $fillable = [
        'order_id', 
        'courier_id', 
        'recipient_name', 
        'recipient_phone', 
        'delivery_address', 
        'latitude', 
        'longitude', 
        'delivery_status', 
        'courier_latitude', 
        'courier_longitude', 
        'delivery_date'
    ];

    protected function casts(): array
    {
        return [
            'delivery_date' => 'datetime',
        ];
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courier_id', 'user_id');
    }
}