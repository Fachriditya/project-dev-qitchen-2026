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
                'article_title' => '5 Tips Memilih Menu Favorit Saat Berkunjung ke Qitchen',
                'article_slug' => Str::slug('5 Tips Memilih Menu Favorit Saat Berkunjung ke Qitchen'),
                'article_category' => 'Tips',
                'thumbnail' => 'articles/tips-menu.jpg',
                'article_content' => 'Bingung memilih menu? Mulailah dari kategori favorit Anda seperti Indonesian, Japanese, atau Coffee. Jangan lupa tambahkan minuman signature agar pengalaman makan semakin lengkap.',
                'is_published' => true,
            ],

            [
                'admin_id' => 1,
                'article_title' => 'Mengapa Self-Service Restaurant Semakin Populer?',
                'article_slug' => Str::slug('Mengapa Self-Service Restaurant Semakin Populer?'),
                'article_category' => 'News',
                'thumbnail' => 'articles/self-service.jpg',
                'article_content' => 'Konsep self-service memberikan pengalaman yang lebih cepat, efisien, dan praktis. Pelanggan dapat melakukan pemesanan sendiri tanpa harus menunggu waiter sehingga waktu pelayanan menjadi lebih singkat.',
                'is_published' => true,
            ],

            [
                'admin_id' => 1,
                'article_title' => 'Cara Mendapatkan Voucher Referral Gratis',
                'article_slug' => Str::slug('Cara Mendapatkan Voucher Referral Gratis'),
                'article_category' => 'Promo',
                'thumbnail' => 'articles/referral.jpg',
                'article_content' => 'Bagikan kode referral Anda kepada teman. Ketika mereka berhasil mendaftar dan melakukan transaksi pertama, Anda dan teman Anda akan mendapatkan voucher Free 1 Cup Drink.',
                'is_published' => true,
            ],

            [
                'admin_id' => 1,
                'article_title' => 'Rekomendasi Menu Terlaris Minggu Ini',
                'article_slug' => Str::slug('Rekomendasi Menu Terlaris Minggu Ini'),
                'article_category' => 'News',
                'thumbnail' => 'articles/best-menu.jpg',
                'article_content' => 'Nasi Goreng Special, Chicken Ramen, Cappuccino, dan Chocolate Milkshake menjadi menu favorit pelanggan minggu ini. Cobalah salah satunya saat berkunjung ke Qitchen.',
                'is_published' => true,
            ],

            [
                'admin_id' => 1,
                'article_title' => 'Cara Melakukan Order Delivery di Qitchen',
                'article_slug' => Str::slug('Cara Melakukan Order Delivery di Qitchen'),
                'article_category' => 'Tips',
                'thumbnail' => 'articles/delivery.jpg',
                'article_content' => 'Pilih menu favorit Anda, tentukan metode Delivery saat checkout, masukkan alamat pengiriman, lakukan pembayaran, lalu pantau kurir secara realtime melalui halaman tracking.',
               'is_published' => true,
            ],

        ];

        foreach ($articles as $article) {

            Article::create([
                'admin_id'           => $article['admin_id'],
                'article_title'      => $article['article_title'],
                'article_slug'       => $article['article_slug'],
                'article_category'   => $article['article_category'],
                'thumbnail'          => $article['thumbnail'],
                'article_content'    => $article['article_content'],
                'is_published'       => $article['is_published'],
                'created_at'         => Carbon::now(),
                'updated_at'         => Carbon::now(),
            ]);

        }
    }
}