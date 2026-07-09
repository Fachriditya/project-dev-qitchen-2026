<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Voucher;
use App\Models\UserVoucher;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $primaryKey = 'user_id';

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'profile_picture',
        'google_id',
        'otp',
        'role',
        'phone',
        'referral_code',
        'referred_by',
        'email_verified_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'user_id', 'user_id');
    }

    protected static function booted()
    {
        // 1. SENSOR CREATING: Menyala SESAAT SEBELUM data user masuk ke Database
        static::creating(function ($user) {
            // Buat Referral Code Otomatis jika masih kosong
            if (empty($user->referral_code)) {
                // Ambil 4 huruf pertama dari nama (buang spasi/simbol)
                $cleanName = strtoupper(substr(preg_replace('/[^a-zA-Z0-9]/', '', $user->name), 0, 4));
                // Generate 4 huruf/angka acak
                $randomString = strtoupper(\Illuminate\Support\Str::random(4));
                
                // Hasilnya misal: QITCHEN-FACH-A1B2
                $user->referral_code = 'QITCHEN-' . $cleanName . '-' . $randomString;
            }
        });

        // 2. SENSOR CREATED: Menyala SETELAH data user masuk ke Database (Ini kodingan Voucher yang kemarin)
        static::created(function ($user) {
            if ($user->role === 'customer') {
                $welcomeVoucher = \App\Models\Voucher::where('voucher_code', 'NEW-BOGO-2026')
                                    ->where('is_active', true)
                                    ->first();

                if ($welcomeVoucher) {
                    \App\Models\UserVoucher::create([
                        'user_id' => $user->user_id,
                        'voucher_id' => $welcomeVoucher->voucher_id,
                        'is_used' => false,
                        'expired_at' => \Carbon\Carbon::now()->addMonths(1)
                    ]);
                    $welcomeVoucher->increment('issued_count');
                }
            }
        });
    }

}