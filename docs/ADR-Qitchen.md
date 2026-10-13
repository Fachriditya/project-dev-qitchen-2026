# Catatan Keputusan Arsitektur (ADR) — Qitchen 2026

> **Status:** Aktif  
**Pemilik:** Muhammad Fachriditya  
**Terakhir Diperbarui:** 2026  
**Format:** [ADR Nathaniel](https://github.com/joelparkerhenderson/architecture-decision-record)

---

## ADR-001: Framework Backend — Laravel

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-001 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Fondasi |

### Konteks

Qitchen membutuhkan framework web full-stack yang mendukung arsitektur MVC, Eloquent ORM, autentikasi bawaan, middleware berbasis peran, dan pengembangan cepat untuk sistem manajemen restoran. Alternatif yang dipertimbangkan adalah Laravel (PHP), Django (Python), dan Next.js (TypeScript).

### Keputusan

Menggunakan **Laravel 13.x** sebagai framework backend.

### Konsekuensi

#### ✅ Kelebihan
- Scaffolding autentikasi bawaan (Breeze)
- Eloquent ORM menyederhanakan relasi restoran yang kompleks (Pesanan → Item → Menu → Kategori)
- Sistem middleware mendukung kontrol akses berbasis peran secara native
- Sistem antrean (Horizon) siap untuk fitur WebSocket/notifikasi di masa depan
- Blade templating memungkinkan rendering sisi server tanpa kerumitan SPA
- Ekosistem kaya: Socialite (OAuth), DomPDF, SDK Midtrans

#### ❌ Kekurangan
- PHP kurang berkinerja dibandingkan Go/Rust untuk skenario konkurensi tinggi
- Blade + Tailwind = HTML yang dirender server; tidak ada interaktivitas SPA tanpa Alpine.js
- Keranjang berbasis sesi tidak bertahan di berbagai perangkat

---

## ADR-002: Rendering Frontend — Blade + TailwindCSS (Tanpa SPA)

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-002 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Presentasi |

### Konteks

Qitchen menargetkan staf restoran (kasir, dapur, kurir) dan pelanggan. Staf membutuhkan pemuatan halaman cepat di perangkat kelas bawah. SPA berat seperti React/Vue akan menambah kompleksitas untuk tim satu orang. UI juga perlu responsif untuk seluler.

### Keputusan

Menggunakan template sisi server **Blade** dengan kelas utilitas **TailwindCSS**. Tanpa framework JS. Alpine.js minimal untuk komponen interaktif (keranjang, formulir checkout).

### Konsekuensi

#### ✅ Kelebihan
- Pemuatan halaman awal cepat (tanpa bundel JS yang perlu diunduh)
- Ramah SEO untuk halaman publik (menu, artikel)
- Pengembang tunggal dapat memelihara full-stack
- TailwindCSS menjamin desain responsif langsung

#### ❌ Kekurangan
- Papan dapur memerlukan muat ulang halaman penuh (polling 30 detik) — tanpa pembaruan real-time WebSocket
- Tidak ada manajemen state sisi klien — formulir direset pada kesalahan validasi
- Aplikasi seluler masa depan memerlukan lapisan API terpisah

---

## ADR-003: Basis Data — MySQL dengan Soft Delete

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-003 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Lapisan Data |

### Konteks

Data restoran bersifat misi-kritis: kehilangan pesanan, pembayaran, atau data pengguna tidak dapat diterima. Skema melibatkan penggabungan relasional yang kompleks (Pesanan → Item → Menu → Kategori → Pembayaran → Pengiriman). Pengembangan menggunakan SQLite untuk portabilitas; produksi membutuhkan MySQL.

### Keputusan

Menggunakan **MySQL** untuk produksi, **SQLite** untuk pengembangan. Menerapkan **SoftDeletes** di SEMUA tabel resource.

### Konsekuensi

#### ✅ Kelebihan
- Soft delete mencegah kehilangan data permanen (kolom `deleted_at` di 11 tabel)
- Kendala foreign key menegakkan integritas referensial (misalnya, tidak dapat menghapus menu dengan pesanan aktif)
- Dukungan JSON MySQL siap untuk analitik masa depan
- SQLite di pengembangan berarti tanpa pengaturan basis data untuk kontributor baru

#### ❌ Kekurangan
- Soft delete memerlukan pembatasan kueri tambahan (`->whereNull('deleted_at')`)
- Migrasi skema harus diurutkan dengan hati-hati (foreign key merujuk ke tabel sebelumnya)
- Pencarian teks lengkap MySQL tidak secanggih PostgreSQL tsvector

---

## ADR-004: Autentikasi — Email/Password + Google OAuth + Verifikasi OTP

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-004 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Keamanan |

### Konteks

Pelanggan membutuhkan pendaftaran yang mudah, tetapi sistem harus mencegah akun palsu (yang dapat menyalahgunakan hadiah referral). Restoran juga membutuhkan akses berbasis peran untuk staf. Opsi: email/password murni, OTP telepon, Google saja, atau hybrid.

### Keputusan

Menerapkan **pendaftaran 3-faktor**: Daftar dengan email/password ATAU Google OAuth → Verifikasi OTP melalui email → Akses berbasis peran. Reset password menggunakan OTP (bukan tautan email) untuk UX yang lebih cepat.

### Konsekuensi

#### ✅ Kelebihan
- OTP mencegah pendaftaran bot (mitigasi penipuan referral)
- Google OAuth mengurangi kelelahan password bagi pelanggan
- Peran dipetakan langsung ke middleware untuk otorisasi yang bersih
- Reset OTP lebih cepat daripada alur tautan email (tanpa penundaan klik)

#### ❌ Kekurangan
- Pengiriman OTP bergantung pada keandalan SMTP (downtime = tidak ada pendaftaran)
- Tidak ada OTP SMS telepon (hanya email) — pelanggan harus memeriksa kotak masuk
- Ketergantungan Google OAuth — jika API Google berubah, login rusak

---

## ADR-005: Implementasi Keranjang — Penyimpanan Sesi Sisi Server

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-005 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Fitur Pelanggan |

### Konteks

Keranjang perlu bertahan selama sesi penjelajahan pelanggan. Opsi: localStorage (sisi klien), sesi (sisi server), atau basis data (persisten di seluruh login). Restoran memiliki jumlah item rendah (menu < 100 item), sehingga kinerja bukan masalah.

### Keputusan

Menyimpan data keranjang di **sesi PHP** (sisi server).

### Konsekuensi

#### ✅ Kelebihan
- Implementasi sederhana — tidak memerlukan tabel basis data tambahan
- Data keranjang tidak terlihat atau dapat dimanipulasi oleh klien (keamanan)
- Berfungsi untuk pengguna tamu tanpa memerlukan login
- Dibersihkan secara otomatis saat sesi kedaluwarsa

#### ❌ Kekurangan
- Keranjang tidak bertahan di berbagai perangkat atau browser
- Hilang jika sesi kedaluwarsa atau cache dibersihkan
- Tidak cocok untuk skenario restoran multi-perangkat

---

## ADR-006: Gateway Pembayaran — Midtrans (Snap API)

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-006 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Pembayaran |

### Konteks

Qitchen membutuhkan metode pembayaran Indonesia: QRIS (kode QR), Virtual Account (BCA/BNI/Mandiri), dan tunai. Midtrans adalah pemimpin pasar di Indonesia untuk pembayaran online. Alternatif: Xendit, iPaymu, atau transfer bank manual.

### Keputusan

Mengintegrasikan **Midtrans Snap API** untuk pembayaran online. Pembayaran tunai ditangani melalui alur POS kasir (offline).

### Konsekuensi

#### ✅ Kelebihan
- Mendukung QRIS + Multiple Virtual Account langsung
- Mode popup Snap memungkinkan pembayaran inline tanpa pengalihan
- Webhook (notifikasi HTTP) memperbarui status pembayaran secara otomatis
- Lingkungan Sandbox tersedia untuk pengembangan

#### ❌ Kekurangan
- Memerlukan persetujuan akun merchant (bisa memakan waktu berhari-hari)
- Biaya transaksi per pembayaran (biasanya 2-3% untuk QRIS)
- Ketergantungan API — jika Midtrans down, pembayaran online gagal

---

## ADR-007: Sinkronisasi Status Pembayaran — Webhook + Verifikasi Tanda Tangan

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-007 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Pembayaran |

### Konteks

Setelah pelanggan membayar melalui Midtrans Snap, status pesanan harus berubah dari "tertunda" menjadi "lunas" secara otomatis. Melakukan polling pada API Midtrans boros; webhook berbasis panggilan balik adalah standar tetapi memerlukan verifikasi keamanan.

### Keputusan

Menggunakan **Webhook Midtrans (HTTP POST)** dengan **verifikasi tanda tangan SHA-512**. Titik akhir webhook bersifat publik (tanpa CSRF) tetapi memvalidasi hash `signature_key` sebelum diproses.

### Konsekuensi

#### ✅ Kelebihan
- Notifikasi pembayaran instan (tanpa penundaan polling)
- Verifikasi SHA-512 mencegah panggilan balik palsu
- Pemeriksaan idempotensi (`order_status == 'paid'`) mencegah pemrosesan ganda
- Mencatat semua upaya webhook untuk jejak audit

#### ❌ Kekurangan
- URL Webhook harus dapat diakses publik (memerlukan server yang digunakan)
- Midtrans dapat mengirim notifikasi duplikat (ditangani melalui pemeriksaan idempotensi)
- Tidak ada antrean — webhook diproses secara sinkron; jika basis data lambat, Midtrans mungkin timeout

---

## ADR-008: Kontrol Akses Berbasis Peran — Middleware per Peran (5 Peran)

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-008 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Otorisasi |

### Konteks

Operasi restoran memerlukan pemisahan kepentingan yang ketat: pelanggan tidak boleh mengakses dasbor admin, kurir tidak boleh mengedit menu, dll. Penjaga auth bawaan Laravel + middleware kustom menawarkan cara yang bersih untuk menegakkan ini.

### Keputusan

Menentukan **5 peran** sebagai kolom enum pada tabel `users`: `admin`, `cashier`, `kitchen`, `courier`, `customer`. Membuat **5 kelas middleware** (`IsAdmin`, `IsCashier`, `IsKitchen`, `IsCourier`, `IsCustomer`) yang memeriksa `auth()->user()->role`.

### Konsekuensi

#### ✅ Kelebihan
- Nama middleware eksplisit dan mudah dibaca
- Setiap grup rute hanya merantai `['auth', 'isAdmin']` — tanpa logika boolean
- Kolom peran diindeks untuk kueri cepat
- Mudah menambahkan peran baru (cukup buat kelas middleware baru)

#### ❌ Kekurangan
- String peran dikodekan keras — tidak ada manajemen peran dinamis (peran baru memerlukan perubahan kode)
- Middleware melempar 403 tanpa pengalihan — pengguna melihat halaman error, bukan dasbor mereka
- Middleware `IsCustomer` ada tetapi **tidak pernah digunakan** di rute (rute pelanggan hanya menggunakan middleware `auth`)

---

## ADR-009: Peta & Pelacakan Lokasi — Leaflet.js + OpenStreetMap

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-009 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Fitur Pengiriman |

### Konteks

Kurir perlu melihat tujuan pengiriman di peta. Pelanggan perlu melacak lokasi kurir. Opsi: Google Maps (memerlukan kunci API, berbayar setelah kredit $200), Mapbox (berbayar), atau OpenStreetMap (gratis).

### Keputusan

Menggunakan **Leaflet.js** (pustaka frontend) dengan lapisan petak **OpenStreetMap**. Tidak memerlukan kunci API. Pembaruan lokasi kurir melalui API Geolokasi browser.

### Konsekuensi

#### ✅ Kelebihan
- Biaya nol — tanpa biaya penggunaan API
- Tanpa masalah manajemen kunci API atau penagihan
- Leaflet ringan (~40KB terkompresi gzip)
- Petak OpenStreetMap dipelihara oleh komunitas dan andal

#### ❌ Kekurangan
- Petak OpenStreetMap kurang detail dibandingkan Google Maps (tanpa tampilan dalam ruangan, lebih sedikit POI)
- API Geolokasi Browser memerlukan HTTPS (localhost berfungsi, tetapi produksi harus memiliki SSL)
- Tidak ada navigasi belokan demi belokan — hanya peta visual
- Pelacakan lokasi latar belakang memerlukan aplikasi seluler (tidak tersedia di browser)

---

## ADR-010: Pembuatan Laporan PDF — Laravel DomPDF

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-010 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Pelaporan |

### Konteks

Admin perlu mengekspor laporan penjualan sebagai PDF untuk akuntansi dan audit. Opsi: DomPDF (PHP, dirender server), Browsershot (Puppeteer, lebih berat), atau cetak-ke-PDF sisi klien.

### Keputusan

Menggunakan **barryvdh/laravel-dompdf** — pembungkus Laravel untuk DomPDF.

### Konsekuensi

#### ✅ Kelebihan
- Rendering sisi server — PDF dihasilkan di server, tidak bergantung pada browser klien
- Menggunakan template Blade — menggunakan kembali struktur tampilan laporan yang ada
- API sederhana: `PDF::loadView('report', $data)->download()`
- Tanpa ketergantungan layanan eksternal (tidak seperti Puppeteer yang membutuhkan Chrome)

#### ❌ Kekurangan
- DomPDF memiliki dukungan CSS terbatas (Flexbox tidak didukung sepenuhnya, Grid tidak didukung)
- Laporan besar dengan banyak pesanan mungkin timeout atau melebihi memori
- Tanpa streaming — seluruh PDF dibangun di memori sebelum diunduh

---

## ADR-011: Sistem Referral — Distribusi Hadiah Berbasis Peristiwa

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-011 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Loyalitas |

### Konteks

Ketika pelanggan yang dirujuk menyelesaikan pesanan pertama mereka, pemberi rujukan harus menerima voucher hadiah. Ini perlu terjadi secara andal tanpa intervensi manual. Dua pendekatan: sinkron (di controller/webhook) atau berbasis peristiwa (Laravel Events + Listeners).

### Keputusan

Menerapkan distribusi hadiah referral secara **sinkron** di dua tempat:
1. **Peristiwa `updated` model Order** — ketika status pesanan berubah menjadi `completed`
2. **Controller Webhook** — ketika pembayaran dikonfirmasi sebagai `paid`

Keduanya memeriksa `$completedOrdersCount === 1` untuk memastikan hadiah hanya untuk pesanan pertama.

### Konsekuensi

#### ✅ Kelebihan
- Distribusi hadiah instan (tanpa penundaan antrean)
- Penjagaan ganda mencegah bypass hadiah (pemeriksaan model Order + Webhook)
- Kolom `referred_by` pada tabel users menyederhanakan kueri
- Riwayat referral disimpan di tabel `referrals` untuk audit

#### ❌ Kekurangan
- Logika diduplikasi di dua tempat (peristiwa model Order + MidtransWebhookController) — melanggar DRY
- Tanpa antrean — jika pembuatan voucher gagal selama webhook, hadiah hilang
- Kondisi balapan: jika webhook dan peristiwa model menyala bersamaan, hadiah bisa diberikan dua kali

---

## ADR-012: Tanpa WebSocket / Real-Time — Pengganti Polling

| Bidang | Nilai |
|--------|-------|
| **ID** | ADR-012 |
| **Tanggal** | 2026-05-01 |
| **Status** | ✅ Diterima |
| **Lingkup** | Infrastruktur |

### Konteks

Papan dapur, pelacakan pesanan, dan lokasi kurir idealnya diperbarui secara real-time. Dukungan WebSocket penuh (Laravel Reverb atau Pusher) menambah biaya infrastruktur yang signifikan untuk proyek pribadi.

### Keputusan

Menggunakan **polling** (muat ulang halaman periodik / polling AJAX) sebagai pengganti WebSocket. Papan dapur disegarkan setiap 30 detik. Lokasi kurir dikirim melalui HTTP POST, bukan dialirkan.

### Konsekuensi

#### ✅ Kelebihan
- Biaya infrastruktur nol — tidak perlu server WebSocket
- Implementasi sederhana — `setTimeout(window.location.reload(), 30000)`
- Berfungsi di semua lingkungan hosting (shared hosting, VPS)
- Tanpa manajemen status koneksi

#### ❌ Kekurangan
- Keterlambatan 30 detik pada pembaruan papan dapur
- Beban server lebih tinggi karena permintaan halaman berulang
- Tidak benar-benar "real-time" — pembaruan posisi kurir adalah "terakhir diketahui"
- Pemborosan bandwidth dari muat ulang halaman penuh, bukan pembaruan DOM parsial

---

## Tabel Ringkasan ADR

| ID | Keputusan | Status | Prioritas |
|----|-----------|--------|-----------|
| ADR-001 | Laravel 13.x Framework | ✅ Diterima | Kritis |
| ADR-002 | Blade + TailwindCSS (Tanpa SPA) | ✅ Diterima | Kritis |
| ADR-003 | MySQL + Soft Delete | ✅ Diterima | Kritis |
| ADR-004 | Email/Password + Google OAuth + OTP | ✅ Diterima | Kritis |
| ADR-005 | Keranjang Sesi Sisi Server | ✅ Diterima | Tinggi |
| ADR-006 | Midtrans Snap API | ✅ Diterima | Kritis |
| ADR-007 | Webhook + Verifikasi SHA-512 | ✅ Diterima | Kritis |
| ADR-008 | Middleware per Peran (5 Peran) | ✅ Diterima | Kritis |
| ADR-009 | Leaflet.js + OpenStreetMap | ✅ Diterima | Tinggi |
| ADR-010 | Laravel DomPDF | ✅ Diterima | Sedang |
| ADR-011 | Hadiah Referral Berbasis Peristiwa | ✅ Diterima | Tinggi |
| ADR-012 | Polling (Tanpa WebSocket) | ✅ Diterima | Sedang |

---

*Kembali ke [docs/](./)*