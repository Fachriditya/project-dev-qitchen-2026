<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Membuat Master Voucher Welcome (Buy 1 Get 1)
        Voucher::create([
            'voucher_name' => 'Welcome Buy 1 Get 1 Free!',
            'voucher_code' => 'NEW-BOGO-2026',
            'voucher_description' => 'Spesial buat kamu pengguna baru! Beli 1 menu pilihan, dapatkan 1 gratis.',
            'voucher_type' => 'bogo',
            'voucher_value' => 1, // Artinya dapet 1 item gratis
            'min_order' => 0,
            'max_discount' => null,
            'quota' => 9999, // Kuota dibikin banyak karena untuk semua user baru
            'issued_count' => 0,
            'expiration_date' => Carbon::create(2026, 12, 31, 23, 59, 59),
            'is_active' => true,
        ]);

        // 2. Membuat Master Voucher Referral Reward (BARU DITAMBAHKAN)
        Voucher::create([
            'voucher_name' => 'Referral Bonus 50K!',
            'voucher_code' => 'REF-REWARD-2026', // Kode ini yang dicari oleh Model Order
            'voucher_description' => 'Terima kasih telah mengajak temanmu! Nikmati potongan Rp 50.000 untuk pesananmu berikutnya.',
            'voucher_type' => 'fixed', // Tipe potongan harga pas (bisa kamu ganti 'percentage' atau 'bogo')
            'voucher_value' => 100000, // Nominal diskon 50rb
            'min_order' => 0, // Syarat minimal belanja agar voucher ini bisa dipakai
            'max_discount' => null, // Karena tipe fixed, biarkan null
            'quota' => 9999, // Kuota master voucher
            'issued_count' => 0,
            'expiration_date' => Carbon::create(2026, 12, 31, 23, 59, 59), // Berlaku sampai akhir tahun promo
            'is_active' => true,
        ]);
    }
}