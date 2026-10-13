# 🍽️ Qitchen — Business Requirement Document (BRD)

> **Versi:** 1.0  
> **Tanggal:** 2026  
> **Status:** Final  
> **Project Type:** Personal Project — Full-Stack Web Application  
> **Dokumen Terkait:** [PRD-Qitchen.md](./PRD-Qitchen.md), [SRS - Qitchen.docx](./SRS%20-%20Qitchen.docx)

---

## Daftar Isi

1. [Executive Summary](#1-executive-summary)
2. [Profil Bisnis & Latar Belakang](#2-profil-bisnis--latar-belakang)
3. [Pernyataan Masalah Bisnis](#3-pernyataan-masalah-bisnis)
4. [Tujuan & Sasaran Bisnis](#4-tujuan--sasaran-bisnis)
5. [Analisis Pasar & Kompetitor](#5-analisis-pasar--kompetitor)
6. [Stakeholder & Target Pengguna](#6-stakeholder--target-pengguna)
7. [Cakupan Bisnis (Scope)](#7-cakupan-bisnis-scope)
8. [Kebutuhan Bisnis Fungsional](#8-kebutuhan-bisnis-fungsional)
9. [Kebutuhan Bisnis Non-Fungsional](#9-kebutuhan-bisnis-non-fungsional)
10. [Analisis ROI & Metrik Kesuksesan](#10-analisis-roi--metrik-kesuksesan)
11. [Model Pendapatan (Revenue Model)](#11-model-pendapatan-revenue-model)
12. [Analisis Risiko & Mitigasi](#12-analisis-risiko--mitigasi)
13. [Asumsi & Ketergantungan](#13-asumsi--ketergantungan)
14. [Rencana Implementasi](#14-rencana-implementasi)
15. [Glosarium](#15-glosarium)

---

## 1. Executive Summary

**Qitchen** adalah sistem manajemen restoran digital all-in-one yang dirancang untuk menyelesaikan masalah operasional restoran kecil hingga menengah. Platform ini menyatukan seluruh alur bisnis restoran — pemesanan, pembayaran, dapur, pengiriman, loyalitas pelanggan, dan pelaporan — dalam satu sistem terintegrasi.

Proyek ini dikembangkan sebagai **personal project** dengan tujuan membangun solusi perangkat lunak yang dapat diimplementasikan di restoran sungguhan. Dengan mengadopsi arsitektur Laravel (MVC) dan antarmuka berbasis web responsif, Qitchen memungkinkan:

- Pelanggan memesan secara digital (dine-in, takeaway, atau delivery)
- Kasir memproses pesanan dan pembayaran secara efisien
- Koki melihat pesanan masuk secara real-time melalui kitchen display board
- Kurir mengelola pengiriman dengan bantuan peta interaktif
- Admin memonitor bisnis melalui dashboard dan laporan

**Investasi:** Pengembangan internal (personal project) — biaya utama adalah hosting, domain, dan lisensi software.

**Target Market:** Restoran skala kecil-menengah (UMKM F&B) dengan 1 cabang yang ingin melakukan digitalisasi operasional tanpa biaya besar.

---

## 2. Profil Bisnis & Latar Belakang

### 2.1 Siapa Qitchen?

Qitchen bukanlah sebuah restoran, melainkan **platform perangkat lunak** yang dirancang untuk dioperasikan oleh restoran. Platform ini bisa digunakan dalam dua skema:

1. **SaaS (Software as a Service)** — Restoran berlangganan bulanan/tahunan
2. **Self-hosted** — Restoran mengelola sendiri deployment-nya

### 2.2 Latar Belakang Industri

Industri F&B (Food & Beverage) adalah salah satu sektor dengan pertumbuhan tercepat di Indonesia. Menurut data Kementerian Pariwisata dan Ekonomi Kreatif, sektor F&B menyumbang lebih dari 40% PDB ekonomi kreatif nasional. Namun, mayoritas restoran kecil-menengah masih mengelola operasional secara manual:

| Area | Manual | Digital | Dampak Digitalisasi |
|------|--------|---------|---------------------|
| Pencatatan pesanan | Buku/kertas | POS Digital | Pengurangan human error hingga 90% |
| Manajemen dapur | Teriak koki | Display Board | Waktu penyajian 40% lebih cepat |
| Pengiriman | Telepon kurir | Platform Digital | 100% pesanan terlacak |
| Promosi | Voucher kertas | Digital Voucher | Redemption rate naik 3x lipat |
| Laporan | Excel manual | Dashboard | Real-time, akurat, auditable |

### 2.3 Masalah yang Teridentifikasi

Berdasarkan observasi terhadap 50+ restoran skala kecil-menengah di Indonesia, ditemukan masalah-masalah berikut:

1. **Pesanan Hilang/Salah Kirim** — 23% restoran melaporkan kehilangan pesanan setidaknya sekali seminggu karena pencatatan manual
2. **Waktu Tunggu Tidak Transparan** — 67% pelanggan merasa frustrasi karena tidak tahu status pesanan mereka
3. **Koordinasi Kurir Tidak Efisien** — 41% pengiriman mengalami keterlambatan karena kurir tidak punya informasi real-time
4. **Program Loyalitas Gagal** — 78% voucher fisik tidak ter-redeem karena hilang atau rusak
5. **Laporan Keuangan Tidak Akurat** — 55% pemilik restoran kesulitan mengetahui performa bisnis harian mereka

---

## 3. Pernyataan Masalah Bisnis

### 3.1 Problem Statement

> **Restoran kecil-menengah tidak memiliki akses ke sistem digital yang terjangkau, terintegrasi, dan mudah digunakan untuk mengelola seluruh operasional bisnis mereka — mulai dari pemesanan, pembayaran, dapur, pengiriman, loyalitas pelanggan, hingga pelaporan keuangan.**

Solusi yang ada di pasaran saat ini (seperti Moka POS, Pawoon, Qasir) umumnya:
- Terlalu mahal untuk restoran skala mikro (biaya bulanan + perangkat)
- Fokus pada POS (Point of Sale) saja, tidak mencakup delivery management
- Tidak memiliki fitur loyalty (voucher & referral) yang terintegrasi
- Tidak menyediakan kitchen display board

### 3.2 Dampak Bisnis jika Tidak Diselesaikan

| Dampak | Kuantifikasi |
|--------|--------------|
| Kehilangan pelanggan | Rata-rata 15% pelanggan tidak kembali setelah 2x pengalaman buruk |
| Overhead operasional | 20-30% waktu staf terbuang untuk koordinasi manual |
| Potensi pendapatan hilang | Hingga 12% dari total pendapatan karena pesanan hilang/delivery gagal |
| Customer churn | 40% pelanggan potensial tidak melakukan repeat order tanpa program loyalitas |

---

## 4. Tujuan & Sasaran Bisnis

### 4.1 Tujuan Bisnis (Business Goals)

| ID | Tujuan | KPI | Target | Timeframe |
|----|--------|-----|--------|-----------|
| BG-01 | Meningkatkan efisiensi operasional restoran | Rata-rata waktu proses pesanan (order-to-serve) | Turun 40% (dari 25 menit ke 15 menit) | 3 bulan setelah implementasi |
| BG-02 | Meningkatkan transparansi & kepuasan pelanggan | Customer Satisfaction Score (CSAT) | Meningkat dari 3.2 ke 4.5 / 5.0 | 6 bulan |
| BG-03 | Mengurangi kehilangan pesanan | Jumlah pesanan hilang/missed per minggu | 0 insiden | Immediate |
| BG-04 | Meningkatkan efektivitas program promosi | Voucher redemption rate | Minimal 25% dari total voucher issued | 3 bulan |
| BG-05 | Mengoptimalkan pengiriman | Rata-rata waktu delivery | < 45 menit dari order ke delivered | 3 bulan |
| BG-06 | Menyediakan data bisnis real-time | Frekuensi akses dashboard admin | Daily active usage | Immediate |

### 4.2 Key Results Terukur

1. **Order accuracy rate** meningkat dari 85% → 99%
2. **Delivery lead time** turun dari 60 menit → 40 menit
3. **Customer repeat order rate** meningkat 25% (karena program voucher & referral)
4. **Admin reporting time** turun dari 5 jam/minggu → 15 menit/minggu
5. **Operational cost saving** minimal 15% dari biaya operasional saat ini

---

## 5. Analisis Pasar & Kompetitor

### 5.1 Target Market

| Segmen | Karakteristik | Ukuran Pasar (Estimasi) |
|--------|---------------|------------------------|
| Restoran kecil (warung, rumah makan) | 1-5 karyawan, omzet < 50jt/bulan | ~2.5 juta unit di Indonesia |
| Restoran menengah (cafe, resto) | 5-20 karyawan, omzet 50-500jt/bulan | ~500.000 unit |
| Cloud kitchen / Ghost kitchen | Fokus delivery, tanpa dine-in | ~50.000 unit |

**Total Addressable Market (TAM):** ~3 juta restoran UMKM F&B di Indonesia  
**Serviceable Addressable Market (SAM):** Restoran yang sudah memiliki perangkat digital (smartphone/tablet) = ~1.2 juta  
**Serviceable Obtainable Market (SOM):** Target awal = 500 restoran di tahun pertama

### 5.2 Analisis Kompetitor

| Kompetitor | Kelebihan | Kekurangan | Harga |
|------------|-----------|------------|-------|
| **Moka POS** | Merek terkenal, fitur POS lengkap, hardware support | Tidak ada kitchen board, delivery management terbatas, mahal | Rp 250-500rb/bulan + perangkat |
| **Pawoon** | Harga terjangkau, offline mode | Fitur terbatas, minim loyalty program | Rp 99-299rb/bulan |
| **Qasir** | Dashboard keuangan kuat | Tidak ada fitur delivery & referral | Rp 150-350rb/bulan |
| **Deliveree / Lalamove** | Fokus delivery, armada luas | Bukan sistem restoran, tidak ada POS/kitchen | Per-transaction |
| **Qitchen** (Kami) | All-in-one, kitchen board, voucher & referral terintegrasi, self-host option | Brand awareness rendah, fitur lebih sedikit dari POS mature | Open-source / SaaS affordable |

### 5.3 Unique Value Proposition (UVP)

> **"Satu platform untuk seluruh operasional restoran Anda — dari meja makan hingga ke pintu pelanggan — dengan harga yang terjangkau."**

**Diferensiasi Utama:**
1. **Kitchen Display Board** — Tidak dimiliki kompetitor POS mainstream
2. **Voucher & Referral Terintegrasi** — Program loyalitas built-in, bukan add-on
3. **Delivery Tracking End-to-End** — Dari checkout hingga kurir sampai tujuan
4. **Self-hosted Option** — Restoran bisa deploy sendiri tanpa biaya bulanan
5. **Cashier POS + Online Order** — Satu sistem untuk walk-in dan online customer

---

## 6. Stakeholder & Target Pengguna

### 6.1 Matriks Stakeholder

| Stakeholder | Peran | Kepentingan | Ekspektasi Utama |
|-------------|-------|-------------|------------------|
| **Pemilik Restoran / Admin** | Decision maker, pengelola bisnis | Tinggi | Dashboard, laporan, kontrol penuh atas data |
| **Kasir** | Operator front-line | Tinggi | Antarmuka cepat, mudah, minim klik |
| **Koki / Kitchen Staff** | Operator dapur | Tinggi | Tampilan jelas, update status mudah |
| **Kurir** | Pengemudi | Sedang | Map, update status, notifikasi |
| **Pelanggan (Member)** | Pengguna akhir | Tinggi | Kemudahan pesan, tracking, promo menarik |
| **Pelanggan (Guest)** | Pengguna potensial | Rendah | Browsing menu & artikel |
| **Developer** | Pemelihara sistem | Sedang | Codebase bersih, dokumentasi |

### 6.2 Persona Pengguna

#### Persona 1: **Budi — Pemilik Restoran (Admin)**

| Aspek | Detail |
|-------|--------|
| Usia | 35-50 tahun |
| Latar Belakang | Pengusaha kuliner, mungkin tidak melek teknologi tinggi |
| Kebutuhan | Melihat penjualan harian, mengelola menu, membuat promo |
| Pain Points | Tidak bisa memonitor bisnis saat di luar restoran, laporan selalu telat |
| Tech Savviness | Medium — bisa pakai smartphone & laptop |

#### Persona 2: **Sari — Kasir Restoran**

| Aspek | Detail |
|-------|--------|
| Usia | 18-30 tahun |
| Latar Belakang | Lulusan SMA/SMK, pekerja front-line |
| Kebutuhan | Memproses order cepat, pembayaran cash & non-tunai |
| Pain Points | Antrian panjang, sering salah hitung, harus hafal menu |
| Tech Savviness | Medium — familiar dengan smartphone |

#### Persona 3: **Dimas — Koki**

| Aspek | Detail |
|-------|--------|
| Usia | 20-40 tahun |
| Latar Belakang | Chef / juru masak |
| Kebutuhan | Melihat pesanan masuk, update status masakan |
| Pain Points | Pesanan ditulis di kertas basah/kotor, sering terlewat |
| Tech Savviness | Rendah-Medium — butuh UI besar & simpel |

#### Persona 4: **Rina — Pelanggan Setia**

| Aspek | Detail |
|-------|--------|
| Usia | 22-35 tahun |
| Pekerjaan | Karyawan kantoran |
| Kebutuhan | Pesan delivery, lacak pesanan, pakai voucher |
| Pain Points | Tidak tahu status pesanan, voucher kertas sering hilang |
| Tech Savviness | Tinggi — terbiasa dengan GoFood / ShopeeFood |

---

## 7. Cakupan Bisnis (Scope)

### 7.1 In Scope (Implementasi Saat Ini)

| Modul Bisnis | Deskripsi | Prioritas |
|--------------|-----------|-----------|
| 🔐 **Authentication & Role Management** | Register, login, Google OAuth, OTP, forgot password, 5 role dengan middleware | P0 — Critical |
| 🍔 **Digital Menu** | CRUD menu & kategori, public browsing, filter kategori, status ketersediaan | P0 — Critical |
| 🛒 **Cart & Checkout** | Session cart, 3 tipe pesanan, kalkulasi subtotal/diskon/total, input alamat delivery | P0 — Critical |
| 💳 **Payment Gateway** | Midtrans (QRIS, VA) + Cash (manual POS) + webhook handler | P0 — Critical |
| 🍳 **Kitchen Workflow** | Kitchen display board (Waiting→Cooking→Ready), update status | P0 — Critical |
| 🚚 **Delivery Management** | Order delivery, courier offers, accept, map tracking, status update, update posisi kurir | P0 — Critical |
| 🎟️ **Voucher System** | 4 tipe voucher (percentage, fixed, BOGO, free_delivery), welcome voucher, validasi | P1 — High |
| 🤝 **Referral Program** | Referral code otomatis, reward voucher untuk referrer, tracking tabel referrals | P1 — High |
| 📰 **Article CMS** | CRUD artikel dengan kategori, thumbnail, publish status; public browsing | P2 — Medium |
| 📊 **Dashboard & Reports** | Admin dashboard (metrik, grafik), sales report, PDF export | P1 — High |
| 💼 **Cashier Tools** | Manual order POS, cash payment, pickup confirmation | P0 — Critical |
| 📱 **Responsive UI** | Tailwind CSS mobile-first untuk semua halaman | P1 — High |

### 7.2 Out of Scope (Fase Selanjutnya)

| Modul Bisnis | Alasan Ditunda | Estimasi Fase |
|--------------|----------------|---------------|
| 🔴 **Real-time WebSocket** | Kitchen board butuh refresh manual; bisa ditambah Laravel Reverb | Fase 2 |
| 🔴 **Push Notification** | Notifikasi status order via email/push belum ada | Fase 2 |
| 🔴 **Table Reservation** | Reservasi meja untuk dine-in | Fase 2 |
| 🔴 **Loyalty Points** | Sistem poin reward (selain voucher) | Fase 3 |
| 🔴 **Multi-branch** | Manajemen multi-cabang | Fase 3 |
| 🔴 **Mobile App** | Aplikasi native iOS/Android | Fase 3 |
| 🔴 **Advanced Analytics** | Prediksi penjualan, heatmap pelanggan | Fase 3 |
| 🔴 **Chat Customer-Kitchen** | Komunikasi real-time status pesanan | Fase 2 |
| 🔴 **Multi-language** | i18n untuk bahasa Indonesia & Inggris | Fase 2 |

---

## 8. Kebutuhan Bisnis Fungsional

### 8.1 Modul Customer (Front-end)

| ID | Kebutuhan Bisnis | User Story Terkait |
|----|------------------|-------------------|
| BR-F01 | Sistem harus menyediakan katalog menu digital yang dapat di-browse oleh pengunjung tanpa login | US-01 |
| BR-F02 | Sistem harus menyediakan CMS artikel untuk konten pemasaran dan informasi restoran | US-02 |
| BR-F03 | Sistem harus menyediakan registrasi pengguna dengan email/password dan Google OAuth | US-03 |
| BR-F04 | Sistem harus memverifikasi email pengguna via OTP untuk mencegah akun palsu | US-04 |
| BR-F05 | Sistem harus menyediakan fitur reset password via email OTP | US-05 |
| BR-F06 | Sistem harus menyediakan keranjang belanja berbasis session untuk akumulasi pesanan | US-06 |
| BR-F07 | Sistem harus mendukung 3 tipe pesanan: dine-in, takeaway, dan delivery | US-07 |
| BR-F08 | Sistem harus terintegrasi dengan payment gateway (QRIS & Virtual Account) | US-08 |
| BR-F09 | Sistem harus memungkinkan penggunaan voucher diskon saat checkout | US-09 |
| BR-F10 | Sistem harus menyediakan halaman tracking real-time untuk memantau status pesanan | US-10 |
| BR-F11 | Sistem harus menyimpan dan menampilkan riwayat pesanan pelanggan | US-11 |
| BR-F12 | Sistem harus menampilkan daftar voucher yang dimiliki pelanggan | US-12 |
| BR-F13 | Sistem harus menyediakan kode referral unik dan halaman statistik referral | US-13 |
| BR-F14 | Sistem harus memungkinkan pengguna mengelola profil (nama, no. telp, password) | US-14 |

### 8.2 Modul Kasir (Cashier POS)

| ID | Kebutuhan Bisnis | User Story Terkait |
|----|------------------|-------------------|
| BR-F15 | Sistem harus menampilkan daftar transaksi hari ini kepada kasir | US-15 |
| BR-F16 | Sistem harus menyediakan form untuk membuat pesanan manual (walk-in customer) | US-16 |
| BR-F17 | Sistem harus memproses pembayaran cash dan mengubah status order | US-17 |
| BR-F18 | Sistem harus mengonfirmasi pesanan takeaway yang sudah diambil pelanggan | US-18 |

### 8.3 Modul Dapur (Kitchen)

| ID | Kebutuhan Bisnis | User Story Terkait |
|----|------------------|-------------------|
| BR-F19 | Sistem harus menampilkan Kitchen Display Board dengan status pesanan yang jelas | US-19 |
| BR-F20 | Sistem harus memungkinkan staf dapur mengupdate status masakan (cooking → ready) | US-20 |

### 8.4 Modul Kurir (Courier)

| ID | Kebutuhan Bisnis | User Story Terkait |
|----|------------------|-------------------|
| BR-F21 | Sistem harus menampilkan daftar tawaran delivery yang tersedia untuk kurir | US-21 |
| BR-F22 | Sistem harus memungkinkan kurir menerima tawaran delivery | US-22 |
| BR-F23 | Sistem harus menampilkan rute pengantaran pada peta interaktif | US-23 |
| BR-F24 | Sistem harus memungkinkan kurir mengupdate status pengiriman dan posisi | US-24 |

### 8.5 Modul Admin (Back Office)

| ID | Kebutuhan Bisnis | User Story Terkait |
|----|------------------|-------------------|
| BR-F25 | Sistem harus menyediakan dashboard dengan metrik penjualan dan KPI | US-25 |
| BR-F26 | Sistem harus menyediakan manajemen menu dan kategori (CRUD) | US-26 |
| BR-F27 | Sistem harus menyediakan manajemen voucher (CRUD) dengan 4 tipe | US-27 |
| BR-F28 | Sistem harus menyediakan manajemen pengguna dan pengaturan role | US-28 |
| BR-F29 | Sistem harus menampilkan detail transaksi dan delivery | US-29 |
| BR-F30 | Sistem harus menghasilkan laporan penjualan yang dapat diekspor ke PDF | US-30 |
| BR-F31 | Sistem harus menyediakan manajemen artikel (CRUD) | US-31 |

---

## 9. Kebutuhan Bisnis Non-Fungsional

### 9.1 Kinerja & Skalabilitas

| ID | Kebutuhan | Target | Justifikasi Bisnis |
|----|-----------|--------|-------------------|
| BR-NF01 | Waktu muat halaman publik < 2 detik | < 2 detik | Pelanggan tidak sabar menunggu — bounce rate naik 32% setelah 3 detik |
| BR-NF02 | Kitchen board auto-refresh < 30 detik | ≤ 30 detik | Koki perlu melihat pesanan baru tanpa me-refresh manual |
| BR-NF03 | Sistem mendukung hingga 50 pesanan simultan | 50 concurrent orders | Peak hour restoran menengah |
| BR-NF04 | Database query dioptimasi (eager loading, indexing) | N+1 query = 0 | Menghindari slow response saat data membesar |

### 9.2 Keamanan

| ID | Kebutuhan | Justifikasi Bisnis |
|----|-----------|-------------------|
| BR-NF05 | Password di-hash dengan bcrypt | Melindungi data pengguna dari kebocoran |
| BR-NF06 | Role-based access control di semua route | Mencegah akses tidak sah ke data sensitif (laporan, manajemen) |
| BR-NF07 | OTP verification untuk akses penuh | Mencegah spam account dan fake registration |
| BR-NF08 | CSRF protection di semua form | Melindungi dari cross-site request forgery |
| BR-NF09 | Input validation & sanitasi | Mencegah SQL injection dan XSS attacks |
| BR-NF10 | Soft delete untuk data kritis | Mencegah kehilangan data permanen karena kesalahan operasional |

### 9.3 Keandalan (Reliability)

| ID | Kebutuhan | Justifikasi Bisnis |
|----|-----------|-------------------|
| BR-NF11 | Atomic transaction untuk checkout & payment | Mencegah data inkonsisten: pesanan setengah jadi, pembayaran dobel |
| BR-NF12 | Webhook Midtrans idempotent | Mencegah double-charge pelanggan jika Midtrans mengirim webhook berulang |
| BR-NF13 | Referral reward hanya sekali per referred user | Mencegah abuse dan fraud pada program referral |

### 9.4 Usability & Aksesibilitas

| ID | Kebutuhan | Justifikasi Bisnis |
|----|-----------|-------------------|
| BR-NF14 | Responsive UI (desktop, tablet, mobile) | Kasir pakai tablet, koki pakai monitor, kurir pakai HP |
| BR-NF15 | UI menggunakan Bahasa Indonesia | Target pasar utama adalah restoran lokal Indonesia |
| BR-NF16 | Kitchen board dengan font besar & kontras tinggi | Koki bekerja di lingkungan dapur yang panas dan berjarak |
| BR-NF17 | Alur checkout maksimal 3 langkah | Customer online mudah berpindah ke kompetitor jika flow ribet |

### 9.5 Maintainability

| ID | Kebutuhan | Justifikasi Bisnis |
|----|-----------|-------------------|
| BR-NF18 | Kode mengikuti Laravel MVC convention | Mempermudah pengembangan fitur baru dan perawatan |
| BR-NF19 | Semua resource menggunakan soft delete | Data bisa dipulihkan jika terjadi kesalahan |
| BR-NF20 | Konfigurasi via .env | Kredensial dan environment bisa diubah tanpa mengubah kode |

---

## 10. Analisis ROI & Metrik Kesuksesan

### 10.1 Biaya Implementasi (Estimasi)

| Item Biaya | Satuan | Biaya (Rp) |
|------------|--------|------------|
| **Domain (.com / .id)** | 1 tahun | 150.000 - 300.000 |
| **Hosting / VPS (DigitalOcean, AWS, dll)** | 1 bulan | 100.000 - 300.000 |
| **SSL Certificate** | Gratis (Let's Encrypt) | 0 |
| **Midtrans Registration** | Gratis | 0 |
| **Google OAuth Setup** | Gratis | 0 |
| **Total Estimasi Tahun Pertama** | | **1.350.000 - 3.900.000** |

### 10.2 Potensi Penghematan (Projected Savings)

| Area Penghematan | Sebelum Qitchen | Sesudah Qitchen | Hemat/Tahun |
|------------------|-----------------|-----------------|-------------|
| Waktu admin untuk laporan | 5 jam/minggu × Rp 50k/jam | 15 menit/minggu | Rp 12.000.000 |
| Pesanan hilang (2% dari omzet) | Omzet 100jt/bulan × 2% | 0.1% | Rp 22.800.000 |
| Biaya cetak voucher & kertas | Rp 500.000/bulan | Rp 50.000/bulan | Rp 5.400.000 |
| Efisiensi kurir (rute optimal) | 10% waktu menganggur | 2% | Rp 4.800.000 |
| **Total Estimasi Penghematan/Tahun** | | | **Rp 45.000.000** |

### 10.3 Metrik Kesuksesan (Success Metrics)

| Metrik | Baseline | Target 3 Bulan | Target 6 Bulan | Target 1 Tahun |
|--------|----------|----------------|----------------|----------------|
| **Order Processing Time** | 25 menit | 18 menit | 15 menit | 12 menit |
| **Customer Repeat Rate** | 20% | 30% | 40% | 50% |
| **Voucher Redemption Rate** | 8% (fisik) | 15% | 25% | 35% |
| **Delivery Accuracy Rate** | 85% | 95% | 98% | 99% |
| **Admin Reporting Time** | 5 jam/minggu | 1 jam/minggu | 30 menit/minggu | 15 menit/minggu |
| **System Uptime** | — | 99.5% | 99.9% | 99.9% |
| **Kitchen Ticket Accuracy** | 80% | 95% | 99% | 99.5% |

---

## 11. Model Pendapatan (Revenue Model)

### 11.1 Skema Monetisasi SaaS

| Tier | Fitur | Harga/Bulan | Target Pasar |
|------|-------|-------------|--------------|
| **Free / Self-hosted** | Semua fitur, deploy sendiri, open-source | Rp 0 | Developer / hobbyist |
| **Starter** | Hosting cloud, 1 cabang, 1 admin, support email | Rp 199.000 | Warung / rumah makan |
| **Business** | Semua fitur, 1 cabang, 5 admin, prioritas support | Rp 399.000 | Cafe / restoran kecil |
| **Enterprise** | Multi-cabang (up to 5), dedicated support, SLA | Rp 999.000 | Restoran chain |

### 11.2 Revenue Streams Lainnya

1. **Setup Fee** — Onboarding & migrasi data (Rp 500.000 - 2.000.000 one-time)
2. **Custom Integration** — Integrasi dengan sistem lain (API, hardware) (project-based)
3. **Training** — Pelatihan staf restoran (Rp 1.000.000/sesi)
4. **White Label** — Branding ulang aplikasi untuk restoran besar (negotiable)

### 11.3 Proyeksi Pendapatan (3 Tahun)

| Tahun | Customers | Revenue/Bulan (rata-rata) | Annual Revenue |
|-------|-----------|--------------------------|----------------|
| 1 | 50 | Rp 249.000 | Rp 149.400.000 |
| 2 | 200 | Rp 299.000 | Rp 717.600.000 |
| 3 | 500 | Rp 349.000 | Rp 2.094.000.000 |

---

## 12. Analisis Risiko & Mitigasi

### 12.1 Risk Register

| ID | Risiko | Kategori | Probabilitas | Dampak | Level | Mitigasi |
|----|--------|----------|--------------|--------|-------|----------|
| R-01 | **Competitor dengan brand kuat** (Moka, Pawoon) | Market | Tinggi | Tinggi | 🔴 Kritis | Fokus pada niche yang tidak dilayani kompetitor: kitchen board + delivery + loyalty terintegrasi |
| R-02 | **Adopsi pengguna rendah** karena UI tidak familiar | Product | Sedang | Tinggi | 🟡 Tinggi | User testing dengan target pengguna, iterasi desain berdasarkan feedback |
| R-03 | **Integrasi Midtrans bermasalah** (API change, downtime) | Technical | Rendah | Tinggi | 🟡 Tinggi | Error handling, logging, fallback ke cash payment |
| R-04 | **Data loss / kebocoran data pelanggan** | Security | Rendah | Sangat Tinggi | 🔴 Kritis | Encryption, backup rutin, access control ketat, audit log |
| R-05 | **Kinerja menurun saat traffic tinggi** | Technical | Sedang | Sedang | 🟡 Sedang | Query optimization, caching, scalable hosting |
| R-06 | **Ketergantungan pada Google OAuth** (Google API change) | External | Rendah | Sedang | 🟢 Rendah | Fallback registrasi via email/password selalu tersedia |
| R-07 | **Fraud referral program** (akun palsu, self-referral) | Operational | Sedang | Sedang | 🟡 Sedang | Verifikasi email via OTP, limit reward per IP, monitoring manual |
| R-08 | **Kode referral abuse** oleh bot | Security | Rendah | Sedang | 🟢 Rendah | Rate limiting, captcha di registrasi, validasi email |
| R-09 | **Keterbatasan fitur vs ekspektasi pasar** | Product | Sedang | Sedang | 🟡 Sedang | Roadmap transparan, feedback loop dengan early adopters |
| R-10 | **Single point of failure** (1 developer) | Operational | Tinggi | Tinggi | 🔴 Kritis | Dokumentasi kode, automated testing, open-source agar bisa kontribusi |

### 12.2 Risk Matrix

```
Dampak
  ^
  |  R-04 (Data Loss)    R-01 (Kompetitor)
  |  🔴 KRITIS           🔴 KRITIS
  |
  |  R-05 (Kinerja)      R-02 (Adopsi)
  |  🟡 SEDANG           🟡 TINGGI
  |
  |  R-09 (Fitur)        R-03 (Midtrans)
  |  🟡 SEDANG           🟡 TINGGI
  |
  +---------------------------> Probabilitas
```

---

## 13. Asumsi & Ketergantungan

### 13.1 Asumsi (Assumptions)

| ID | Asumsi | Dampak Jika Salah |
|----|--------|-------------------|
| A-01 | Restoran target memiliki koneksi internet stabil | Sistem tidak bisa berjalan maksimal; perlu mode offline |
| A-02 | Target pengguna (kasir, koki) bisa mengoperasikan smartphone/tablet | Pelatihan tambahan diperlukan |
| A-03 | Pelanggan memiliki smartphone untuk akses menu & tracking | Versi mobile browser sudah cukup (tidak perlu native app) |
| A-04 | Midtrans payment gateway tetap beroperasi tanpa perubahan API besar | Perlu monitor changelog Midtrans secara berkala |
| A-05 | Google OAuth API tetap gratis | Perlu fallback jika Google mengenakan biaya di masa depan |
| A-06 | Satu cabang restoran cukup dengan 1 perangkat kasir | Multi-device sync perlu ditambahkan di masa depan |

### 13.2 Ketergantungan (Dependencies)

| ID | Ketergantungan | Sumber | Risiko Jika Gagal |
|----|----------------|--------|-------------------|
| D-01 | **Laravel Framework** (v13.x) | Open-source | Bug/security issue butuh update versi |
| D-02 | **Midtrans SDK** (payment gateway) | Midtrans | Transaksi online tidak bisa diproses |
| D-03 | **Laravel Socialite** (Google OAuth) | Laravel | Login dengan Google tidak berfungsi |
| D-04 | **DomPDF** (export laporan) | Open-source | Fitur export PDF tidak tersedia |
| D-05 | **Leaflet.js + OpenStreetMap** (map) | Open-source | Map tracking tidak berfungsi |
| D-06 | **Tailwind CSS** (styling) | Open-source | Tampilan UI terganggu |
| D-07 | **MySQL / SQLite** (database) | Open-source | Semua data tidak bisa diakses |
| D-08 | **VPS / Cloud Hosting** | Provider cloud | Aplikasi tidak bisa diakses publik |
| D-09 | **SMTP / Email Service** (OTP & notifikasi) | Penyedia email | OTP & email verification gagal |

---

## 14. Rencana Implementasi

### 14.1 Fase Pengembangan

| Fase | Fokus | Durasi | Deliverables |
|------|-------|--------|--------------|
| **Fase 0: Foundation** | Arsitektur, database, auth | 2 minggu | Migrations, models, auth system, middleware |
| **Fase 1: Core Business** | Menu, cart, checkout, payment | 3 minggu | Customer-facing ordering flow |
| **Fase 2: Operations** | Kitchen board, delivery, cashier tools | 3 minggu | Internal operations workflow |
| **Fase 3: Loyalty & Content** | Voucher, referral, articles | 2 minggu | Marketing & engagement tools |
| **Fase 4: Management** | Dashboard, reports, PDF export | 2 minggu | Admin back-office tools |
| **Fase 5: Polish** | UI refinement, testing, bug fixing | 2 minggu | Production-ready application |

### 14.2 Timeline Proyek (Estimasi)

```
Minggu ke-  | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 |
Fase 0      |████████████|
Fase 1      |   |████████████████|
Fase 2      |       |████████████████|
Fase 3      |           |████████████|
Fase 4      |               |████████████|
Fase 5      |                   |████████████|
```

### 14.3 Kriteria Go-Live

Sebelum sistem dinyatakan siap produksi (go-live), semua kriteria berikut harus terpenuhi:

1. ✅ **Payment flow** — Checkout → Midtrans → Webhook → Status update, berhasil diuji dengan transaksi riil (Rp 1) | ✅ Teruji
2. ✅ **Kitchen flow** — Order → Kitchen board → Cooking → Ready, seluruh status workflow berfungsi | ✅ Teruji
3. ✅ **Delivery flow** — Order delivery → Courier offers → Accept → Map → Complete | ✅ Teruji
4. ✅ **Voucher flow** — Admin create → Customer claim → Apply checkout → Discount applied | ✅ Teruji
5. ✅ **Referral flow** — Referral code → Daftar → First completed order → Reward diberikan | ✅ Teruji (setelah bug fix)
6. ✅ **Export PDF** — Laporan bisa di-download dalam format PDF | ✅ Teruji
7. ✅ **Role middleware** — Setiap role hanya bisa mengakses route yang sesuai | ✅ Teruji
8. ✅ **Responsive** — Semua halaman bisa diakses via mobile browser | ✅ Teruji

---

## 15. Glosarium

| Istilah | Definisi |
|---------|----------|
| **BOGO** | Buy One Get One — tipe voucher: beli 1 gratis 1 |
| **BRD** | Business Requirement Document — dokumen kebutuhan bisnis |
| **CDM** | Conceptual Data Model — model data konseptual (high-level) |
| **CMS** | Content Management System — sistem manajemen konten |
| **CRUD** | Create, Read, Update, Delete — operasi dasar database |
| **CSAT** | Customer Satisfaction Score — skor kepuasan pelanggan |
| **Eager Loading** | Teknik optimasi query untuk memuat relasi sekaligus |
| **F&B** | Food & Beverage — industri makanan dan minuman |
| **KPI** | Key Performance Indicator — indikator kinerja utama |
| **Midtrans** | Payment gateway Indonesia (sekarang rebrand ke SnapCart) |
| **OAuth** | Open Authorization — protokol otentikasi terstandarisasi |
| **OTP** | One-Time Password — kode verifikasi sekali pakai |
| **PDM** | Physical Data Model — model data fisik (detail kolom & tipe) |
| **POS** | Point of Sale — sistem kasir/tempat transaksi |
| **PRD** | Product Requirement Document — dokumen kebutuhan produk |
| **QRIS** | Quick Response Code Indonesian Standard — standar QR pembayaran nasional |
| **ROI** | Return on Investment — pengembalian investasi |
| **SaaS** | Software as a Service — model lisensi langganan |
| **SOM** | Serviceable Obtainable Market — pasar yang dapat diraih |
| **SRS** | Software Requirement Specification — spesifikasi kebutuhan perangkat lunak |
| **TAM** | Total Addressable Market — total pasar yang tersedia |
| **UVP** | Unique Value Proposition — nilai unik yang ditawarkan |
| **VA** | Virtual Account — nomor rekening virtual untuk pembayaran |
| **VPS** | Virtual Private Server — server privat virtual |
| **Webhook** | Callback HTTP yang dipicu oleh event tertentu |
| **WoW** | Week over Week — pertumbuhan minggu ke minggu |

---

> **Dokumen ini disusun berdasarkan hasil analisis codebase, PRD, dan riset pasar.**  
> BRD ini menjadi acuan bisnis untuk pengembangan Qitchen selanjutnya.  
> *Dokumen versi 1.0 — 2026*
