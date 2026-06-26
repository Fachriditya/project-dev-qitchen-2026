<?php

namespace Database\Seeders;

use App\Models\Article;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        $articles = [

            [
                'admin_id' => 1,
                'title' => '5 Tips Memilih Menu Favorit Saat Berkunjung ke Qitchen',
                'slug' => Str::slug('5 Tips Memilih Menu Favorit Saat Berkunjung ke Qitchen'),
                'thumbnail' => 'articles/tips-menu.jpg',
                'content' => 'Bingung memilih menu? Mulailah dari kategori favorit Anda seperti Indonesian, Japanese, atau Coffee. Jangan lupa tambahkan minuman signature agar pengalaman makan semakin lengkap.',
                'status' => 'published',
            ],

            [
                'admin_id' => 1,
                'title' => 'Mengapa Self-Service Restaurant Semakin Populer?',
                'slug' => Str::slug('Mengapa Self-Service Restaurant Semakin Populer?'),
                'thumbnail' => 'articles/self-service.jpg',
                'content' => 'Konsep self-service memberikan pengalaman yang lebih cepat, efisien, dan praktis. Pelanggan dapat melakukan pemesanan sendiri tanpa harus menunggu waiter sehingga waktu pelayanan menjadi lebih singkat.',
                'status' => 'published',
            ],

            [
                'admin_id' => 1,
                'title' => 'Cara Mendapatkan Voucher Referral Gratis',
                'slug' => Str::slug('Cara Mendapatkan Voucher Referral Gratis'),
                'thumbnail' => 'articles/referral.jpg',
                'content' => 'Bagikan kode referral Anda kepada teman. Ketika mereka berhasil mendaftar dan melakukan transaksi pertama, Anda dan teman Anda akan mendapatkan voucher Free 1 Cup Drink.',
                'status' => 'published',
            ],

            [
                'admin_id' => 1,
                'title' => 'Rekomendasi Menu Terlaris Minggu Ini',
                'slug' => Str::slug('Rekomendasi Menu Terlaris Minggu Ini'),
                'thumbnail' => 'articles/best-menu.jpg',
                'content' => 'Nasi Goreng Special, Chicken Ramen, Cappuccino, dan Chocolate Milkshake menjadi menu favorit pelanggan minggu ini. Cobalah salah satunya saat berkunjung ke Qitchen.',
                'status' => 'published',
            ],

            [
                'admin_id' => 1,
                'title' => 'Cara Melakukan Order Delivery di Qitchen',
                'slug' => Str::slug('Cara Melakukan Order Delivery di Qitchen'),
                'thumbnail' => 'articles/delivery.jpg',
                'content' => 'Pilih menu favorit Anda, tentukan metode Delivery saat checkout, masukkan alamat pengiriman, lakukan pembayaran, lalu pantau kurir secara realtime melalui halaman tracking.',
                'status' => 'published',
            ],

        ];

        foreach ($articles as $article) {

            Article::create([
                'admin_id'   => $article['admin_id'],
                'title'      => $article['title'],
                'slug'       => $article['slug'],
                'thumbnail'  => $article['thumbnail'],
                'content'    => $article['content'],
                'status'     => $article['status'],
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);

        }
    }
}