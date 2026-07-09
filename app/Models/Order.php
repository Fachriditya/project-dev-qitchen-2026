<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory, SoftDeletes;

    protected $primaryKey = 'order_id';

    protected $fillable = [
        'user_id', 
        'order_number', 
        'order_type', 
        'order_status', 
        'order_subtotal', 
        'order_discount', 
        'order_total', 
        'voucher_id', 
        'order_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function voucher()
    {
        return $this->belongsTo(Voucher::class, 'voucher_id', 'voucher_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'order_id');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class, 'order_id', 'order_id');
    }

    public function delivery()
    {
        return $this->hasOne(Delivery::class, 'order_id', 'order_id');
    }

    /**
     * Sensor Model Events Laravel untuk Order
     */
    protected static function booted()
    {
        // Event 'updated' menyala tiap kali ada perubahan data pada order yang sudah ada di database
        static::updated(function ($order) {
            
            // 1. Cek apakah yang diubah itu kolom status, dan status barunya adalah 'completed'
            // (Sesuaikan kata 'completed' dengan status sukses yang kamu pakai di aplikasimu, misal 'success' atau 'delivered')
            if ($order->isDirty('status') && $order->status === 'completed') {
                
                $customer = $order->user;

                // 2. Cek apakah customer ini mendaftar dari jalur undangan (punya referred_by)
                if ($customer && $customer->referred_by) {
                    
                    // 3. PENCEGAHAN SPAM: Pastikan ini benar-benar pesanan sukses PERTAMA customer tersebut
                    $completedOrdersCount = \App\Models\Order::where('user_id', $customer->user_id) // Sesuaikan nama kolom ID-mu
                                                ->where('status', 'completed')
                                                ->count();
                    
                    // Karena status pesanan ini baru saja jadi completed, maka totalnya di DB harus pas 1
                    if ($completedOrdersCount === 1) {
                        
                        // 4. Cari si Pengajak (Referrer)
                        $referrer = \App\Models\User::find($customer->referred_by);

                        if ($referrer) {
                            
                            // 5. Cari Master Voucher Hadiah Referral
                            $referralVoucher = \App\Models\Voucher::where('voucher_code', 'REF-REWARD-2026')
                                                ->where('is_active', true)
                                                ->first();

                            // 6. Suntikkan ke dompet Pengajak!
                            if ($referralVoucher) {
                                \App\Models\UserVoucher::create([
                                    'user_id' => $referrer->user_id, // Sesuaikan kolom ID (id atau user_id)
                                    'voucher_id' => $referralVoucher->voucher_id,
                                    'is_used' => false,
                                    'expired_at' => \Carbon\Carbon::now()->addMonths(1) // Hangus dalam 1 bulan
                                ]);
                                
                                $referralVoucher->increment('issued_count');
                            }
                        }
                    }
                }
            }
        });
    }
}