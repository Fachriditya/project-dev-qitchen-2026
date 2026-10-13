<p align="center">
  <img src="https://img.shields.io/badge/Status-Active-brightgreen?style=flat-square"/>
  <img src="https://img.shields.io/badge/Version-1.0-blue?style=flat-square"/>
  <img src="https://img.shields.io/badge/Laravel-13.x-F55247?style=flat-square&logo=laravel"/>
  <img src="https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php"/>
  <img src="https://img.shields.io/badge/MySQL-4479A1?style=flat-square&logo=mysql"/>
  <img src="https://img.shields.io/badge/TailwindCSS-06B6D4?style=flat-square&logo=tailwindcss"/>
</p>

<h1 align="center">🍽️ Qitchen</h1>

<p align="center">
  <strong>Sistem Manajemen Restoran Modern — All-in-One Digital Platform</strong>
  <br/>
  <i>Personal Project • 2026</i>
</p>

<p align="center">
  <a href="#-tentang">Tentang</a> •
  <a href="#-fitur-utama">Fitur</a> •
  <a href="#-tech-stack">Tech Stack</a> •
  <a href="#-role-dan-akses">Role</a> •
  <a href="#-struktur-direktori">Struktur</a> •
  <a href="#-panduan-instalasi">Instalasi</a> •
  <a href="#-dokumentasi">Dokumentasi</a>
</p>

---

## 📌 Tentang

**Qitchen** adalah sistem manajemen restoran digital **all-in-one** yang menyatukan seluruh alur operasional restoran — dari pemesanan pelanggan, pembayaran, workflow dapur, pengiriman, hingga program loyalitas — dalam satu platform terintegrasi.

Dibangun dengan **Laravel 13.x**, **Blade**, dan **TailwindCSS**, Qitchen dirancang untuk membantu restoran kecil-menengah melakukan digitalisasi operasional tanpa biaya besar.

---

## ✨ Fitur Utama

| Modul | Fitur |
|-------|-------|
| 👥 **Multi-Role Auth** | Register/Login, Google OAuth, OTP Email, Role-based middleware (5 role) |
| 🍔 **Digital Menu** | CRUD menu & kategori, filter kategori, status ketersediaan |
| 🛒 **Cart & Checkout** | Session cart, 3 tipe pesanan (dine-in / takeaway / delivery), kalkulasi diskon |
| 💳 **Payment Gateway** | Midtrans Snap (QRIS + Virtual Account), Cash via Cashier POS, Webhook auto-status |
| 🍳 **Kitchen Board** | Display board Kanban (Waiting → Cooking → Ready), update status real-time |
| 🚚 **Delivery Management** | Courier offers & accept, Leaflet map tracking, GPS update posisi |
| 🎟️ **Voucher System** | 4 tipe (percentage, fixed, BOGO, free_delivery), welcome voucher otomatis |
| 🤝 **Referral Program** | Kode referral unik, reward voucher untuk referrer, tracking tabel referrals |
| 📰 **Article CMS** | CRUD artikel, thumbnail, publish/draft status |
| 📊 **Dashboard & Report** | Metrik penjualan, grafik harian, WoW growth, ekspor PDF |
| 💼 **Cashier Tools** | POS manual order, cash payment, pickup confirmation |

---

## 🛠️ Tech Stack

| Layer | Teknologi |
|-------|-----------|
| **Backend Framework** | Laravel 13.x (PHP 8.3) |
| **Frontend** | Blade Template Engine + TailwindCSS 3.x |
| **Database** | MySQL (Production) / SQLite (Development) |
| **Payment Gateway** | Midtrans Snap API |
| **Authentication** | Laravel Auth + Google OAuth + OTP Email |
| **Maps** | Leaflet.js + OpenStreetMap |
| **PDF Generator** | Laravel DomPDF (barryvdh/laravel-dompdf) |
| **Dependencies** | Laravel Socialite, Midtrans PHP SDK |

---

## 👥 Role dan Akses

| Role | Hak Akses | Middleware |
|------|-----------|------------|
| 🛡️ **Admin** | Dashboard, CRUD semua resource, laporan | `IsAdmin` |
| 💰 **Cashier** | POS manual order, transaksi, pickup | `IsCashier` |
| 👨‍🍳 **Kitchen** | Kitchen display board, update status masakan | `IsKitchen` |
| 🚴 **Courier** | Offers delivery, map tracking, update status | `IsCourier` |
| 👤 **Customer** | Order online, voucher, referral, riwayat | `auth` middleware |

---

## 📂 Struktur Direktori

```
qitchen-2026/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/        # 9 controller (Dashboard, Menu, Category, User, Voucher, dll.)
│   │   │   ├── Api/          # MidtransWebhookController
│   │   │   ├── Auth/         # Registration, Login, OTP, Google, ForgotPassword
│   │   │   ├── Cashier/      # ManualOrder, Pickup, Transaction
│   │   │   ├── Courier/      # DeliveryOfferController
│   │   │   ├── Customer/     # Profile, Order, Voucher, Referral
│   │   │   ├── Kitchen/      # KitchenController
│   │   │   └── Public/       # Home, Menu, Cart, Checkout, Tracking, Article
│   │   ├── Middleware/       # IsAdmin, IsCashier, IsKitchen, IsCourier, IsCustomer
│   │   └── Requests/
│   ├── Models/               # 11 Model: User, Menu, Category, Order, OrderItem, Payment,
│   │                         #           Delivery, Voucher, UserVoucher, Referral, Article
│   └── Providers/
├── bootstrap/
├── config/                   # midtrans.php, app.php
├── database/
│   ├── migrations/           # 11 file migrasi (+ 3 default Laravel)
│   └── seeders/              # UserSeeder, CategorySeeder, MenuSeeder, VoucherSeeder, ArticleSeeder
├── docs/                     # Dokumentasi lengkap (7 file + template)
├── resources/views/          # Template Blade (7 subdirektori per role)
├── routes/
│   ├── web.php               # ~100+ route definitions
│   └── auth.php              # Breeze auth routes
├── storage/
├── tests/
├── .env.example
├── .gitignore
├── composer.json
├── package.json
├── tailwind.config.js
├── vite.config.js
└── README.md
```

---

## 🚀 Panduan Instalasi

### Prasyarat

- PHP 8.3+
- Composer 2.x
- Node.js 20+
- MySQL / SQLite

### Langkah Instalasi

```bash
# 1. Clone repository
gh repo clone Fachriditya/project-dev-qitchen-2026
cd qitchen-2026

# 2. Install PHP dependencies
composer install

# 3. Setup environment
cp .env.example .env
php artisan key:generate

# 4. Konfigurasi .env (sesuaikan dengan kebutuhan)
#    - Database: SQLite (default) atau MySQL
#    - Midtrans: isi MIDTRANS_SERVER_KEY dan MIDTRANS_CLIENT_KEY
#    - Google OAuth: isi GOOGLE_CLIENT_ID dan GOOGLE_CLIENT_SECRET
#    - Mail: isi MAIL_* untuk pengiriman OTP

# 5. Migrate dan seed database
php artisan migrate --seed

# 6. Install frontend dependencies
npm install && npm run build

# 7. Jalankan aplikasi
composer run dev
```

### Akun Default (Seeder)

| Role | Email | Password |
|------|-------|----------|
| Admin | admin@qitchen.test | password |
| Cashier | cashier@qitchen.test | password |
| Kitchen | kitchen@qitchen.test | password |
| Courier | courier@qitchen.test | password |
| Customer | customer@qitchen.test | password |

---

## 📚 Dokumentasi

Seluruh dokumentasi proyek tersedia di direktori [`docs/`](./docs/):

| Dokumen | File | Deskripsi |
|---------|------|-----------|
| **PRD** | [`PRD-Qitchen.md`](./docs/PRD-Qitchen.md) | Product Requirement Document |
| **BRD** | [`BRD-Qitchen.md`](./docs/BRD-Qitchen.md) | Business Requirement Document |
| **SRS** | [`SRS-Qitchen.md`](./docs/SRS-Qitchen.md) | Software Requirement Specification |
| **ADR** | [`ADR-Qitchen.md`](./docs/ADR-Qitchen.md) | Architectural Decision Records |
| **THREAT** | [`THREAT-Qitchen.md`](./docs/THREAT-Qitchen.md) | Threat Model & Security |
| **API** | [`API-Qitchen.md`](./docs/API-Qitchen.md) | API Specification |
| **DEVLOG** | [`DEVLOG-Qitchen.md`](./docs/DEVLOG-Qitchen.md) | Development Log |

### Template (untuk project lain)

Template dokumentasi siap pakai tersedia di [`docs/template/`](./docs/template/):

- [`PRD-Template.md`](./docs/template/PRD-Template.md)
- [`BRD-Template.md`](./docs/template/BRD-Template.md)
- [`SRS-Template.md`](./docs/template/SRS-Template.md)
- [`ADR-Template.md`](./docs/template/ADR-Template.md)
- [`THREAT-Template.md`](./docs/template/THREAT-Template.md)
- [`API-Template.md`](./docs/template/API-Template.md)
- [`DEVLOG-Template.md`](./docs/template/DEVLOG-Template.md)

### Diagram & Desain

| File | Deskripsi |
|------|-----------|
| `docs/Qitchen-CDM.png` | Conceptual Data Model |
| `docs/Qitchen-PDM.png` | Physical Data Model |
| `docs/Qitchen-UseCase.png` | Use Case Diagram |
| `docs/Qitchen-Sitemap.png` | Sitemap |
| `docs/Qitchen-LowFidelity/` | Wireframe low-fidelity |
| `docs/Qitchen-HighFidelity/` | Mockup high-fidelity |
| `docs/Qitchen-UserFlow/` | User flow per role |

---

## 🧪 Pengujian

```bash
# Menjalankan semua test
php artisan test

# Menjalankan test spesifik
php artisan test --filter=CheckoutTest
```

---

## 🔐 Keamanan

- ✅ Password di-hash dengan bcrypt
- ✅ Role-based middleware di semua route
- ✅ OTP verification untuk akses akun
- ✅ CSRF protection (Laravel default)
- ✅ Input validation di semua form
- ✅ Soft delete semua resource
- ✅ Webhook signature verification (SHA-512)
- ✅ HTTPS dipaksakan di produksi
- ℹ️ Lihat [`THREAT-Qitchen.md`](./docs/THREAT-Qitchen.md) untuk detail lengkap

---

## 🧠 Learning Outcomes

Proyek ini dibuat untuk mempraktikkan:

- Full-stack web development dengan **Laravel**
- Database design menggunakan **CDM & PDM**
- **Authentication & role-based authorization**
- **RESTful API** dan integrasi **payment gateway**
- **Responsive UI** menggunakan TailwindCSS
- **Business process implementation** (restaurant workflow)
- **Security best practices** (OWASP Top 10)
- **Software documentation** (PRD, BRD, SRS, ADR)

---

## 📄 Lisensi

Proyek ini adalah **personal project** untuk tujuan pembelajaran dan pengembangan portofolio.

---

<p align="center">
  <sub>Dibuat dengan ❤️ oleh <a href="https://github.com/Fachriditya">Muhammad Fachriditya</a></sub>
  <br/>
  <sub>© 2026 — Qitchen</sub>
</p>
