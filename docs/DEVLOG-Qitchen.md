# Log Pengembangan — Qitchen 2026

> **Status:** Aktif  
**Pemilik:** Muhammad Fachriditya  
**Repositori:** [Fachriditya/project-dev-qitchen-2026](https://github.com/Fachriditya/project-dev-qitchen-2026)  
**Terakhir Diperbarui:** 2026

---

## 1. Persiapan Proyek

### 1.1 Scaffold Awal

```bash
# Dibuat menggunakan Laravel 13.x + Breeze
composer create-project laravel/laravel qitchen-2026
composer require laravel/breeze --dev
php artisan breeze:install blade

# Dependensi inti
composer require laravel/socialite
composer require midtrans/midtrans-php
composer require barryvdh/laravel-dompdf

# Frontend
npm install && npm run build
```

### 1.2 Basis Data

```bash
# Pengembangan: SQLite
touch database/database.sqlite

# Produksi: MySQL (konfigurasi .env)
# DB_CONNECTION=mysql
# DB_DATABASE=qitchen
# DB_USERNAME=root
# DB_PASSWORD=...
```

### 1.3 Variabel Lingkungan

```env
# .env (diperlukan)
APP_NAME=Qitchen
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Basis Data
DB_CONNECTION=sqlite

# Midtrans
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=${APP_URL}/auth/google/callback

# Email (OTP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
```

### 1.4 Struktur Direktori (Jalur Utama)

```
qitchen-2026/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # 9 controller
│   │   │   ├── Api/         # MidtransWebhookController
│   │   │   ├── Auth/        # Registrasi, Login, OTP, Google, LupaPassword
│   │   │   ├── Cashier/     # PesananManual, Pickup, Transaksi
│   │   │   ├── Courier/     # DeliveryOfferController
│   │   │   ├── Customer/    # Profil, Pesanan, Voucher, Referral
│   │   │   ├── Kitchen/     # KitchenController
│   │   │   └── Public/      # Beranda, Menu, Keranjang, Checkout, Lacak, Artikel
│   │   ├── Middleware/       # IsAdmin, IsCashier, IsKitchen, IsCourier, IsCustomer
│   │   └── Requests/        # Form request auth
│   ├── Models/               # 11 model Eloquent
│   └── Providers/
├── config/                   # midtrans.php, app.php
├── database/
│   ├── migrations/           # 11 file migrasi
│   └── seeders/              # Pengguna, Kategori, Menu, Voucher, Artikel
├── resources/views/          # Template Blade (7 subdirektori)
├── routes/
│   ├── web.php               # Definisi rute utama
│   └── auth.php              # Rute auth Breeze
├── docs/                     # Dokumentasi
└── tests/                    # File pengujian PHPUnit
```

---

## 2. Pelacakan Milestone

### 2.1 Template Milestone

```markdown
## Milestone: [NAMA_MILESTONE]

**Tanggal Target:** YYYY-MM-DD  
**Status:** 🟢 On Track / 🟡 Beresiko / 🔴 Terlambat  
**Pemilik:** @username

### Lingkup
- [ ] Fitur A
- [ ] Fitur B

### Hasil
- [ ] PR #xx digabung
- [ ] Di-deploy ke staging

### Penghambat
- [ ] Deskripsi penghambat

### Metrik
- [ ] Cakupan pengujian ≥ 80%
- [ ] Semua kriteria penerimaan terpenuhi
```

### 2.2 Linimasa Milestone

| Milestone | Deskripsi | Target | Status |
|-----------|-----------|--------|--------|
| M0 | Fondasi — Auth, Peran, Middleware, Basis Data | Minggu 1-2 | ✅ Selesai |
| M1 | Pemesanan Inti — Menu, Keranjang, Checkout, Pembayaran | Minggu 3-5 | ✅ Selesai |
| M2 | Operasional — Papan Dapur, Pengiriman, POS Kasir | Minggu 6-8 | ✅ Selesai |
| M3 | Loyalitas — Voucher, Referral, Artikel | Minggu 9-10 | ✅ Selesai |
| M4 | Manajemen — Dashboard, Laporan, Ekspor PDF | Minggu 11-12 | ✅ Selesai |
| M5 | Poles — Perbaikan bug, Tinjauan keamanan, Dokumentasi | Minggu 13-14 | 🟡 Berlangsung |

---

## 3. Log Perbaikan Bug

### Template

```markdown
## BUG-[ID]: [Judul Singkat]

**Severitas:** 🔴 Kritis / 🟡 Tinggi / 🟢 Sedang / 🔵 Rendah  
**Status:** Terbuka / Dalam Pengerjaan / Terselesaikan / Terverifikasi  
**Ditemukan:** YYYY-MM-DD  
**Diperbaiki:** YYYY-MM-DD  
**Commit:** `[hash]`

### Deskripsi

### Akar Masalah

### Langkah Reproduksi

### Perbaikan

### Risiko Regresi
```

### Entri Bug

---

## BUG-001: Hadiah Referral Tidak Terpicu Akibat Nama Kolom Salah

**Severitas:** 🔴 Kritis  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

Voucher hadiah referral tidak pernah didistribusikan ke pemberi rujukan karena event model Order memeriksa `$order->isDirty('status')` dan `$order->status === 'completed'`, tetapi kolom basis data bernama `order_status`, bukan `status`.

### Akar Masalah

Kesalahan ketik di event model Eloquent. Event `updated` pada model Order mereferensikan nama atribut yang salah.

### Perbaikan

Mengubah `status` → `order_status` di semua 4 lokasi dalam metode `Order::booted()`.

### Risiko Regresi

Rendah. Tidak ada kode lain yang mereferensikan `$order->status` sebagai atribut langsung.

---

## BUG-002: Ketidakcocokan Kode Voucher Webhook

**Severitas:** 🔴 Kritis  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

`MidtransWebhookController` mencari kode voucher `REF-BONUS` tetapi `VoucherSeeder` membuat voucher dengan kode `REF-REWARD-2026`. Hadiah referral tidak pernah dibuat melalui webhook.

### Akar Masalah

Ketidakcocokan string yang dikodekan keras antara controller dan seeder.

### Perbaikan

Mengubah `REF-BONUS` → `REF-REWARD-2026` di controller webhook. Juga menambahkan panggilan `Referral::create()` untuk menyimpan catatan hadiah.

### Risiko Regresi

Rendah. Hanya memengaruhi jalur hadiah referral yang sebelumnya tidak berfungsi.

---

## BUG-003: Catatan Pengiriman Tidak Dibuat Saat Checkout

**Severitas:** 🔴 Kritis  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

Ketika pelanggan memilih "delivery" sebagai tipe pesanan saat checkout, tidak ada catatan tabel `deliveries` yang dibuat. Alamat dan koordinat dari formulir checkout diabaikan. Catatan pengiriman hanya dibuat saat kurir mengklik "Terima", menggunakan data placeholder.

### Akar Masalah

Metode `CheckoutController@process` tidak menyertakan panggilan `Delivery::create()` untuk pesanan tipe delivery.

### Perbaikan

Menambahkan pembuatan catatan pengiriman di dalam blok transaksi, menyimpan nama penerima, telepon, alamat, lintang, dan bujur.

### Risiko Regresi

Rendah. Pembuatan pengiriman hanya terpicu untuk `order_type === 'delivery'`.

---

## BUG-004: Admin Tidak Dapat Membuat Voucher BOGO

**Severitas:** 🟡 Tinggi  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

Aturan validasi voucher hanya mengizinkan `percentage,fixed,free_delivery` — tetapi enum basis data dan PRD menentukan 4 tipe termasuk `bogo`.

### Akar Masalah

Aturan validasi tidak memiliki opsi `bogo`.

### Perbaikan

Menambahkan `bogo` ke `in:percentage,fixed,bogo,free_delivery` di metode `store()` dan `update()`.

### Risiko Regresi

Rendah.

---

## BUG-005: Validasi Peran Pengguna Menyertakan Peran Tidak Valid

**Severitas:** 🟡 Tinggi  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

`UserController@store` dan `@update` memvalidasi peran dengan `in:super_admin,admin,customer,cashier,kitchen,courier`, tetapi enum basis data hanya memiliki `admin,cashier,kitchen,courier,customer`. Mengirim `super_admin` menyebabkan kegagalan insert basis data.

### Akar Masalah

Aturan validasi tidak selaras dengan enum migrasi basis data.

### Perbaikan

Diubah menjadi `in:admin,cashier,kitchen,courier,customer`.

### Risiko Regresi

Rendah.

---

## BUG-006: Peta Kurir Menampilkan Koordinat Kode Keras

**Severitas:** 🟡 Tinggi  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

Peta Leaflet di tampilan kurir dan pelacakan menggunakan koordinat kode keras `(-7.2756, 112.7939)` alih-alih membaca dari `delivery.latitude` / `delivery.longitude`.

### Akar Masalah

Koordinat placeholder statis di template Blade.

### Perbaikan

Memperbarui tampilan peta untuk menggunakan `{{ $order->delivery->latitude ?? -7.2756 }}` dan `{{ $order->delivery->longitude ?? 112.7939 }}`.

### Risiko Regresi

Rendah.

---

## BUG-007: Nama Tipe Voucher Tidak Konsisten

**Severitas:** 🟡 Tinggi  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

Perhitungan diskon checkout menggunakan `case 'delivery':` tetapi enum basis data dan migrasi menggunakan `free_delivery`. Voucher pengiriman gratis tidak pernah menerapkan diskon.

### Akar Masalah

Penamaan tidak konsisten antara PRD (yang menyebutnya `free_delivery`) dan logika checkout.

### Perbaikan

Mengubah `case 'delivery':` → `case 'free_delivery':` di metode `index()` dan `process()`.

### Risiko Regresi

Rendah.

---

## BUG-008: Titik Akhir Pembaruan Lokasi Kurir Hilang

**Severitas:** 🟢 Sedang  
**Status:** Terselesaikan  
**Ditemukan:** 2026-06-25  
**Diperbaiki:** 2026-06-25  
**Commit:** `f521539`

### Deskripsi

FR-38 menentukan kurir harus dapat memperbarui lintang/bujur mereka. Rute dan metode controller tidak ada.

### Perbaikan

Menambahkan rute `POST /courier/map/{id}/update-location` dan metode `updateLocation()` ke `DeliveryOfferController`. Memperbarui tampilan peta dengan tombol "Perbarui Lokasi Saya" menggunakan API Geolokasi browser.

### Risiko Regresi

Rendah.

---

## 4. Log Refactoring

### Template

```markdown
## REF-[ID]: [Judul Singkat]

**Lingkup:** [Berkas/Modul]  
**Status:** Diusulkan / Dalam Pengerjaan / Selesai  
**Tanggal:** YYYY-MM-DD

### Motivasi

### Perubahan

### Sebelum vs Sesudah

### Verifikasi
```

### Entri Refactoring

---

## REF-001: Ekstrak Logika Perhitungan Diskon

**Lingkup:** `CheckoutController.php`  
**Status:** ⏳ Diusulkan  
**Tanggal:** 2026

### Motivasi

Logika perhitungan diskon (menerapkan tipe voucher: percentage, fixed, bogo, free_delivery) digandakan persis di metode `index()` dan `process()`. Setiap perubahan pada aturan diskon harus dilakukan di dua tempat — risiko pemeliharaan.

### Solusi yang Diusulkan

Ekstrak ke metode privat `hitungDiskon($cart, $subtotal, $biayaLayanan, $voucherAktif)` yang mengembalikan `$diskon`. Baik `index()` maupun `process()` memanggil metode ini.

### Sebelum

```php
// CheckoutController@index — 30 baris logika diskon
// CheckoutController@process — 30 baris logika diskon identik
```

### Sesudah

```php
private function hitungDiskon($cart, $subtotal, $biayaLayanan, $voucherAktif) {
    $diskon = 0;
    // ... logika sekali
    return $diskon;
}

public function index() {
    // ...
    $diskon = $this->hitungDiskon($cart, $subtotal, $biayaLayanan, $voucherAktif);
    // ...
}

public function process() {
    // ...
    $diskon = $this->hitungDiskon($cart, $subtotal, $biayaLayanan, $voucherAktif);
    // ...
}
```

---

## REF-002: Konsolidasi Logika Hadiah Referral

**Lingkup:** `MidtransWebhookController.php` + `Order.php`  
**Status:** ⏳ Diusulkan  
**Tanggal:** 2026

### Motivasi

Logika distribusi hadiah referral ada di DUA tempat:
1. Event `updated` `Order::booted()` — terpicu saat pesanan menjadi `completed`
2. `MidtransWebhookController@handler` — terpicu saat pembayaran dikonfirmasi `paid`

Keduanya melakukan hal yang sama (cari pemberi rujukan, periksa pesanan pertama, buat hadiah voucher) tetapi dengan implementasi yang sedikit berbeda. Ini adalah pelanggaran DRY dan risiko kondisi balapan.

### Solusi yang Diusulkan

Ekstrak logika referral ke kelas aksi atau kelas layanan Laravel khusus `LayananReferral::beriHadiah($pesanan)`.

```php
class LayananReferral {
    public static function beriHadiah(Pesanan $pesanan): void {
        $pelanggan = $pesanan->user;
        if (!$pelanggan || !$pelanggan->referred_by) return;

        // Periksa pesanan pertama yang selesai
        // Cari pemberi rujukan
        // Buat hadiah VoucherPengguna
        // Catat ke tabel referrals
    }
}
```

---

## 5. Daftar Utang Teknis

| ID | Item | Area | Severitas | Dibuat | 
|----|------|------|-----------|--------|
| TD-001 | Logika diskon digandakan di `index()` dan `process()` | CheckoutController | Sedang | 2026 |
| TD-002 | Logika hadiah referral digandakan di dua tempat | Order + Webhook | Tinggi | 2026 |
| TD-003 | Middleware `IsCustomer` ada tetapi tidak digunakan di rute | Middleware | Rendah | 2026 |
| TD-004 | Tidak ada throttle/pembatasan kecepatan di titik akhir login | Auth | Sedang | 2026 |
| TD-005 | Papan dapur menggunakan muat ulang halaman penuh (polling 30 detik) | Dapur | Sedang | 2026 |
| TD-006 | Tidak ada pencatatan audit login gagal | Keamanan | Rendah | 2026 |
| TD-007 | Lokasi restoran kode keras (-7.2756, 112.7939) | Config | Rendah | 2026 |
| TD-008 | Cakupan pengujian di bawah 80% | Testing | Tinggi | 2026 |

---

## 6. Catatan Lingkungan & Deployment

### Lingkungan Dev

```bash
# Memulai semua layanan (dari composer.json)
composer run dev

# Ini menjalankan:
# - php artisan serve          (Server di :8000)
# - php artisan queue:listen   (Worker antrean)
# - php artisan pail           (Penampil log)
# - npm run dev                (Vite HMR)
```

### Perintah Basis Data

```bash
# Migrasi segar dengan seeder
php artisan migrate:fresh --seed

# Menjalankan seeder tertentu
php artisan db:seed --class=VoucherSeeder
```

### Pengujian

```bash
# Menjalankan semua pengujian
php artisan test

# Menjalankan file pengujian tertentu
php artisan test --filter=CheckoutTest
```

---

*Kembali ke [docs/](./)*