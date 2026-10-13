# Software Requirements Specification

# QITCHEN
## Modern Restaurant Workflow System

**Versi:** 1.0  
**Dibuat oleh:** Muhammad Fachriditya  
**Tanggal:** Mei 2026

---

## Daftar Isi

1. [Pendahuluan](#1-pendahuluan)
   - 1.1 Tujuan
   - 1.2 Konvensi Dokumen
   - 1.3 Target Pembaca dan Panduan Membaca
   - 1.4 Ruang Lingkup Produk
   - 1.5 Referensi
2. [Deskripsi Umum](#2-deskripsi-umum)
   - 2.1 Perspektif Produk
   - 2.2 Fungsi Produk
   - 2.3 Kelas dan Karakteristik Pengguna
   - 2.4 Lingkungan Operasi
   - 2.5 Batasan Desain dan Implementasi
3. [Kebutuhan Antarmuka Eksternal](#3-kebutuhan-antarmuka-eksternal)
   - 3.1 Antarmuka Pengguna
   - 3.2 Antarmuka Perangkat Lunak
   - 3.3 Antarmuka Komunikasi
4. [Fitur Sistem](#4-fitur-sistem)
   - 4.1 Customer Ordering System
   - 4.2 Authentication System
   - 4.3 Cashier Management System
   - 4.4 Kitchen Workflow System
   - 4.5 Customer Display Board & TTS Order Calling System
   - 4.6 Delivery Management & Live Courier Tracking System
   - 4.7 Voucher & Referral System
   - 4.8 Report Export System
   - 4.9 Admin Management System
5. [Kebutuhan Nonfungsional Lainnya](#5-kebutuhan-nonfungsional-lainnya)
   - 5.1 Kebutuhan Performa
   - 5.2 Kebutuhan Keamanan Operasional
   - 5.3 Kebutuhan Keamanan Sistem
   - 5.4 Atribut Kualitas Perangkat Lunak
   - 5.5 Aturan Bisnis
6. [Kebutuhan Lainnya](#6-kebutuhan-lainnya)
   - 6.1 Kebutuhan Database
   - 6.2 Kebutuhan Deployment Sistem
   - 6.3 Kebutuhan Responsif
   - 6.4 Pengembangan Sistem di Masa Depan
   - 6.5 Repository GitHub
   - 6.6 Dokumentasi Testing
- [Daftar Pustaka](#daftar-pustaka)
- [Lampiran](#lampiran)

---

## Riwayat Revisi Dokumen

| Nama | Tanggal | Alasan Pengubahan | Version |
|------|---------|-------------------|---------|
| Muhammad Fachriditya | Mei 2026 | Dokumen awal SRS Qitchen versi 1.0 | 1.0 |

---

# 1. Pendahuluan

## 1.1 Tujuan

Dokumen Software Requirement Specification (SRS) ini dibuat untuk menjelaskan kebutuhan sistem perangkat lunak Qitchen versi 1.0 secara rinci, baik kebutuhan fungsional maupun non-fungsional.

Qitchen merupakan sistem pemesanan makanan dan minuman modern berbasis web yang dirancang untuk membantu operasional restoran atau cafe melalui sistem digital terintegrasi dengan konsep self-service restaurant workflow. Sistem ini mendukung proses pemesanan makanan dan minuman secara mandiri melalui web ordering untuk kebutuhan dine in, takeaway, dan delivery order.

Dokumen ini mencakup seluruh kebutuhan utama sistem, meliputi:
- Sistem pemesanan customer
- Cashier workflow
- Kitchen board
- Customer display board
- Text To Speech (TTS) order calling
- Delivery management system
- Live courier tracking
- Voucher dan referral system
- Sistem autentikasi menggunakan email dan Google Login
- Reset password melalui email verification
- Export laporan transaksi dan penjualan berbasis PDF
- Serta dashboard admin management

Dokumen SRS ini digunakan sebagai acuan utama dalam proses analisis, perancangan, pengembangan, pengujian, dan deployment sistem Qitchen.

## 1.2 Konvensi Dokumen

Dokumen SRS ini menggunakan format penulisan formal dengan struktur penomoran bertingkat untuk mempermudah pembacaan dan identifikasi kebutuhan sistem.

Konvensi penulisan yang digunakan pada dokumen ini meliputi:
- Judul utama menggunakan format heading bertingkat
- Requirement sistem ditulis secara deskriptif dan terstruktur
- Istilah teknis seperti dashboard, kitchen board, web ordering, live tracking, dan Google Login menggunakan Bahasa Inggris sesuai konteks pengembangan perangkat lunak
- Penulisan nama fitur dan modul sistem menggunakan format huruf kapital pada bagian tertentu untuk memberikan penekanan

Dokumen ini juga menggunakan pembagian kebutuhan sistem menjadi:
- Kebutuhan fungsional
- Kebutuhan non-fungsional
- Kebutuhan database
- Serta kebutuhan deployment dan testing

Setiap requirement pada sistem memiliki prioritas dan fungsi masing-masing sesuai kebutuhan operasional aplikasi Qitchen.

## 1.3 Target Pembaca dan Panduan Membaca

Dokumen SRS ini ditujukan untuk berbagai pihak yang terlibat dalam pengembangan dan penggunaan sistem Qitchen, antara lain:
- Developer
- UI/UX designer
- Tester
- Project manager
- Stakeholder
- Serta pengguna sistem

Dokumen disusun mulai dari pendahuluan, deskripsi sistem, kebutuhan fungsional, kebutuhan non-fungsional, hingga kebutuhan deployment dan testing sistem.

Pembaca dapat mempelajari dokumen sesuai kebutuhan masing-masing:
- **Developer** — fokus pada kebutuhan sistem, database, dan deployment
- **UI/UX designer** — fokus pada kebutuhan antarmuka dan pengalaman pengguna
- **Tester** — fokus pada kebutuhan fungsional dan skenario pengujian
- **Stakeholder** — dapat membaca keseluruhan dokumen untuk memahami workflow dan tujuan sistem secara umum

## 1.4 Ruang Lingkup Produk

Qitchen merupakan sistem pemesanan restoran modern berbasis web yang dirancang untuk membantu proses operasional restoran atau cafe secara digital dan terintegrasi.

Sistem menggunakan konsep self-service restaurant workflow dimana customer melakukan pemesanan secara mandiri melalui web ordering. Sistem ini menyediakan fitur web ordering untuk kebutuhan dine in, takeaway, dan delivery order.

Monitoring status pembayaran digital secara otomatis menggunakan Midtrans Sandbox, kitchen workflow management, delivery order management dengan mekanisme delivery acceptance oleh courier internal, live courier tracking berbasis lokasi, customer display board, voucher dan referral system, autentikasi modern menggunakan email dan Google Login, reset password melalui email verification, serta dashboard admin untuk pengelolaan sistem. Sistem juga menyediakan fitur export laporan transaksi dan penjualan berbasis PDF untuk kebutuhan monitoring dan dokumentasi operasional restoran.

Sistem juga menyediakan halaman publik yang berfungsi sebagai media informasi dan promosi restoran, meliputi halaman beranda, artikel, profil restoran, dan informasi kontak yang dapat diakses oleh pengunjung tanpa perlu melakukan autentikasi.

Tujuan utama pengembangan Qitchen adalah:
1. Meningkatkan efisiensi operasional restoran
2. Mempermudah monitoring pesanan
3. Mengurangi proses manual
4. Memberikan pengalaman pemesanan modern bagi customer
5. Mendukung workflow restoran self-service modern secara digital

Qitchen dikembangkan menggunakan Laravel, Blade, TailwindCSS, dan MySQL dengan konsep responsive web application agar dapat digunakan pada perangkat mobile maupun desktop.

## 1.5 Referensi

Referensi yang digunakan dalam penyusunan dokumen SRS ini meliputi:

1. IEEE Software Requirements Specification Documentation
2. Laravel Official Documentation
3. TailwindCSS Official Documentation
4. MySQL Documentation
5. Laravel Breeze Documentation
6. Laravel Socialite Documentation
7. Google OAuth Documentation
8. Laravel Mail Documentation
9. Midtrans Documentation
10. LeafletJS Documentation
11. OpenStreetMap Documentation
12. Laravel DomPDF Documentation
13. Referensi workflow sistem pemesanan restoran modern berbasis web ordering
14. Dokumentasi perancangan sistem Qitchen

---

# 2. Deskripsi Umum

## 2.1 Perspektif Produk

Qitchen merupakan sistem pemesanan restoran modern berbasis web yang dikembangkan sebagai sistem baru (standalone system) untuk membantu operasional restoran atau cafe secara digital dan terintegrasi.

Qitchen menggunakan konsep modern self-service restaurant workflow dimana customer melakukan pemesanan secara mandiri melalui web ordering dengan konsep customer self-service tanpa proses pemesanan melalui waiter. Customer dapat melakukan pemesanan baik langsung melalui website untuk kebutuhan dine in, takeaway, dan delivery order.

Sistem ini dirancang untuk mendukung proses pemesanan makanan dan minuman melalui web ordering, monitoring status pembayaran digital secara otomatis melalui Midtrans Sandbox, pengelolaan antrean pesanan pada kitchen board, pengelolaan delivery order menggunakan courier internal dengan mekanisme delivery acceptance oleh courier, live courier tracking berbasis lokasi, autentikasi modern menggunakan email dan Google Login, serta export laporan transaksi berbasis PDF. Sistem ini juga menyediakan halaman publik yang berisi informasi restoran, artikel, dan kontak yang dapat diakses oleh pengunjung tanpa perlu login.

Qitchen terdiri dari beberapa modul utama yang saling terhubung, yaitu:
- Authentication system
- Customer ordering system
- Cashier management
- Kitchen workflow system
- Delivery management & Live courier tracking
- Voucher dan referral system
- Report export system
- Serta admin management system

Seluruh modul pada sistem menggunakan database terpusat sehingga data pesanan, pembayaran, status order, delivery, dan data pengguna dapat diproses secara terintegrasi dan realtime.

## 2.2 Fungsi Produk

Qitchen memiliki beberapa fungsi utama untuk mendukung operasional restoran modern, antara lain:

1. Menampilkan menu makanan dan minuman
2. Mengelola kategori menu
3. Menyediakan halaman informasi restoran dan artikel
4. Melakukan pemesanan melalui web ordering
5. Mengelola cart dan checkout
6. Menampilkan status pembayaran customer secara realtime
7. Mengelola pembayaran digital menggunakan Midtrans Sandbox
8. Mendukung input order manual oleh cashier
9. Menampilkan antrean pesanan pada kitchen board
10. Memperbarui status pesanan secara realtime
11. Mendukung customer self pickup melalui pickup counter
12. Mendukung takeaway order dan pickup mandiri
13. Mengelola voucher dan referral system
14. Mengelola delivery order
15. Menampilkan lokasi courier secara realtime
16. Memperbarui lokasi courier secara berkala
17. Menyediakan login menggunakan email dan Google Authentication
18. Menyediakan fitur reset password melalui email verification
19. Generate dan export laporan transaksi dalam format PDF
20. Menyediakan dashboard admin untuk pengelolaan sistem

Sistem juga mendukung penggunaan member account untuk fitur voucher personal, referral code, dan riwayat pemesanan customer.

### 2.2.1 Use Case Diagram

Use Case Diagram digunakan untuk menggambarkan interaksi antara pengguna dengan sistem Qitchen sesuai hak akses dan fungsi masing-masing.

Aktor utama pada sistem Qitchen meliputi:
- Guest customer
- Member customer
- Cashier
- Kitchen staff
- Courier
- Dan Admin

Masing-masing aktor memiliki akses dan fungsi yang berbeda sesuai kebutuhan operasional sistem.

> **Catatan:** Diagram Use Case terdapat pada dokumen perancangan sistem (file gambar terpisah).

## 2.3 Kelas dan Karakteristik Pengguna

Pengguna sistem Qitchen dibagi menjadi beberapa kelompok berdasarkan hak akses dan fungsi penggunaan sistem.

### Guest Customer
Pengguna umum yang dapat melakukan pemesanan makanan dan minuman tanpa login akun melalui web ordering baik untuk dine in, takeaway, maupun delivery order.

### Member Customer
Pengguna yang memiliki akun pada sistem dan dapat menggunakan fitur tambahan seperti voucher personal, referral system, riwayat pemesanan, Google Login, dan reset password melalui email verification.

### Cashier
Pengguna yang bertugas memantau status pembayaran customer, membantu input order manual untuk customer non-digital, menangani pickup counter, serta melakukan monitoring transaksi pembayaran.

### Kitchen Staff
Pengguna yang bertugas mengelola antrean pesanan dan memperbarui status proses memasak pada kitchen board.

### Courier
Pengguna yang bertugas menerima delivery order yang tersedia, mengantarkan pesanan kepada customer, serta memperbarui status pengiriman dan lokasi courier secara realtime.

### Admin
Pengguna dengan hak akses penuh untuk mengelola menu, kategori, voucher, pengguna, transaksi, laporan sistem, dan monitoring operasional sistem secara keseluruhan.

## 2.4 Lingkungan Operasi

Sistem Qitchen dikembangkan sebagai web application yang dapat dijalankan pada berbagai perangkat desktop maupun mobile melalui browser modern.

Lingkungan operasi sistem meliputi:

| Kategori | Spesifikasi |
|----------|-------------|
| **Sistem Operasi** | Windows, Linux, Android, iOS |
| **Browser** | Google Chrome, Microsoft Edge, Mozilla Firefox, Safari |
| **Backend Framework** | Laravel |
| **Frontend** | Blade Template Engine, TailwindCSS |
| **Database** | MySQL |
| **Maps Library** | Leaflet.js, OpenStreetMap |
| **Payment Gateway** | Midtrans Sandbox |
| **PDF Generator** | Laravel DomPDF |
| **Mail Service** | SMTP Mail Service |
| **Web Server** | Apache / Nginx |

Sistem dirancang menggunakan konsep responsive web design agar tampilan dapat menyesuaikan ukuran layar perangkat pengguna.

Koneksi internet diperlukan untuk proses autentikasi Google Login, pengiriman email reset password, integrasi Midtrans Sandbox, layanan pemetaan berbasis OpenStreetMap, serta fitur realtime tracking.

## 2.5 Batasan Desain dan Implementasi

Dalam proses pengembangan sistem Qitchen terdapat beberapa batasan desain dan implementasi, antara lain:

- Sistem dikembangkan menggunakan framework Laravel dan database MySQL
- Sistem menggunakan Blade dan TailwindCSS sebagai teknologi frontend utama
- Sistem pembayaran digital menggunakan integrasi Midtrans Sandbox sebagai simulasi payment gateway
- Status pembayaran digital diperbarui secara otomatis berdasarkan callback dari Midtrans Sandbox
- Sistem autentikasi Google menggunakan integrasi OAuth melalui Laravel Socialite
- Sistem reset password menggunakan OTP email verification berbasis SMTP mail service
- Sistem delivery menggunakan courier internal dan belum terintegrasi dengan layanan pihak ketiga
- Sistem realtime menggunakan metode polling sederhana untuk memperbarui status pesanan dan lokasi courier secara berkala
- Live courier tracking menggunakan browser geolocation dan belum mendukung tracking background seperti aplikasi mobile native
- Sistem peta dan tracking courier menggunakan LeafletJS dan OpenStreetMap sebagai layanan pemetaan utama
- Fitur export laporan menggunakan Laravel DomPDF untuk menghasilkan dokumen PDF berbasis server-side rendering
- Sistem hanya mendukung operasional satu restoran atau cafe dan belum mendukung multi-branch management
- Sistem dikembangkan dengan fokus pada kebutuhan minimum viable product (MVP)

Batasan tersebut ditetapkan agar proses pengembangan sistem tetap terfokus, terstruktur, dan sesuai target waktu pengembangan.

---

# 3. Kebutuhan Antarmuka Eksternal

## 3.1 Antarmuka Pengguna

Antarmuka pengguna (User Interface) pada sistem Qitchen dirancang menggunakan konsep modern web application dengan tampilan yang responsif, sederhana, dan mudah digunakan pada perangkat desktop maupun mobile.

Desain antarmuka mengutamakan kemudahan navigasi bagi seluruh pengguna sistem, termasuk Customer, Cashier, Kitchen Staff, Courier, dan Admin.

Karakteristik antarmuka pengguna pada sistem Qitchen meliputi:
- Menggunakan desain responsive web sehingga dapat menyesuaikan berbagai ukuran layar perangkat
- Menggunakan komponen antarmuka yang konsisten pada seluruh halaman sistem
- Menyediakan navigasi yang sederhana dan mudah dipahami
- Menampilkan informasi status pesanan dan pembayaran secara realtime
- Menggunakan warna, ikon, dan elemen visual untuk mempermudah identifikasi status sistem
- Menampilkan pesan validasi dan error secara informatif kepada pengguna
- Mendukung akses melalui browser modern tanpa memerlukan instalasi aplikasi tambahan

Detail desain antarmuka pengguna dijelaskan lebih lanjut pada dokumen UI/UX Design Qitchen (file gambar terpisah).

### 3.1.1 Sitemap

Sitemap digunakan untuk menggambarkan struktur halaman dan hubungan navigasi antar fitur pada sistem Qitchen.

Sitemap mencakup seluruh halaman yang dapat diakses oleh:
- Public
- Guest Customer
- Member Customer
- Cashier
- Kitchen Staff
- Courier
- Admin

> **Catatan:** Diagram sitemap terdapat pada dokumen perancangan sistem (file gambar terpisah).

### 3.1.2 User Flow

User Flow digunakan untuk menggambarkan alur penggunaan sistem berdasarkan peran pengguna.

User Flow pada sistem Qitchen mencakup:
- Customer Ordering Flow
- Cashier Workflow
- Kitchen Workflow
- Courier Workflow
- Admin Management Flow

> **Catatan:** Diagram user flow terdapat pada dokumen perancangan sistem (file gambar terpisah).

### 3.1.3 Desain Navigasi Sistem

Sistem Qitchen menggunakan struktur navigasi berbasis role-based access control (RBAC), dimana setiap pengguna hanya dapat mengakses fitur sesuai hak akses yang dimiliki.

Navigasi sistem dibagi menjadi beberapa kelompok utama:

**Public Navigation:**
- Home
- Articles
- About Us
- Contact
- Login
- Register

**Guest Customer Navigation:**
- Menu
- Cart
- Checkout
- Order Tracking

**Member Customer Navigation:**
- Menu
- Cart
- Checkout
- Order Tracking
- Profile
- Order History
- Voucher

**Cashier Navigation:**
- Dashboard
- Manual Order
- Transaction Monitoring
- Pickup Counter

**Kitchen Navigation:**
- Kitchen Board
- Waiting Orders
- Cooking Orders
- Ready Orders

**Courier Navigation:**
- Available Deliveries
- Active Deliveries
- Delivery Tracking

**Admin Navigation:**
- Dashboard
- Menu Management
- Category Management
- Voucher Management
- User Management
- Transaction Monitoring
- Delivery Monitoring
- Reports

Navigasi dirancang agar setiap pengguna dapat mengakses fitur utama dengan jumlah langkah seminimal mungkin.

## 3.2 Antarmuka Perangkat Lunak

Sistem Qitchen berinteraksi dengan berbagai perangkat lunak dan layanan eksternal untuk mendukung operasional sistem.

### Database
- **MySQL** — Digunakan untuk menyimpan data pengguna, menu, transaksi, voucher, pesanan, delivery, dan laporan sistem.

### Backend Framework
- **Laravel** — Digunakan untuk mengelola logika bisnis, autentikasi, manajemen database, dan integrasi layanan eksternal.

### Frontend Framework
- **Blade Template Engine** — Digunakan untuk membangun antarmuka pengguna berbasis web yang responsif.
- **TailwindCSS**

### Payment Gateway
- **Midtrans Sandbox** — Digunakan untuk simulasi pembayaran digital dan pengelolaan status pembayaran secara otomatis melalui callback API.

Data yang dipertukarkan:
- Nomor transaksi
- Nominal pembayaran
- Status pembayaran
- Token pembayaran

### Authentication Service
- **Google OAuth** via **Laravel Socialite** — Digunakan untuk mendukung login menggunakan akun Google.

Data yang dipertukarkan:
- Nama pengguna
- Email pengguna
- Google Account ID

### Email Service
- **SMTP Mail Service** — Digunakan untuk reset password, email verifikasi, dan notifikasi sistem.

### Mapping Service
- **LeafletJS** & **OpenStreetMap** — Digunakan untuk menampilkan peta dan lokasi courier pada fitur live delivery tracking.

Data yang dipertukarkan:
- Latitude
- Longitude
- Lokasi pengiriman

### PDF Service
- **Laravel DomPDF** — Digunakan untuk menghasilkan laporan transaksi dan penjualan dalam format PDF.

### Browser API
- **Geolocation API** — Untuk live courier tracking

## 3.3 Antarmuka Komunikasi

Sistem Qitchen menggunakan komunikasi berbasis jaringan internet untuk mendukung seluruh proses operasional sistem.

### Protokol Komunikasi
- HTTP / HTTPS — HTTPS digunakan sebagai protokol utama untuk menjaga keamanan komunikasi data antara client dan server.

### Komunikasi API
Sistem menggunakan API untuk berkomunikasi dengan layanan eksternal seperti:
- Midtrans Sandbox
- Google OAuth
- SMTP Mail Service
- OpenStreetMap

### Keamanan Komunikasi
- Enkripsi data menggunakan HTTPS
- Penggunaan token autentikasi pada layanan pihak ketiga
- Validasi input pengguna pada sisi client dan server
- Proteksi terhadap akses tidak sah menggunakan sistem autentikasi dan otorisasi berbasis role

### Sinkronisasi Data
Sinkronisasi data dilakukan secara realtime maupun berkala melalui mekanisme polling untuk:
- Status pembayaran
- Status pesanan
- Kitchen Board
- Live Courier Tracking

Sistem memerlukan koneksi internet aktif agar seluruh fitur komunikasi dan sinkronisasi data dapat berjalan dengan baik.

---

# 4. Fitur Sistem

Bab ini menjelaskan kebutuhan fungsional sistem Qitchen berdasarkan fitur-fitur utama yang tersedia pada aplikasi. Setiap fitur dijelaskan melalui deskripsi fitur, urutan stimulus dan respons antara pengguna dengan sistem, serta kebutuhan fungsional yang harus dipenuhi agar sistem dapat berjalan sesuai dengan kebutuhan operasional restoran modern berbasis self-service.

Fitur-fitur yang dijelaskan pada bab ini meliputi Customer Ordering System, Authentication System, Cashier Management System, Kitchen Workflow System, Customer Display Board & Text To Speech Order Calling System [BELUM TERIMPLEMENTASI — FASE 2], Delivery Management & Live Courier Tracking System, Voucher dan Referral System, Report Export System, serta Admin Management System.

## 4.1 Customer Ordering System

### 4.1.1 Deskripsi dan Prioritas

Customer Ordering System merupakan fitur utama pada Qitchen yang digunakan untuk memungkinkan customer melakukan pemesanan makanan dan minuman secara mandiri melalui web ordering. Customer dapat mengakses sistem melalui website untuk kebutuhan dine in, takeaway, dan delivery order.

Fitur ini mencakup proses melihat menu, memilih produk, mengelola cart, melakukan checkout, menggunakan voucher, melakukan pembayaran, serta memantau status pesanan hingga pesanan selesai diproses.

**Prioritas:** Tinggi

### 4.1.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Customer membuka halaman web ordering | Sistem menampilkan halaman utama yang berisi daftar menu makanan dan minuman yang tersedia |
| REQ-2 | Customer memilih produk yang diinginkan | Sistem menampilkan detail produk yang dipilih |
| REQ-3 | Customer menambahkan produk ke cart | Sistem menyimpan produk ke cart dan memperbarui total pesanan |
| REQ-4 | Customer membuka halaman cart | Sistem menampilkan daftar item yang dipilih beserta subtotal dan total pesanan |
| REQ-5 | Customer melakukan checkout | Sistem menampilkan ringkasan pesanan, metode pembayaran, serta informasi pemesanan yang diperlukan sesuai jenis order |
| REQ-6 | Customer mengonfirmasi pesanan | Sistem membuat data order dan mengarahkan customer ke proses pembayaran |
| REQ-7 | Pembayaran berhasil dilakukan | Sistem memperbarui status pembayaran dan meneruskan pesanan ke Kitchen Workflow System untuk diproses |

### 4.1.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-CO-01 | Sistem harus menampilkan daftar menu makanan dan minuman yang tersedia |
| FR-CO-02 | Sistem harus menampilkan informasi detail produk yang dipilih customer |
| FR-CO-03 | Sistem harus memungkinkan customer menambahkan produk ke cart |
| FR-CO-04 | Sistem harus memungkinkan customer mengubah jumlah item pada cart |
| FR-CO-05 | Sistem harus memungkinkan customer menghapus item dari cart |
| FR-CO-06 | Sistem harus menghitung subtotal dan total pesanan secara otomatis |
| FR-CO-07 | Sistem harus mendukung pemesanan dine in, takeaway, dan delivery order |
| FR-CO-08 | Sistem harus memvalidasi data checkout sebelum pesanan dibuat |
| FR-CO-09 | Sistem harus memungkinkan penggunaan voucher yang valid untuk customer yang memiliki akun |
| FR-CO-10 | Sistem harus membuat order baru setelah customer mengkonfirmasi checkout |
| FR-CO-11 | Sistem harus menampilkan status pembayaran customer secara realtime |
| FR-CO-12 | Sistem harus menampilkan status pesanan customer secara realtime |
| FR-CO-13 | Sistem harus menyimpan riwayat pesanan untuk customer yang memiliki akun |
| FR-CO-14 | Sistem harus menampilkan pesan kesalahan apabila data checkout tidak lengkap atau tidak valid |

## 4.2 Authentication System

### 4.2.1 Deskripsi dan Prioritas

Authentication System merupakan fitur yang digunakan untuk mengelola proses autentikasi dan otorisasi pengguna pada sistem Qitchen. Sistem mendukung registrasi akun menggunakan email, login menggunakan email dan password, login menggunakan Google Account, reset password melalui OTP email verification, serta proses logout pengguna.

Fitur ini bertujuan untuk memberikan akses yang aman kepada pengguna serta mendukung penggunaan fitur khusus member seperti voucher personal, referral system, dan riwayat pemesanan.

**Prioritas:** Tinggi

### 4.2.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Pengguna memilih menu registrasi akun | Sistem menampilkan formulir registrasi pengguna |
| REQ-2 | Pengguna mengisi data registrasi dan mengirim formulir | Sistem memvalidasi data dan membuat akun baru apabila data valid |
| REQ-3 | Pengguna melakukan login menggunakan email dan password | Sistem memvalidasi kredensial dan memberikan akses ke akun pengguna |
| REQ-4 | Pengguna memilih login menggunakan Google Account | Sistem mengarahkan pengguna ke halaman autentikasi Google dan membuat sesi login setelah autentikasi berhasil |
| REQ-5 | Pengguna memilih fitur forgot password | Sistem mengirimkan kode OTP reset password ke alamat email pengguna |
| REQ-6 | Pengguna memverifikasi OTP dan membuat password baru | Sistem memverifikasi kode OTP dan menampilkan halaman reset password. Setelah password baru berhasil diperbarui, sistem mengarahkan pengguna ke halaman login |
| REQ-7 | Pengguna memilih logout | Sistem menghapus sesi login dan mengarahkan pengguna ke halaman utama |

### 4.2.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-AU-01 | Sistem harus memungkinkan pengguna membuat akun menggunakan email |
| FR-AU-02 | Sistem harus memvalidasi format email pada saat registrasi |
| FR-AU-03 | Sistem harus memastikan email yang digunakan belum terdaftar pada sistem |
| FR-AU-04 | Sistem harus memungkinkan pengguna login menggunakan email dan password |
| FR-AU-05 | Sistem harus memvalidasi kecocokan email dan password saat login |
| FR-AU-06 | Sistem harus memungkinkan pengguna login menggunakan Google Authentication |
| FR-AU-07 | Sistem harus membuat akun baru secara otomatis apabila pengguna login menggunakan Google Account yang belum terdaftar |
| FR-AU-08 | Sistem harus memungkinkan pengguna melakukan logout |
| FR-AU-09 | Sistem harus menyediakan fitur forgot password |
| FR-AU-10 | Sistem harus mengirim kode OTP reset password ke email pengguna yang terdaftar |
| FR-AU-11 | Sistem harus memungkinkan pengguna membuat password baru setelah memverifikasi kode OTP reset password yang valid |
| FR-AU-12 | Sistem harus menampilkan pesan kesalahan apabila email atau password yang dimasukkan tidak valid |
| FR-AU-13 | Sistem harus menampilkan pesan kesalahan apabila email registrasi telah digunakan oleh akun lain |
| FR-AU-14 | Sistem harus menyimpan sesi login pengguna setelah autentikasi berhasil |

## 4.3 Cashier Management System

### 4.3.1 Deskripsi dan Prioritas

Cashier Management System merupakan fitur yang digunakan oleh cashier untuk membantu proses operasional restoran yang berkaitan dengan transaksi dan pengambilan pesanan. Cashier dapat memantau status pembayaran customer, membantu membuat pesanan secara manual apabila diperlukan, mengelola proses pickup order, serta memantau transaksi yang terjadi pada sistem.

Fitur ini berfungsi sebagai penghubung antara customer dan operasional restoran untuk memastikan pesanan dapat diproses dan diserahkan dengan baik.

**Prioritas:** Tinggi

### 4.3.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Cashier membuka dashboard cashier | Sistem menampilkan daftar transaksi dan status pembayaran customer |
| REQ-2 | Cashier melihat detail transaksi tertentu | Sistem menampilkan informasi detail pesanan dan status pembayaran |
| REQ-3 | Cashier membuat pesanan manual untuk customer | Sistem menampilkan formulir pemesanan dan menyimpan data order setelah dikonfirmasi |
| REQ-4 | Customer datang untuk mengambil pesanan | Cashier memverifikasi nomor order dan menyerahkan pesanan kepada customer |
| REQ-5 | Cashier memantau daftar transaksi | Sistem menampilkan informasi transaksi yang telah dibayar maupun yang masih menunggu pembayaran |

### 4.3.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-CS-01 | Sistem harus menyediakan dashboard khusus untuk cashier |
| FR-CS-02 | Sistem harus menampilkan daftar transaksi customer |
| FR-CS-03 | Sistem harus menampilkan status pembayaran setiap transaksi |
| FR-CS-04 | Sistem harus menampilkan detail pesanan customer |
| FR-CS-05 | Sistem harus memungkinkan cashier membuat pesanan secara manual |
| FR-CS-06 | Sistem harus memungkinkan cashier memilih menu saat membuat pesanan manual |
| FR-CS-07 | Sistem harus menghitung total pesanan secara otomatis pada order manual |
| FR-CS-08 | Sistem harus menyimpan data pesanan manual ke dalam sistem |
| FR-CS-09 | Sistem harus menampilkan daftar pesanan yang siap diambil customer |
| FR-CS-10 | Sistem harus memungkinkan cashier memperbarui status pesanan setelah pesanan diserahkan kepada customer |
| FR-CS-11 | Sistem harus menampilkan pesan kesalahan apabila data pesanan manual tidak lengkap atau tidak valid |

## 4.4 Kitchen Workflow System

### 4.4.1 Deskripsi dan Prioritas

Kitchen Workflow System merupakan fitur yang digunakan untuk membantu kitchen staff mengelola antrean pesanan yang masuk dari sistem. Kitchen staff dapat melihat daftar pesanan yang telah dibayar, memperbarui status proses memasak, serta menandai pesanan yang telah selesai diproses.

Fitur ini bertujuan untuk memastikan seluruh pesanan dapat diproses secara terstruktur dan status pesanan dapat diperbarui secara realtime sehingga customer dan cashier dapat mengetahui perkembangan pesanan.

**Prioritas:** Tinggi

### 4.4.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Kitchen staff membuka halaman Kitchen Board | Sistem menampilkan daftar pesanan yang sedang menunggu untuk diproses |
| REQ-2 | Kitchen staff memilih pesanan dan mengubah status menjadi Cooking | Sistem memperbarui status pesanan menjadi Cooking |
| REQ-3 | Kitchen staff menyelesaikan proses memasak dan mengubah status menjadi Ready | Sistem memperbarui status pesanan menjadi Ready dan menampilkan informasi status terbaru pada Kitchen Board |
| REQ-4 | Status pesanan berubah menjadi Ready | Sistem menampilkan perubahan status pada Kitchen Board |

### 4.4.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-KW-01 | Sistem harus menyediakan halaman Kitchen Board untuk kitchen staff |
| FR-KW-02 | Sistem harus menampilkan daftar pesanan yang telah berhasil dibuat dan siap diproses |
| FR-KW-03 | Sistem harus menampilkan informasi detail pesanan yang meliputi nomor order, daftar menu, jumlah item, dan catatan pesanan |
| FR-KW-04 | Sistem harus memungkinkan kitchen staff mengubah status pesanan dari Waiting menjadi Cooking |
| FR-KW-05 | Sistem harus memungkinkan kitchen staff mengubah status pesanan dari Cooking menjadi Ready |
| FR-KW-06 | Sistem harus memperbarui status pesanan secara realtime setelah status diubah |
| FR-KW-07 | Sistem harus menampilkan data pesanan berstatus Ready pada halaman yang sesuai (saat ini Kitchen Board) |
| FR-KW-08 | Sistem harus menampilkan perubahan status pesanan pada Kitchen Board ketika status berubah menjadi Ready |
| FR-KW-09 | Sistem harus menampilkan pesan kesalahan apabila terjadi kegagalan dalam memperbarui status pesanan |

## 4.5 Customer Display Board & Text To Speech Order Calling System [BELUM TERIMPLEMENTASI — FASE 2]

### 4.5.1 Deskripsi dan Prioritas

Customer Display Board & Text To Speech (TTS) Order Calling System merupakan fitur yang direncanakan untuk menampilkan status pesanan yang telah selesai diproses oleh kitchen serta membantu proses pemanggilan customer secara otomatis menggunakan suara. Fitur ini **BELUM TERIMPLEMENTASI** pada versi 1.0 dan akan dikembangkan pada Fase 2.

Fitur ini bertujuan untuk memudahkan customer dalam mengetahui status pesanan dan mendukung proses self pickup tanpa perlu menunggu informasi dari staff restoran secara langsung.

**Prioritas:** Sedang (Fase 2)

### 4.5.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Kitchen staff mengubah status pesanan menjadi Ready | [BELUM TERIMPLEMENTASI] Sistem akan mengirimkan data pesanan ke Customer Display Board |
| REQ-2 | Data pesanan diterima oleh Customer Display Board | [BELUM TERIMPLEMENTASI] Sistem akan menampilkan nomor pesanan pada daftar pesanan siap diambil |
| REQ-3 | Nomor pesanan berhasil ditampilkan pada Customer Display Board | [BELUM TERIMPLEMENTASI] Sistem akan menjalankan Text To Speech (TTS) untuk memanggil nomor pesanan customer |
| REQ-4 | Customer melihat nomor pesanan dipanggil | Customer menuju pickup counter untuk mengambil pesanan |

### 4.5.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-CD-01 | [BELUM TERIMPLEMENTASI] Sistem harus menyediakan halaman Customer Display Board |
| FR-CD-02 | [BELUM TERIMPLEMENTASI] Sistem harus menampilkan daftar pesanan yang berstatus Ready |
| FR-CD-03 | [BELUM TERIMPLEMENTASI] Sistem harus menampilkan nomor pesanan yang siap diambil customer |
| FR-CD-04 | [BELUM TERIMPLEMENTASI] Sistem harus memperbarui daftar pesanan secara realtime |
| FR-CD-05 | [BELUM TERIMPLEMENTASI] Sistem harus menerima data pesanan dari Kitchen Workflow System |
| FR-CD-06 | [BELUM TERIMPLEMENTASI] Sistem harus menjalankan Text To Speech (TTS) ketika pesanan berubah menjadi Ready |
| FR-CD-07 | [BELUM TERIMPLEMENTASI] Sistem harus menyebutkan nomor pesanan yang dipanggil melalui Text To Speech (TTS) |
| FR-CD-08 | [BELUM TERIMPLEMENTASI] Sistem harus menampilkan pesan atau indikator apabila tidak terdapat pesanan yang siap diambil |
| FR-CD-09 | [BELUM TERIMPLEMENTASI] Sistem harus menampilkan pesan kesalahan apabila terjadi kegagalan pada proses pemanggilan nomor pesanan |

## 4.6 Delivery Management & Live Courier Tracking System

### 4.6.1 Deskripsi dan Prioritas

Delivery Management & Live Courier Tracking System merupakan fitur yang digunakan untuk mengelola proses pengiriman pesanan kepada customer. Sistem memungkinkan courier menerima dan mengelola pesanan delivery, memperbarui status pengiriman, serta membagikan lokasi secara realtime agar customer dapat memantau proses pengantaran.

Fitur ini bertujuan untuk meningkatkan transparansi proses pengiriman serta memberikan informasi status pengantaran secara realtime kepada customer.

**Prioritas:** Tinggi

### 4.6.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Customer memilih metode delivery saat checkout | Sistem membuat pesanan delivery dan menunggu proses penugasan courier |
| REQ-2 | Pesanan delivery tersedia pada dashboard courier | Sistem menampilkan daftar pesanan delivery yang dapat diambil oleh courier |
| REQ-3 | Courier menerima pesanan delivery | Sistem menetapkan courier pada pesanan dan memperbarui status pengiriman |
| REQ-4 | Courier mengambil pesanan | Sistem memperbarui status pesanan menjadi On Delivery |
| REQ-5 | Courier memperbarui lokasi secara berkala | Sistem menyimpan lokasi courier dan memperbarui peta tracking customer secara realtime |
| REQ-6 | Customer membuka halaman tracking pengiriman | Sistem menampilkan status pengiriman dan lokasi courier pada peta |
| REQ-7 | Courier berhasil menyerahkan pesanan kepada customer | Sistem memperbarui status pengiriman menjadi Delivered |

### 4.6.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-DL-01 | Sistem harus mendukung pemesanan dengan metode delivery |
| FR-DL-02 | Sistem harus menyediakan dashboard khusus untuk courier |
| FR-DL-03 | Sistem harus menampilkan daftar pesanan delivery yang tersedia untuk courier |
| FR-DL-04 | Sistem harus memungkinkan courier menerima pesanan delivery |
| FR-DL-05 | Sistem harus menetapkan courier pada pesanan yang diterima |
| FR-DL-06 | Sistem harus memungkinkan courier memperbarui status pengiriman |
| FR-DL-07 | Sistem harus mendukung status pengiriman Waiting, Assigned, On Delivery, dan Delivered |
| FR-DL-08 | Sistem harus memungkinkan courier membagikan lokasi secara realtime menggunakan browser geolocation |
| FR-DL-09 | Sistem harus menyimpan dan memperbarui lokasi courier secara berkala |
| FR-DL-10 | Sistem harus menampilkan lokasi courier pada halaman tracking customer |
| FR-DL-11 | Sistem harus menampilkan status pengiriman kepada customer secara realtime |
| FR-DL-12 | Sistem harus menampilkan riwayat delivery kepada admin |
| FR-DL-13 | Sistem harus menampilkan pesan kesalahan apabila lokasi courier tidak dapat diperoleh dari perangkat |

## 4.7 Voucher & Referral System

### 4.7.1 Deskripsi dan Prioritas

Voucher dan Referral System merupakan fitur yang digunakan untuk memberikan promosi kepada customer melalui penggunaan voucher dan program referral. Sistem memungkinkan member menggunakan voucher yang valid saat melakukan checkout serta membagikan kode referral kepada pengguna lain.

Fitur ini bertujuan untuk meningkatkan loyalitas customer, menarik pengguna baru, serta mendukung aktivitas promosi restoran melalui sistem digital.

**Prioritas:** Sedang

### 4.7.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Member membuka halaman voucher | Sistem menampilkan daftar voucher yang tersedia dan dapat digunakan |
| REQ-2 | Member memasukkan kode voucher saat checkout | Sistem memvalidasi voucher dan menghitung ulang total pembayaran apabila voucher valid |
| REQ-3 | Member membagikan kode referral kepada pengguna lain | Sistem menyimpan hubungan referral antara pengguna yang mengundang dan pengguna yang mendaftar menggunakan kode referral |
| REQ-4 | Pengguna baru melakukan registrasi menggunakan kode referral | Sistem mencatat data referral dan memberikan manfaat sesuai aturan referral yang berlaku |
| REQ-5 | Voucher tidak valid atau telah kedaluwarsa | Sistem menampilkan pesan kesalahan dan menolak penggunaan voucher |

### 4.7.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-VR-01 | Sistem harus menyediakan fitur voucher untuk member |
| FR-VR-02 | Sistem harus menampilkan daftar voucher yang dimiliki member |
| FR-VR-03 | Sistem harus memvalidasi status voucher sebelum digunakan |
| FR-VR-04 | Sistem harus memvalidasi masa berlaku voucher |
| FR-VR-05 | Sistem harus menerapkan potongan harga sesuai aturan voucher yang digunakan |
| FR-VR-06 | Sistem harus menolak penggunaan voucher yang tidak valid atau telah kedaluwarsa |
| FR-VR-07 | Sistem harus menyediakan kode referral unik untuk setiap member |
| FR-VR-08 | Sistem harus memungkinkan pengguna baru melakukan registrasi menggunakan kode referral |
| FR-VR-09 | Sistem harus menyimpan data hubungan referral antara member dan pengguna yang direferensikan |
| FR-VR-10 | Sistem harus menampilkan riwayat penggunaan voucher oleh member |
| FR-VR-11 | Sistem harus menampilkan pesan kesalahan apabila kode voucher atau kode referral tidak valid |

## 4.8 Report Export System

### 4.8.1 Deskripsi dan Prioritas

Report Export System merupakan fitur yang digunakan untuk menghasilkan laporan transaksi dan penjualan dalam format PDF. Fitur ini membantu admin dalam melakukan monitoring, dokumentasi, dan evaluasi operasional restoran berdasarkan data yang tersimpan pada sistem.

Sistem memungkinkan admin memilih periode laporan yang diinginkan sebelum menghasilkan dokumen PDF yang dapat diunduh dan disimpan.

**Prioritas:** Sedang

### 4.8.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Admin membuka halaman laporan | Sistem menampilkan pilihan jenis laporan dan periode laporan |
| REQ-2 | Admin memilih periode laporan dan menekan tombol generate report | Sistem mengumpulkan data transaksi sesuai periode yang dipilih |
| REQ-3 | Data laporan berhasil diproses | Sistem menampilkan ringkasan laporan kepada admin |
| REQ-4 | Admin memilih export PDF | Sistem menghasilkan dokumen PDF dan menyediakan file untuk diunduh |

### 4.8.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-RP-01 | Sistem harus menyediakan halaman laporan untuk admin |
| FR-RP-02 | Sistem harus memungkinkan admin memilih periode laporan |
| FR-RP-03 | Sistem harus menampilkan ringkasan data transaksi berdasarkan periode yang dipilih |
| FR-RP-04 | Sistem harus menampilkan total transaksi pada periode laporan |
| FR-RP-05 | Sistem harus menampilkan total pendapatan pada periode laporan |
| FR-RP-06 | Sistem harus menghasilkan laporan dalam format PDF |
| FR-RP-07 | Sistem harus memungkinkan admin mengunduh laporan yang telah dihasilkan |
| FR-RP-08 | Sistem harus menampilkan pesan kesalahan apabila data laporan tidak tersedia |
| FR-RP-09 | Sistem harus menampilkan pesan kesalahan apabila proses generate PDF gagal |

## 4.9 Admin Management System

### 4.9.1 Deskripsi dan Prioritas

Admin Management System merupakan fitur yang digunakan untuk mengelola seluruh data dan operasional sistem Qitchen. Admin memiliki hak akses penuh untuk mengelola menu, kategori, promosi, voucher, pengguna, pesanan, delivery, serta melakukan monitoring aktivitas sistem melalui dashboard administrasi.

Fitur ini berfungsi sebagai pusat pengelolaan sistem untuk memastikan seluruh modul Qitchen dapat berjalan dengan baik dan terintegrasi.

**Prioritas:** Tinggi

### 4.9.2 Urutan Stimulus dan Respons

| No | Stimulus | Respons |
|----|----------|---------|
| REQ-1 | Admin membuka dashboard admin | Sistem menampilkan ringkasan informasi operasional restoran |
| REQ-2 | Admin mengelola data menu | Sistem menampilkan halaman manajemen menu dan menyimpan perubahan data yang dilakukan |
| REQ-3 | Admin mengelola kategori menu | Sistem menampilkan halaman manajemen kategori dan memperbarui data kategori |
| REQ-4 | Admin mengelola promosi dan voucher | Sistem menyimpan perubahan data promosi dan voucher yang dilakukan admin |
| REQ-5 | Admin mengelola konten halaman publik | Sistem menyimpan perubahan data artikel, informasi restoran, atau informasi kontak yang dilakukan admin |
| REQ-6 | Admin mengelola data pengguna | Sistem menampilkan daftar pengguna dan memperbarui data sesuai perubahan yang dilakukan |
| REQ-7 | Admin memantau data pesanan | Sistem menampilkan daftar pesanan beserta statusnya |
| REQ-8 | Admin memantau data delivery | Sistem menampilkan daftar pengiriman dan status pengiriman |
| REQ-9 | Admin melakukan pencarian atau filter data | Sistem menampilkan data sesuai kriteria yang dipilih |

### 4.9.3 Kebutuhan Fungsional

| ID | Kebutuhan Fungsional |
|----|----------------------|
| FR-AD-01 | Sistem harus menyediakan dashboard admin |
| FR-AD-02 | Sistem harus menampilkan ringkasan data operasional pada dashboard admin |
| FR-AD-03 | Sistem harus memungkinkan admin menambah data menu |
| FR-AD-04 | Sistem harus memungkinkan admin mengubah data menu |
| FR-AD-05 | Sistem harus memungkinkan admin menghapus data menu |
| FR-AD-06 | Sistem harus memungkinkan admin menambah kategori menu |
| FR-AD-07 | Sistem harus memungkinkan admin mengubah kategori menu |
| FR-AD-08 | Sistem harus memungkinkan admin menghapus kategori menu |
| FR-AD-09 | Sistem harus memungkinkan admin membuat promosi baru |
| FR-AD-10 | Sistem harus memungkinkan admin mengubah data promosi |
| FR-AD-11 | Sistem harus memungkinkan admin menghapus data promosi |
| FR-AD-12 | Sistem harus memungkinkan admin membuat voucher baru |
| FR-AD-13 | Sistem harus memungkinkan admin mengubah data voucher |
| FR-AD-14 | Sistem harus memungkinkan admin menghapus data voucher |
| FR-AD-15 | Sistem harus memungkinkan admin mengelola konten halaman publik seperti artikel, informasi restoran, dan kontak |
| FR-AD-16 | Sistem harus menampilkan konten halaman publik kepada pengunjung website |
| FR-AD-17 | Sistem harus menampilkan daftar pengguna yang terdaftar pada sistem |
| FR-AD-18 | Sistem harus memungkinkan admin melihat detail data pengguna |
| FR-AD-19 | Sistem harus menampilkan daftar pesanan customer |
| FR-AD-20 | Sistem harus menampilkan status setiap pesanan |
| FR-AD-21 | Sistem harus menampilkan daftar delivery order |
| FR-AD-22 | Sistem harus menampilkan status pengiriman setiap delivery order |
| FR-AD-23 | Sistem harus menyediakan fitur pencarian data |
| FR-AD-24 | Sistem harus menyediakan fitur filter data |
| FR-AD-25 | Sistem harus menampilkan pesan kesalahan apabila terjadi kegagalan penyimpanan atau pembaruan data |

---

# 5. Kebutuhan Nonfungsional Lainnya

## 5.1 Kebutuhan Performa

Sistem Qitchen harus mampu memberikan performa yang memadai untuk mendukung operasional restoran secara realtime dan responsif.

Kebutuhan performa sistem meliputi:
- Waktu respon halaman utama tidak melebihi 3 detik pada kondisi penggunaan normal
- Sistem harus mampu menangani minimal 50 pengguna aktif secara bersamaan
- Status pembayaran harus diperbarui secara otomatis setelah menerima callback dari Midtrans Sandbox
- Status pesanan pada Kitchen Board dan Order Tracking harus diperbarui secara berkala melalui mekanisme polling
- Sistem harus mampu menghasilkan laporan transaksi dalam format PDF tanpa menyebabkan kegagalan sistem
- Sistem harus mampu menyimpan dan mengambil data transaksi dari database secara konsisten

## 5.2 Kebutuhan Keamanan Operasional

Sistem Qitchen harus memiliki mekanisme untuk mengurangi risiko kehilangan data, kerusakan sistem, maupun kesalahan operasional pengguna.

Kebutuhan keamanan operasional meliputi:
- Data sistem harus disimpan pada database terpusat
- Sistem harus melakukan validasi data sebelum proses penyimpanan
- Sistem harus menampilkan pesan kesalahan apabila terjadi kegagalan proses
- Sistem harus menjaga konsistensi data transaksi dan pesanan
- Sistem harus mencegah terjadinya duplikasi data pesanan akibat proses checkout berulang
- Sistem harus membatasi akses fitur sesuai hak akses pengguna
- Sistem harus memungkinkan administrator melakukan pemantauan data operasional sistem

## 5.3 Kebutuhan Keamanan Sistem

Sistem Qitchen harus menjaga keamanan data pengguna dan membatasi akses terhadap fitur sistem berdasarkan hak akses masing-masing pengguna.

Kebutuhan keamanan sistem meliputi:
- Sistem harus menyediakan autentikasi menggunakan email dan password
- Sistem harus mendukung autentikasi menggunakan Google Login
- Sistem harus menyediakan fitur reset password melalui email verification
- Password pengguna harus disimpan dalam bentuk terenkripsi (hashed password)
- Sistem harus menggunakan HTTPS untuk komunikasi data
- Sistem harus menerapkan role-based access control (RBAC)
- Sistem harus membatasi akses halaman berdasarkan peran pengguna
- Sistem harus melindungi data pengguna dari akses yang tidak sah
- Sistem harus melakukan validasi input pada sisi client dan server

## 5.4 Atribut Kualitas Perangkat Lunak

Sistem Qitchen dirancang dengan memperhatikan berbagai atribut kualitas perangkat lunak untuk memastikan sistem dapat digunakan secara efektif dan mudah dikembangkan.

### Usability
- Sistem harus mudah digunakan oleh pengguna tanpa memerlukan pelatihan khusus
- Navigasi sistem harus sederhana dan konsisten

### Reliability
- Sistem harus mampu berjalan secara stabil selama operasional restoran berlangsung
- Sistem harus menjaga konsistensi data transaksi dan pesanan

### Maintainability
- Sistem harus dikembangkan menggunakan struktur kode yang terorganisir
- Sistem harus mudah dipelihara dan dikembangkan pada masa mendatang

### Compatibility
- Sistem harus dapat digunakan pada browser modern seperti Google Chrome, Mozilla Firefox, Microsoft Edge, dan Safari

### Portability
- Sistem harus dapat diakses melalui perangkat desktop maupun mobile tanpa instalasi aplikasi tambahan

### Scalability
- Sistem harus memungkinkan penambahan fitur baru pada versi pengembangan berikutnya

## 5.5 Aturan Bisnis

Sistem Qitchen menerapkan beberapa aturan bisnis untuk memastikan proses operasional restoran berjalan dengan baik.

1. Guest Customer dapat melakukan pemesanan tanpa login akun
2. Member Customer harus login untuk menggunakan voucher personal, referral system, dan melihat riwayat pesanan
3. Setiap pesanan harus memiliki nomor order yang unik
4. Pesanan hanya dapat diproses oleh Kitchen Staff setelah pembayaran berhasil diverifikasi
5. Customer hanya dapat menggunakan voucher yang masih berlaku
6. Courier hanya dapat menerima delivery order yang tersedia
7. Satu delivery order hanya dapat ditangani oleh satu courier pada waktu yang sama
8. Admin memiliki hak akses penuh terhadap pengelolaan data sistem
9. Cashier dapat membuat pesanan manual untuk customer yang melakukan pemesanan secara langsung
10. Customer dapat memantau status pesanan melalui fitur Order Tracking
11. Riwayat pesanan hanya tersedia untuk Member Customer yang memiliki akun

---

# 6. Kebutuhan Lainnya

Bab ini menjelaskan kebutuhan tambahan yang mendukung pengembangan, implementasi, deployment, dan pemeliharaan sistem Qitchen di luar kebutuhan fungsional maupun non-fungsional yang telah dijelaskan sebelumnya.

## 6.1 Kebutuhan Database

Sistem Qitchen menggunakan database relasional untuk menyimpan seluruh data operasional restoran secara terpusat.

Kebutuhan database meliputi:
- Database menggunakan MySQL
- Database digunakan untuk menyimpan data pengguna, menu, kategori, transaksi, voucher, referral, pesanan, delivery, artikel, dan laporan sistem
- Database dirancang menggunakan relasi antar tabel untuk menjaga konsistensi data
- Sistem harus mendukung proses Create, Read, Update, dan Delete (CRUD) terhadap data yang diperlukan
- Database harus menjaga integritas data melalui penggunaan primary key dan foreign key
- Database harus mampu menyimpan riwayat transaksi dan aktivitas sistem yang diperlukan
- Backup database dapat dilakukan secara berkala untuk mengurangi risiko kehilangan data

### 6.1.1 Conceptual Data Model (CDM)

Conceptual Data Model (CDM) digunakan untuk menggambarkan entitas utama dan hubungan antar entitas pada sistem Qitchen secara konseptual tanpa menampilkan detail implementasi basis data.

CDM mencakup entitas utama seperti:
- Users
- Categories
- Menus
- Orders
- Order Items
- Payments
- Deliveries
- Vouchers
- User Vouchers
- Referrals
- Articles

> **Catatan:** Diagram CDM terdapat pada file `docs/Qitchen-CDM.png` (gambar terpisah).

### 6.1.2 Physical Data Model (PDM)

Physical Data Model (PDM) digunakan untuk menggambarkan struktur fisik database yang akan diimplementasikan pada sistem Qitchen, termasuk atribut, tipe data, panjang data, primary key, foreign key, dan constraint lainnya.

PDM mencakup seluruh tabel yang digunakan pada database sistem Qitchen beserta relasi antar tabel.

> **Catatan:** Diagram PDM terdapat pada file `docs/Qitchen-PDM.png` (gambar terpisah).

## 6.2 Kebutuhan Deployment Sistem

Sistem Qitchen dirancang untuk diimplementasikan sebagai web application yang dapat diakses melalui jaringan internet maupun jaringan lokal.

Kebutuhan deployment meliputi:
- Web Server: Apache atau Nginx
- Backend Framework: Laravel
- Database Server: MySQL
- PHP versi 8.x atau yang kompatibel dengan Laravel
- Browser modern sebagai media akses sistem
- Repository Git untuk penyimpanan source code

Deployment sistem dapat dilakukan pada:
- Local Development Server
- Shared Hosting
- Virtual Private Server (VPS)
- Cloud Hosting

## 6.3 Kebutuhan Responsif

Sistem Qitchen harus mampu menyesuaikan tampilan antarmuka pada berbagai ukuran layar perangkat.

Kebutuhan responsif meliputi:
- Tampilan dapat digunakan pada desktop, tablet, dan smartphone
- Layout sistem harus menyesuaikan ukuran layar secara otomatis
- Seluruh fitur utama harus tetap dapat digunakan pada perangkat mobile
- Sistem harus mendukung browser modern seperti Google Chrome, Mozilla Firefox, Microsoft Edge, dan Safari
- Sistem menggunakan pendekatan responsive web design berbasis TailwindCSS

## 6.4 Pengembangan Sistem di Masa Depan

Sistem Qitchen masih memungkinkan untuk dikembangkan lebih lanjut pada versi berikutnya.

Pengembangan yang dapat dilakukan antara lain:
1. Integrasi payment gateway produksi
2. Pengembangan aplikasi mobile Android dan iOS
3. Integrasi layanan delivery pihak ketiga
4. Implementasi notifikasi realtime menggunakan WebSocket
5. Dukungan multi-branch restaurant management
6. Dashboard analitik penjualan yang lebih lengkap
7. Implementasi Artificial Intelligence (AI) untuk rekomendasi menu
8. Integrasi loyalty program dan membership yang lebih kompleks
9. **Customer Display Board & TTS Order Calling** (Fase 2)

## 6.5 Repository GitHub

Sistem Qitchen menggunakan repository Git sebagai media penyimpanan source code, dokumentasi, dan version control selama proses pengembangan.

Repository digunakan untuk:
- Penyimpanan source code
- Dokumentasi proyek
- Kolaborasi pengembangan
- Version control

**Link repository:** [https://github.com/Fachriditya/project-dev-qitchen-2026](https://github.com/Fachriditya/project-dev-qitchen-2026)

## 6.6 Dokumentasi Testing

Pengujian sistem dilakukan untuk memastikan seluruh fitur pada Qitchen berjalan sesuai kebutuhan yang telah ditentukan.

Metode pengujian yang digunakan meliputi:

### Black Box Testing
Digunakan untuk menguji fungsi sistem berdasarkan input dan output yang dihasilkan tanpa melihat kode program.

### Integration Testing
Digunakan untuk menguji integrasi antar modul sistem seperti pembayaran, kitchen workflow, delivery management, dan autentikasi.

Dokumentasi hasil pengujian akan dilampirkan pada laporan pengujian sistem.

### User Acceptance Testing (UAT)
Digunakan untuk memastikan sistem telah memenuhi kebutuhan pengguna dan sesuai dengan proses bisnis restoran yang dirancang.

### Responsive Testing
Digunakan untuk memastikan tampilan sistem dapat berjalan dengan baik pada berbagai ukuran layar perangkat.

---

# Daftar Pustaka

1. IEEE. (1998). *IEEE Recommended Practice for Software Requirements Specifications (IEEE Std 830-1998)*.
2. Laravel Documentation. *Laravel 11.x Official Documentation*. https://laravel.com/docs
3. TailwindCSS Documentation. https://tailwindcss.com/docs
4. MySQL Documentation. https://dev.mysql.com/doc
5. Laravel Breeze Documentation. https://laravel.com/docs/starter-kits
6. Laravel Socialite Documentation. https://laravel.com/docs/socialite
7. Google OAuth Documentation. https://developers.google.com/identity/protocols/oauth2
8. Laravel Mail Documentation. https://laravel.com/docs/mail
9. Midtrans Documentation. https://docs.midtrans.com
10. LeafletJS Documentation. https://leafletjs.com/reference.html
11. OpenStreetMap Documentation. https://wiki.openstreetmap.org
12. Laravel DomPDF Documentation. https://github.com/barryvdh/laravel-dompdf
13. Dokumentasi perancangan sistem Qitchen

---

# Lampiran

Dokumen ini memiliki beberapa lampiran berupa diagram dan gambar desain yang disimpan sebagai file terpisah di direktori `docs/`. Berikut daftar lampiran:

## Lampiran A — Sitemap

> File: `docs/Qitchen-Sitemap.png`

## Lampiran B — Use Case Diagram

> File: `docs/Qitchen-UseCase.png`

## Lampiran C — User Flow

### Lampiran C.1 — Customer Flow
> File: `docs/Qitchen-UserFlow/`

### Lampiran C.2 — Cashier Flow
> File: `docs/Qitchen-UserFlow/`

### Lampiran C.3 — Kitchen Flow
> File: `docs/Qitchen-UserFlow/`

### Lampiran C.4 — Courier Flow
> File: `docs/Qitchen-UserFlow/`

### Lampiran C.5 — Admin Flow
> File: `docs/Qitchen-UserFlow/`

## Lampiran D — Low Fidelity Wireframe

### Lampiran D.1 — Public Pages
- D.1.1 Home Page
- D.1.2 Articles Page
- D.1.3 Article Detail Page
- D.1.4 About Page
- D.1.5 Contact Page
- D.1.6 Login Page
- D.1.7 Register Page

### Lampiran D.2 — Guest Customer Pages
- D.2.1 Menu Catalog
- D.2.2 Menu Detail
- D.2.3 Cart
- D.2.4 Checkout
- D.2.5 Payment
- D.2.6 Tracking Order

### Lampiran D.3 — Member Customer Pages
- D.3.1 Menu Catalog
- D.3.2 Menu Detail
- D.3.3 Cart
- D.3.4 Checkout
- D.3.5 Payment
- D.3.6 Tracking Order
- D.3.7 Profile Page
- D.3.8 Order History
- D.3.9 Voucher Page
- D.3.10 Referral Page

### Lampiran D.4 — Cashier Pages
- D.4.1 Transaction Monitoring
- D.4.2 Manual Order Input
- D.4.3 Manual Order Payment
- D.4.4 Pickup Counter

### Lampiran D.5 — Kitchen Board
- D.5.1 Kitchen Board

### Lampiran D.6 — Display Board (Fase 2)
- D.6.1 Display Board

### Lampiran D.7 — Courier Pages
- D.7.1 Delivery Offers
- D.7.2 My Deliveries
- D.7.3 Delivery Detail

### Lampiran D.8 — Admin Pages
- D.8.1 Admin Dashboard
- D.8.2 Menu Management
- D.8.3 Category Management
- D.8.4 User Management
- D.8.5 Voucher Management
- D.8.6 Transaction Monitoring
- D.8.7 Delivery Monitoring
- D.8.8 Reports Page
- D.8.9 Article Management

> File: `docs/Qitchen-LowFidelity/`

## Lampiran E — High Fidelity Wireframe

### Lampiran E.1 — Public Pages
- E.1.1 Home Page
- E.1.2 Articles Page
- E.1.3 Article Detail Page
- E.1.4 About Page
- E.1.5 Contact Page
- E.1.6 Login Page
- E.1.7 Register Page

### Lampiran E.2 — Guest Customer Pages
- E.2.1 Menu Catalog
- E.2.2 Menu Detail
- E.2.3 Cart
- E.2.4.1 Checkout - Delivery
- E.2.4.2 Checkout - Takeaway
- E.2.4.3 Checkout - Dine In
- E.2.5 Payment
- E.2.6 Tracking Order

### Lampiran E.3 — Member Customer Pages
- E.3.1 Menu Catalog
- E.3.2 Menu Detail
- E.3.3 Cart
- E.3.4.1 Checkout - Delivery
- E.3.4.2 Checkout - Takeaway
- E.3.4.3 Checkout - Dine In
- E.3.5 Payment
- E.3.6 Tracking Order
- E.3.7 Profile Page
- E.3.8 Order History
- E.3.9 Voucher Page
- E.3.10 Referral Page

### Lampiran E.4 — Cashier Pages
- E.4.1 Transaction Monitoring
- E.4.2 Manual Order Input
- E.4.3 Manual Order Payment
- E.4.4 Pickup Counter

### Lampiran E.5 — Kitchen Board
- E.5.1 Kitchen Board

### Lampiran E.6 — Display Board (Fase 2)
- E.6.1 Display Board

### Lampiran E.7 — Courier Pages
- E.7.1 Delivery Offers
- E.7.2.1 My Deliveries - Active
- E.7.2.2 My Deliveries - Accepted
- E.7.2.3 My Deliveries - History
- E.7.3 Delivery Detail

### Lampiran E.8 — Admin Pages
- E.8.1 Admin Dashboard
- E.8.2 Menu Management
- E.8.3 Category Management
- E.8.4 User Management
- E.8.5 Voucher Management
- E.8.6 Transaction Monitoring
- E.8.7 Delivery Monitoring
- E.8.8 Reports Page
- E.8.9 Article Management

> File: `docs/Qitchen-HighFidelity/`

## Lampiran F — Conceptual Data Model (CDM)

> File: `docs/Qitchen-CDM.png`

## Lampiran G — Physical Data Model (PDM)

> File: `docs/Qitchen-PDM.png`

---

*Dokumen SRS-Qitchen.md — Versi 1.0 — 2026*

*Dokumen ini selaras dengan [PRD-Qitchen.md](./PRD-Qitchen.md), [BRD-Qitchen.md](./BRD-Qitchen.md), dan implementasi codebase.*