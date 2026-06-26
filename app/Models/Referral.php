<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Referral extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'referral_id';

    protected $fillable = [
        'referrer_id', 
        'referred_user_id', 
        'voucher_id', 
        'rewarded_at'
    ];

    protected function casts(): array
    {
        return [
            'rewarded_at' => 'datetime',
        ];
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id', 'user_id');
    }

    public function referredUser()
    {
        return $this->belongsTo(User::class, 'referred_user_id', 'user_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }
}