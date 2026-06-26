<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserVoucher extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'user_voucher_id';

    protected $fillable = [
        'user_id', 
        'voucher_id', 
        'is_used', 
        'used_at'
    ];

    protected function casts(): array
    {
        return [
            'is_used' => 'boolean',
            'used_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }
}