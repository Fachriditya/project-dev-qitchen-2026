<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'order_item_id';

    protected $fillable = [
        'order_id', 
        'menu_id', 
        'quantity', 
        'item_price', 
        'item_subtotal', 
        'item_notes'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id', 'order_id');
    }

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'menu_id', 'menu_id');
    }
}