<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Voucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'voucher_id';

    protected $fillable = [
        'voucher_code', 
        'voucher_type', 
        'voucher_value', 
        'min_order', 
        'max_discount', 
        'quota', 
        'issued_count', 
        'expiration_date', 
        'is_active'
    ];

    protected function casts(): array
    {
        return [
            'expiration_date' => 'datetime',
            'is_active' => 'boolean',
        ];
    }

    public function userVouchers()
    {
        return $this->hasMany(UserVoucher::class, 'voucher_id', 'voucher_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'voucher_id', 'voucher_id');
    }
}