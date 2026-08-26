# 🍽️ Qitchen — Product Requirement Document (PRD)

> **Versi:** 1.0  
> **Tanggal:** 2026  
> **Status:** In Progress  
> **Project Type:** Personal Project — Full-Stack Web Application

---

## 1. Problem Statement

### 1.1 Latar Belakang

Restoran modern menghadapi kompleksitas operasional yang tinggi: antrian pelanggan, manajemen pesanan dapur, koordinasi pengiriman, dan pengelolaan program loyalitas (voucher & referral) seringkali berjalan secara terpisah. Hal ini menyebabkan:

- **Inefisiensi operasional** — pesanan hilang, salah kirim ke dapur, atau tertukar antar pelanggan.
- **Pengalaman pelanggan buruk** — waktu tunggu lama dan tidak transparan, ketidakjelasan status pesanan.
- **Sulitnya pelacakan delivery** — kurir tidak tahu lokasi pengantaran secara real-time, customer tidak bisa melacak.
- **Manajemen promosi manual** — voucher fisik mudah rusak/hilang, referral tracking tidak akurat.
- **Laporan keuangan tidak terpusat** — pemilik restoran kesulitan memantau performa penjualan harian.

### 1.2 Problem yang Diselesaikan Qitchen

Qitchen menyatukan semua alur operasional restoran ke dalam satu platform digital terintegrasi: dari pemesanan, pembayaran, workflow dapur, pengiriman, hingga manajemen voucher dan referral — sehingga meningkatkan efisiensi, transparansi, dan kepuasan pelanggan.

---

## 2. Goals

| # | Goal | Key Result / Measure |
|---|------|----------------------|
| G1 | Menyediakan sistem pemesanan digital end-to-end | Customer dapat memesan, membayar, dan melacak pesanan dalam satu platform |
| G2 | Mengoptimalkan workflow dapur | Pesanan tampil real-time di kitchen display board; status diperbarui oleh staf dapur |
| G3 | Memudahkan manajemen delivery | Kurir dapat menerima tawaran, melihat rute (map), dan customer dapat melacak status pengiriman |
| G4 | Meningkatkan loyalitas pelanggan | Voucher otomatis untuk customer baru; referral reward untuk pengajak setelah transaksi sukses pertama |
| G5 | Menyediakan dashboard dan laporan | Admin dapat melihat metrik penjualan, mengelola semua resource, dan ekspor laporan PDF |
| G6 | Mendukung pembayaran fleksibel | QRIS, Virtual Account (Midtrans), dan Cash (via cashier) |

---

## 3. Target Users

| Role | Deskripsi | Kebutuhan Utama |
|------|-----------|-----------------|
| **Guest Customer** | Pengunjung yang belum login | Browsing menu, membaca artikel, melihat halaman publik |
| **Member Customer** | Pelanggan terdaftar & login | Memesan online, melacak pesanan, klaim voucher, referral, riwayat transaksi |
| **Cashier** | Kasir restoran | Membuat pesanan manual (dine-in/takeaway), memproses pembayaran cash, konfirmasi pickup |
| **Kitchen Staff** | Staf dapur | Melihat pesanan masuk (display board), update status masakan (cooking → ready) |
| **Courier** | Kurir pengiriman | Menerima tawaran delivery, melihat rute (map), update status pengiriman |
| **Admin** | Pemilik/manajer restoran | Dashboard penjualan, manajemen menu/kategori/user/voucher/artikel, laporan, ekspor PDF |

---

## 4. User Stories

### 4.1 Guest Customer

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-01 | Sebagai guest, saya ingin melihat menu makanan & minuman beserta kategori dan harga | Halaman `/menu` menampilkan semua menu yang tersedia, dikelompokkan per kategori |
| US-02 | Sebagai guest, saya ingin membaca artikel seputar restoran | Halaman `/articles` menampilkan daftar artikel; klik untuk baca detail |
| US-03 | Sebagai guest, saya ingin mendaftar akun dengan email/password atau Google | Form register (name, email, phone, password) dan tombol "Login with Google" berfungsi |
| US-04 | Sebagai guest, saya ingin login dengan OTP (email) untuk verifikasi | Setelah register/login, OTP dikirim ke email; hanya bisa lanjut setelah OTP terverifikasi |
| US-05 | Sebagai guest, saya bisa reset password via OTP email | Flow: request → OTP → reset password baru |

### 4.2 Member Customer

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-06 | Sebagai member, saya ingin menambahkan menu ke cart dan checkout | Cart menyimpan item + quantity; checkout menghitung subtotal, diskon, dan total |
| US-07 | Sebagai member, saya ingin memilih tipe pesanan: dine-in, takeaway, atau delivery | Saat checkout, pilih order type; delivery meminta alamat & koordinat |
| US-08 | Sebagai member, saya ingin membayar via QRIS, Virtual Account, atau Cash | Payment gateway Midtrans terintegrasi; status pembayaran tercatat |
| US-09 | Sebagai member, saya ingin menggunakan voucher saat checkout | Input kode voucher; diskon otomatis terhitung; validasi quota & expired |
| US-10 | Sebagai member, saya ingin melacak status pesanan saya | Halaman `/track/{order_number}` menampilkan status real-time |
| US-11 | Sebagai member, saya ingin melihat riwayat pesanan | Halaman profile → history menampilkan semua order dengan status |
| US-12 | Sebagai member, saya ingin melihat dan menggunakan voucher saya | Halaman profile → vouchers menampilkan daftar voucher yang dimiliki |
| US-13 | Sebagai member, saya ingin mengundang teman via kode referral | Profile → referral menampilkan kode referral dan status reward |
| US-14 | Sebagai member, saya ingin mengelola profil (nama, phone, password) | Halaman profile → edit & update profil |

### 4.3 Cashier

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-15 | Sebagai cashier, saya ingin melihat daftar transaksi hari ini | Dashboard cashier menampilkan semua transaksi dengan filter status |
| US-16 | Sebagai cashier, saya bisa membuat pesanan manual untuk Dine-in / Takeaway | Form manual order: pilih menu, quantity, notes; proses pembayaran cash |
| US-17 | Sebagai cashier, saya bisa mengonfirmasi pembayaran dan order selesai | Tombol "Complete Payment" → status order berubah jadi "paid" |
| US-18 | Sebagai cashier, saya bisa mengonfirmasi pickup pesanan takeaway | Halaman pickup → konfirmasi "sudah diambil" |

### 4.4 Kitchen Staff

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-19 | Sebagai kitchen, saya ingin melihat display board pesanan yang harus dimasak | Board menampilkan order berstatus "paid" dengan detail item, quantity, dan notes |
| US-20 | Sebagai kitchen, saya bisa mengupdate status: cooking → ready | Tombol update status; perubahan langsung tampil di board dan tracking customer |

### 4.5 Courier

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-21 | Sebagai courir, saya ingin melihat daftar tawaran delivery yang tersedia | Halaman `/courier/offers` menampilkan order delivery tanpa courir (status: waiting) |
| US-22 | Sebagai courir, saya bisa menerima/mengambil tawaran delivery | Tombol "Accept" → status delivery berubah jadi "assigned", courier_id tercatat |
| US-23 | Sebagai courir, saya ingin melihat rute pengantaran di map | Halaman map menampilkan posisi restoran dan alamat tujuan |
| US-24 | Sebagai courir, saya bisa update status delivery (on_delivery → delivered) | Tombol update status; completion menandai order delivered |

### 4.6 Admin

| ID | Story | Acceptance Criteria |
|----|-------|---------------------|
| US-25 | Sebagai admin, saya ingin melihat dashboard dengan metrik penjualan | Dashboard: total orders, revenue, top menu, grafik harian |
| US-26 | Sebagai admin, saya bisa CRUD menu dan kategori | Resource controller untuk menus & categories lengkap |
| US-27 | Sebagai admin, saya bisa CRUD voucher (persentase, fixed, BOGO, free delivery) | Form voucher: tipe, nilai, min order, max diskon, quota, expired |
| US-28 | Sebagai admin, saya bisa CRUD user & assign role | Manage users: list, edit role, delete |
| US-29 | Sebagai admin, saya bisa melihat detail transaksi dan delivery | Halaman detail menampilkan semua info order, payment, dan delivery |
| US-30 | Sebagai admin, saya bisa melihat laporan penjualan & ekspor PDF | Halaman reports dengan filter; tombol export to PDF |
| US-31 | Sebagai admin, saya bisa CRUD artikel untuk halaman publik | Resource controller articles: title, content, thumbnail, publish status |

---

## 5. Functional Requirements

### 5.1 Authentication & Authorization

| FR-01 | Registrasi dengan email, nama, phone, password |
| FR-02 | Login dengan email + password |
| FR-03 | Google OAuth login |
| FR-04 | OTP verifikasi via email setelah register/login |
| FR-05 | Resend OTP |
| FR-06 | Forgot password flow (request → OTP verify → reset password) |
| FR-07 | Role-based access control: admin, cashier, kitchen, courier, customer |
| FR-08 | Proteksi route per role menggunakan middleware (`isAdmin`, `isCashier`, `isKitchen`, `isCourier`) |

### 5.2 Menu & Category Management

| FR-09 | Admin CRUD kategori (nama, slug) |
| FR-10 | Admin CRUD menu (nama, slug, deskripsi, harga, gambar, kategori, ketersediaan) |
| FR-11 | Public browse menu per kategori dengan status `is_available` |
| FR-12 | Soft delete untuk semua resource |

### 5.3 Cart & Checkout

| FR-13 | Tambah menu ke cart (session-based) |
| FR-14 | Update quantity & remove item dari cart |
| FR-15 | Checkout: pilih order type (dine_in / takeaway / delivery) |
| FR-16 | Hitung subtotal, diskon voucher, dan total secara otomatis |
| FR-17 | Input delivery address + koordinat (lat/lng) untuk tipe delivery |
| FR-18 | Aplikasi kode voucher dengan validasi (quota, expired, min order, max discount) |

### 5.4 Payment

| FR-19 | Integrasi Midtrans payment gateway |
| FR-20 | Metode pembayaran: QRIS, Virtual Account (VA) |
| FR-21 | Pembayaran cash via cashier (manual order) |
| FR-22 | Webhook Midtrans untuk update status pembayaran otomatis |
| FR-23 | Status payment: pending → success / failed / expired |

### 5.5 Order Management

| FR-24 | Generate nomor order unik (20 char) |
| FR-25 | Status order workflow: pending → paid → cooking → ready → completed / cancelled |
| FR-26 | Order history untuk customer (profile) |
| FR-27 | Cashier: daftar transaksi, detail transaksi |
| FR-28 | Cashier: buat manual order (pilih menu, quantity, proses, payment cash) |
| FR-29 | Cashier: konfirmasi pickup takeaway |

### 5.6 Kitchen Workflow

| FR-30 | Kitchen display board menampilkan order berstatus "paid" |
| FR-31 | Kitchen update status order: cooking → ready |
| FR-32 | Tampilan real-time (auto-refresh atau manual) |

### 5.7 Delivery Management

| FR-33 | Delivery dibuat saat checkout dengan tipe "delivery" |
| FR-34 | Status delivery: waiting → assigned → on_delivery → delivered |
| FR-35 | Courier melihat daftar offers (delivery tanpa courier) |
| FR-36 | Courier accept delivery → status berubah jadi "assigned" |
| FR-37 | Map view dengan posisi courier & tujuan (lat/lng) |
| FR-38 | Courier update posisi (courier_latitude, courier_longitude) |
| FR-39 | Courier complete delivery → status berubah jadi "delivered" |

### 5.8 Voucher System

| FR-40 | Admin CRUD voucher dengan tipe: percentage, fixed, bogo, free_delivery |
| FR-41 | Atribut voucher: nama, kode, deskripsi, tipe, nilai, min order, max diskon, quota, issued_count, expiration, is_active |
| FR-42 | Voucher welcome otomatis (NEW-BOGO-2026) untuk customer baru |
| FR-43 | Customer melihat & menggunakan voucher yang dimiliki |
| FR-44 | Validasi voucher saat apply (aktif, quota, expired, min order) |

### 5.9 Referral Program

| FR-45 | Setiap user mendapat referral code unik otomatis (format: QITCHEN-XXXX-YYYY) |
| FR-46 | Customer baru bisa daftar dengan kode referral (`referred_by`) |
| FR-47 | Referrer mendapat voucher reward (REF-REWARD-2026) setelah transaksi sukses PERTAMA referred user |
| FR-48 | Tracking referral via tabel referrals |

### 5.10 Article / CMS

| FR-49 | Admin CRUD artikel (judul, slug, kategori artikel, konten, thumbnail, publish status) |
| FR-50 | Public browse artikel published; detail per artikel |

### 5.11 Reporting & Dashboard

| FR-51 | Admin dashboard: metrik orders, revenue |
| FR-52 | Laporan penjualan dengan filter |
| FR-53 | Ekspor laporan ke PDF |

---

## 6. Non-Functional Requirements

### 6.1 Performance

| NFR-01 | Halaman publik (menu, artikel) dimuat dalam < 2 detik |
| NFR-02 | Kitchen display board auto-refresh dalam interval < 10 detik |
| NFR-03 | Database queries dioptimasi (eager loading, indexing) |

### 6.2 Security

| NFR-04 | Password di-hash (bcrypt via Laravel hashed casting) |
| NFR-05 | Role-based middleware untuk semua route terproteksi |
| NFR-06 | OTP verification mencegah akses tanpa verifikasi email |
| NFR-07 | CSRF protection (bawaan Laravel) |
| NFR-08 | Input validation & sanitization di semua form |
| NFR-09 | Soft delete untuk mencegah kehilangan data permanen |

### 6.3 Reliability

| NFR-10 | Atomic transaction untuk checkout & payment agar data konsisten |
| NFR-11 | Webhook Midtrans idempotent (tidak proses ulang transaksi yang sudah sukses) |
| NFR-12 | Referral reward hanya diberikan sekali untuk first completed order |

### 6.4 Usability

| NFR-13 | Responsive UI — dapat digunakan di desktop, tablet, dan mobile |
| NFR-14 | UI berbahasa Indonesia (atau Inggris, sesuai target user) |
| NFR-15 | Kitchen display board dengan tampilan jelas dan besar (large font, high contrast) |

### 6.5 Maintainability

| NFR-16 | Kode mengikuti konvensi Laravel (MVC, Eloquent ORM, Blade templating) |
| NFR-17 | Soft delete untuk semua resource (data recoverable) |
| NFR-18 | Environment-based configuration (.env untuk credentials) |

### 6.6 Technology Stack

| Layer | Technology |
|-------|-----------|
| Backend Framework | Laravel (PHP) |
| Frontend | Blade + Tailwind CSS |
| Database | MySQL / SQLite (development) |
| Payment Gateway | Midtrans |
| Authentication | Laravel Auth + Google OAuth + OTP Email |
| Maps | Leaflet.js / Google Maps (courier tracking) |

---

## 7. Scope

### 7.1 In Scope (Current Implementation)

| Modul | Cakupan |
|-------|---------|
| 🟢 Authentication | Register, login, Google OAuth, OTP verification, forgot/reset password |
| 🟢 Role Management | 5 role: Admin, Cashier, Kitchen, Courier, Customer — middleware per role |
| 🟢 Menu & Category | CRUD admin, public browse dengan filter kategori |
| 🟢 Cart & Checkout | Session cart, order type, perhitungan total & diskon, voucher |
| 🟢 Payment | Midtrans (QRIS, VA) + Cash via cashier + webhook handler |
| 🟢 Kitchen Board | Display pesanan "paid", update status cooking → ready |
| 🟢 Delivery | Order delivery, courier offers & accept, map tracking, status update |
| 🟢 Voucher | CRUD admin, 4 tipe voucher, welcome voucher, validasi |
| 🟢 Referral | Kode referral otomatis, reward voucher setelah transaksi sukses pertama |
| 🟢 Article CMS | CRUD admin, publikasi artikel |
| 🟢 Dashboard & Report | Dashboard admin, laporan, ekspor PDF |
| 🟢 Cashier Tools | Manual order, payment cash, pickup confirmation |
| 🟢 UI Responsive | Tailwind CSS mobile-first untuk semua halaman |

### 7.2 Out of Scope (Future Enhancement)

| Modul | Keterangan |
|-------|------------|
| 🔴 Real-time WebSocket | Kitchen board saat ini tidak real-time (perlu refresh; bisa ditambah Laravel Reverb di masa depan) |
| 🔴 Push Notification | Notifikasi status order via push/email belum diimplementasi |
| 🔴 Table Reservation | Fitur reservasi meja dine-in |
| 🔴 Loyalty Points | Sistem poin selain voucher |
| 🔴 Multi-branch | Manajemen multi-cabang restoran |
| 🔴 Mobile App | Aplikasi native iOS/Android |
| 🔴 Advanced Analytics | Prediksi penjualan, analisis pelanggan, heatmap |
| 🔴 Chat Customer-Kitchen | Komunikasi langsung terkait pesanan |
| 🔴 Multi-language | i18n untuk multi-bahasa |

---

## 8. Data Model Overview

### 8.1 Entity Relationship Summary

```
users (1) ────< orders (M) ────< order_items (M) >──── menus (1)
   │                │                                      │
   │                │──< payment (1)                       │──< categories (1)
   │                │──< delivery (1)                                    
   │                │──< voucher (M, nullable)
   │
   │──< user_vouchers (M) >── vouchers (1)
   │──< referrals (referrer / referred)
   │──< articles (M, admin only)
```

### 8.2 Key Tables

| Table | Primary Key | Key Fields |
|-------|------------|------------|
| `users` | `user_id` | name, email, password, phone, role, referral_code, referred_by, google_id, otp |
| `categories` | `category_id` | category_name, category_slug |
| `menus` | `menu_id` | category_id (FK), menu_name, menu_slug, menu_description, menu_price, menu_image, is_available |
| `orders` | `order_id` | user_id (FK), order_number, order_type (dine_in/takeaway/delivery), order_status, order_subtotal, order_discount, order_total, voucher_id (FK), order_notes |
| `order_items` | `order_item_id` | order_id (FK), menu_id (FK), quantity, item_price, item_subtotal, item_notes |
| `payments` | `payment_id` | order_id (FK), midtrans_transaction_id, payment_amount, payment_method (qris/va/cash), payment_status, paid_at |
| `deliveries` | `delivery_id` | order_id (FK), courier_id (FK), recipient_name, recipient_phone, delivery_address, lat/lng, delivery_status, courier_lat/lng |
| `vouchers` | `voucher_id` | voucher_name, voucher_code, voucher_type (percentage/fixed/bogo/free_delivery), voucher_value, min_order, max_discount, quota, issued_count, expiration_date, is_active |
| `user_vouchers` | `user_voucher_id` | user_id (FK), voucher_id (FK), is_used, used_at, expired_at |
| `referrals` | `referral_id` | referrer_id (FK), referred_user_id (FK), voucher_id (FK), rewarded_at |
| `articles` | `article_id` | admin_id (FK), article_title, article_slug, article_category, article_content, thumbnail, is_published, published_at |

---

## 9. Design Artifacts

Dokumen desain pendukung tersedia di direktori `docs/`:

| File | Keterangan |
|------|------------|
| `Qitchen-CDM.png` | Conceptual Data Model |
| `Qitchen-PDM.png` | Physical Data Model |
| `Qitchen-UseCase.png` | Use Case Diagram |
| `Qitchen-Sitemap.png` | Sitemap aplikasi |
| `Qitchen-UserFlow/` | User flow per role (Admin, Cashier, Courier, Customer, Kitchen) |
| `Qitchen-LowFidelity/` | Wireframe low-fidelity per modul |
| `Qitchen-HighFidelity/` | Mockup high-fidelity per modul |

---

> **Dokumen ini disusun berdasarkan hasil review codebase existing.**  
> Semua fitur yang tercantum di *In Scope* sudah terimplementasi atau sedang dalam pengerjaan di branch saat ini.  
> *Out of Scope* merupakan kandidat future enhancement.
