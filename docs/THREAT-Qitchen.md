# Model Ancaman & Spesifikasi Keamanan — Qitchen 2026

> **Status:** Aktif  
**Klasifikasi:** Internal  
**Standar:** OWASP Top 10:2021, OWASP ASVS 4.0  
**Terakhir Diperbarui:** 2026

---

## 1. Ikhtisar Permukaan Serangan

```
┌─────────────────────────────────────────────────────────────┐
│                   PERMUKAAN SERANGAN                        │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [Pengguna Publik]                                          │
│    ├── /menu, /articles              → Baca-saja, tanpa auth│
│    ├── Daftar / Masuk / OTP         → PII, kredensial      │
│    └── /track/{order}               → Tebak nomor pesanan  │
│                                                             │
│  [Pelanggan Terautentikasi]                                  │
│    ├── Checkout + Bayar (Midtrans)  → Data transaksi       │
│    ├── Pakai voucher / referral     → Penyalahgunaan diskon│
│    └── Perbarui profil              → Modifikasi PII       │
│                                                             │
│  [Peran Staf: Kasir, Dapur, Kurir]                         │
│    ├── POS Pesanan Manual           → Manipulasi harga     │
│    ├── Perbarui status dapur        → Penyalahgunaan mesin │
│    └── Terima/selesai pengiriman    → Pengiriman palsu     │
│                                                             │
│  [Admin]                                                    │
│    ├── CRUD penuh (menu, user, dll) → Akses istimewa      │
│    ├── Ekspor laporan (PDF)         → Ekfiltrasi data      │
│    └── Manajemen pengguna           → Eskalasi hak akses   │
│                                                             │
│  [Integrasi Eksternal]                                      │
│    ├── Webhook Midtrans (HTTP POST) → Panggilan balik palsu│
│    ├── Panggilan balik Google OAuth → Validasi token CSRF  │
│    └── SMTP Mail                    → Injeksi email        │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Penilaian Risiko OWASP Top 10

### A01: Kerusakan Kontrol Akses

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Eskalasi hak akses melalui manipulasi peran | Pengguna mengubah peran di pembaruan profil | Kritis | Rendah |
| IDOR — Akses pesanan pengguna lain | Tebak `order_id` di URL | Tinggi | Sedang |
| Bypass rute admin | Akses langsung `/admin/dashboard` | Kritis | Rendah |

**Mitigasi:**
- ✅ Middleware berbasis peran di SEMUA rute admin/staff (`IsAdmin`, `IsCashier`, `IsKitchen`, `IsCourier`)
- ✅ Grup rute dengan prefiks + rantai middleware
- ✅ Pelacakan pesanan menggunakan `order_number` (20 karakter unik), bukan `order_id` berurutan
- ❌ Hilang: Middleware `IsCustomer` tidak pernah diterapkan ke rute pelanggan (hanya `auth`)
- ❌ Hilang: Tidak ada pembatasan kecepatan pada upaya login admin

### A02: Kegagalan Kriptografi

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Kata sandi disimpan dalam teks biasa | Pelanggaran basis data | Kritis | Rendah |
| Prediktabilitas token OTP | Generasi acak lemah | Tinggi | Sedang |
| ID Sesi di URL | Kebocoran Log/Referrer | Sedang | Rendah |

**Mitigasi:**
- ✅ Kata sandi di-hash melalui Laravel `Hasher::make()` (bcrypt)
- ✅ HTTPS dipaksakan di produksi (`URL::forceScheme('https')`)
- ✅ Cookie sesi menggunakan flag `HttpOnly` + `Secure` (bawaan Laravel)
- ✅ Token CSRF di semua formulir yang mengubah state

### A03: Injeksi (SQLi, XSS, Injeksi Perintah)

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Injeksi SQL melalui bidang pencarian | `?search=istilah' OR 1=1--` | Kritis | Rendah |
| XSS Tersimpan melalui deskripsi menu | Admin memposting JS berbahaya | Tinggi | Rendah |
| XSS Tercermin melalui pesan error | Parameter URL dipantulkan tanpa sanitasi | Sedang | Sedang |

**Mitigasi:**
- ✅ Eloquent ORM menggunakan kueri terparameterisasi (tanpa konkatenasi SQL mentah)
- ✅ Sintaks Blade `{{ }}` secara otomatis menghindari HTML (pencegahan XSS)
- ✅ Aturan validasi input di semua metode `store`/`update` (Form Request Laravel)
- ✅ Validasi unggahan file (mimes, ukuran maks) untuk gambar menu & thumbnail

### A04: Desain Tidak Aman

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Penyalahgunaan hadiah referral melalui referal diri | Buat akun → referal diri sendiri | Tinggi | Sedang |
| Penggunaan voucher tak terbatas | Gunakan ulang kode voucher yang sama | Tinggi | Rendah |
| Manipulasi harga saat checkout | Ubah harga di sesi/keranjang | Kritis | Sedang |

**Mitigasi:**
- ✅ Verifikasi OTP diperlukan sebelum mengakses akun (mencegah pendaftaran bot)
- ✅ Bendera `is_used` pada voucher mencegah penggunaan ulang
- ✅ Item keranjang mereferensi `menu_price` dari basis data saat checkout, bukan harga sesi
- ✅ Transaksi menggunakan `DB::beginTransaction()` — rollback jika ada langkah gagal
- ❌ Hilang: Pembatasan kecepatan berbasis IP pada pendaftaran

### A05: Salah Konfigurasi Keamanan

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Mode debug diaktifkan di produksi | `.env` `APP_DEBUG=true` | Kritis | Rendah |
| Kredensial default | admin@qitchen.test / password | Kritis | Sedang |
| Salah konfigurasi CORS | Akses API lintas asal | Sedang | Rendah |

**Mitigasi:**
- ✅ `.env.example` memiliki `APP_DEBUG=false` sebagai default produksi
- ✅ Seeder membuat pengguna dengan kredensial yang diketahui (harus diubah di produksi)
- ❌ Hilang: Tidak ada pemeriksaan otomatis untuk mode debug di pipeline deployment
- ❌ Hilang: Tidak ada validasi `APP_ENV=production` di boot()

### A06: Komponen Rentan & Usang

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Versi Laravel dengan CVE yang diketahui | Ketergantungan composer usang | Bervariasi | Sedang |
| Kerentanan SDK Midtrans | Paket midtrans-php usang | Tinggi | Rendah |

**Mitigasi:**
- ✅ `composer.json` menentukan `^13.8` (Laravel) dan `^2.6` (Midtrans)
- ✅ `composer update` rutin direkomendasikan
- ❌ Hilang: Tidak ada konfigurasi Dependabot / Renovate

### A07: Kegagalan Identifikasi & Autentikasi

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Brute-force login | POST berulang ke `/login` | Tinggi | Sedang |
| Intersepsi OTP | Kompromi akun email | Tinggi | Rendah |
| Fiksasi sesi | Penyerang mengatur ID sesi | Sedang | Rendah |

**Mitigasi:**
- ✅ OTP diperlukan setelah pendaftaran sebelum mengakses akun
- ✅ Reset password melalui OTP (bukan tautan reset yang dapat diprediksi)
- ✅ Regenerasi sesi bawaan Laravel saat login
- ❌ Hilang: Pembatasan kecepatan pada titik akhir login (`throttle:5,1`)
- ❌ Hilang: Penguncian akun setelah N upaya gagal

### A08: Kegagalan Integritas Perangkat Lunak & Data

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Instalasi paket berbahaya | Serangan rantai pasokan via `composer install` | Kritis | Rendah |
| Tebakan kode voucher | Kode berurutan atau dapat diprediksi | Tinggi | Sedang |

**Mitigasi:**
- ✅ Kode voucher ditentukan oleh admin (tidak dapat diprediksi jika admin memilih dengan baik)
- ✅ Kode referral menggunakan format `QITCHEN-XXXX-YYYY` dengan akhiran acak
- ❌ Hilang: `composer.lock` sudah dikomit (baik) tetapi tanpa verifikasi integritas

### A09: Kegagalan Pencatatan & Pemantauan Keamanan

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Brute force tidak terdeteksi | Tidak ada pencatatan login gagal | Sedang | Sedang |
| Penipuan webhook | Tanda tangan tidak valid tidak dipantau | Tinggi | Rendah |

**Mitigasi:**
- ✅ Webhook mencatat semua upaya tanda tangan tidak valid melalui `Log::warning()`
- ✅ Saluran log bawaan Laravel (`stack`) menulis ke file harian
- ❌ Hilang: Log audit login gagal
- ❌ Hilang: Tidak ada pemantauan terpusat (Sentry, Grafana)

### A10: Pemalsuan Permintaan Sisi Server (SSRF)

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| Pemindaian jaringan internal | Admin ekspor PDF dengan URL internal | Sedang | Rendah |
| Akses titik akhir metadata | Metadata penyedia cloud | Kritis | Rendah |

**Mitigasi:**
- ✅ Tidak ada URL yang disediakan pengguna yang diambil oleh server
- ✅ DomPDF merender dari tampilan Blade lokal, bukan URL eksternal

---

## 3. Matriks Kontrol Akses (RBAC)

### Izin berdasarkan Peran

| Fitur / Rute | Tamu | Pelanggan | Kasir | Dapur | Kurir | Admin |
|--------------|------|-----------|-------|-------|-------|-------|
| `/menu` (jelajah) | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `/articles` (publik) | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `/cart` (kelola) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/checkout` (proses) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/track/{order}` | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| `/profile` (perbarui) | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/history` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/vouchers` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/referral` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/cashier/*` (POS) | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `/kitchen/*` (papan) | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `/courier/*` (kirim) | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `/admin/dashboard` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/menus` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/categories` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/users` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/vouchers` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/transactions` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/deliveries` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/reports` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/articles` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |

### Nilai Enum Peran (Basis Data)

| Peran | `users.role` | Kelas Middleware | Grup Rute |
|-------|-------------|------------------|-----------|
| Admin | `admin` | `IsAdmin` | `/admin/*` |
| Kasir | `cashier` | `IsCashier` | `/cashier/*` |
| Dapur | `kitchen` | `IsKitchen` | `/kitchen/*` |
| Kurir | `courier` | `IsCourier` | `/courier/*` |
| Pelanggan | `customer` | `IsCustomer` | *(tidak digunakan di rute)* |

---

## 4. Tinjauan Kode Terkait Keamanan

### Temuan Severitas Tinggi (Telah Diperbaiki)

| ID | Temuan | Berkas | Severitas | Perbaikan |
|----|--------|--------|-----------|-----------|
| SEC-001 | Ketidakcocokan tipe `status` → `order_status` pada referral | `app/Models/Order.php` | 🔴 Kritis | Diubah ke `order_status` |
| SEC-002 | Webhook mencari `REF-BONUS` tapi seeder membuat `REF-REWARD-2026` | `MidtransWebhookController.php` | 🔴 Kritis | Diubah ke `REF-REWARD-2026` |
| SEC-003 | Alamat pengiriman tidak disimpan saat checkout | `CheckoutController.php` | 🔴 Kritis | Ditambahkan pembuatan catatan Pengiriman |
| SEC-004 | Validasi voucher tidak menerima tipe `bogo` | `Admin/VoucherController.php` | 🟡 Tinggi | Ditambahkan `bogo` ke aturan validasi |
| SEC-005 | Validator peran menyertakan `super_admin` yang tidak ada di enum DB | `Admin/UserController.php` | 🟡 Tinggi | Dihapus peran tidak valid |

---

## 5. Daftar Periksa Verifikasi Keamanan (Pra-Rilis)

### Autentikasi & Sesi

- [x] Kata sandi di-hash dengan bcrypt (bawaan Laravel)
- [x] Google OAuth menggunakan parameter state (pencegahan CSRF)
- [x] Sesi diregenerasi saat login
- [x] Cookie HttpOnly + Secure
- [ ] Pembatasan kecepatan pada login (middleware `throttle:5,1`)
- [ ] Penguncian akun setelah 5 upaya gagal
- [ ] Timeout sesi setelah tidak aktif (dapat dikonfigurasi)

### Kontrol Akses

- [x] Semua rute admin di belakang middleware `isAdmin`
- [x] Semua rute kasir di belakang middleware `isCashier`
- [x] Semua rute dapur di belakang middleware `isKitchen`
- [x] Semua rute kurir di belakang middleware `isCourier`
- [ ] Terapkan middleware `isCustomer` ke rute khusus pelanggan (profil, voucher)
- [ ] Verifikasi IDOR: Pelacakan pesanan harus memeriksa kepemilikan `user_id`

### Validasi Input

- [x] Semua input formulir divalidasi melalui `$request->validate()`
- [x] Unggahan file dibatasi untuk gambar (mimes:jpeg,png,jpg)
- [x] Ukuran file dibatasi hingga 2MB
- [ ] Tambahkan pemeriksaan penyandian entitas HTML untuk konten yang disimpan (artikel, deskripsi)

### Perlindungan CSRF

- [x] `@csrf` di semua formulir Blade
- [x] Titik akhir webhook dikecualikan dari CSRF (menggunakan tanda tangan SHA-512 sebagai gantinya)
- [x] Titik akhir API-stateless menggunakan auth berbasis token

### Keamanan Pembayaran

- [x] Verifikasi tanda tangan webhook (SHA-512)
- [x] Pemeriksaan idempotensi (mencegah pemrosesan ganda)
- [x] Atomisitas transaksi (`DB::beginTransaction/commit/rollback`)
- [ ] Kredensial produksi Midtrans disimpan di `.env`, tidak pernah di kode
- [ ] Kepatuhan PCI: Data kartu tidak pernah menyentuh server Qitchen (ditangani oleh Midtrans Snap)

### Basis Data

- [x] Soft delete di semua tabel resource
- [x] Kendala foreign key diaktifkan
- [x] Tidak ada kueri SQL mentah (Eloquent saja)
- [ ] Strategi pencadangan basis data didokumentasikan
- [ ] `.env` dikecualikan dari kontrol versi

### Infrastruktur

- [x] HTTPS dipaksakan di produksi
- [x] `APP_DEBUG=false` di produksi
- [ ] Web Application Firewall (WAF) — Cloudflare atau sejenisnya
- [ ] Fail2ban untuk perlindungan SSH/brute force
- [ ] Pembaruan ketergantungan rutin (`composer update` / Dependabot)

### Pencatatan & Pemantauan

- [x] Tanda tangan webhook tidak valid dicatat
- [ ] Upaya login gagal dicatat
- [ ] Tindakan admin dicatat (jejak audit)
- [ ] Pelacakan error (integrasi Sentry / Flare)

> **Legenda:** `[x]` = Terimplementasi | `[ ]` = Belum terimplementasi (tugas untuk kesiapan produksi)

---

## 6. Klasifikasi & Sensitivitas Data

| Tipe Data | Klasifikasi | Penyimpanan | Enkripsi |
|-----------|-------------|-------------|----------|
| Kata sandi | Kritis | `users.password` (hash bcrypt) | Saat disimpan (di-hash), Saat transit (HTTPS) |
| Email | Tinggi | `users.email` | Saat disimpan (teks biasa — diperlukan untuk auth), Saat transit (HTTPS) |
| Telepon | Tinggi | `users.phone` | Saat disimpan (teks biasa), Saat transit (HTTPS) |
| Alamat + Koordinat | Tinggi | Tabel `deliveries` | Saat disimpan (teks biasa), Saat transit (HTTPS) |
| ID transaksi pembayaran | Kritis | `payments.midtrans_transaction_id` | Saat disimpan (teks biasa), Saat transit (HTTPS) |
| Data pesanan | Sedang | `orders`, `order_items` | Saat disimpan (teks biasa), Saat transit (HTTPS) |
| ID Sesi | Tinggi | Cookie browser | HttpOnly + Secure + SameSite |
| Token Google OAuth | Tinggi | Sesi (sementara) | Saat transit (HTTPS) |

---

## 7. Rencana Respons Insiden (Ringkas)

| Severitas | Definisi | Waktu Respons | Tindakan |
|-----------|----------|---------------|----------|
| 🔴 Kritis | Pelanggaran data, kompromi pembayaran, akses admin tidak sah | Segera (1 jam) | Matikan aplikasi, reset semua kredensial, beri tahu pengguna yang terkena dampak, analisis forensik |
| 🟡 Tinggi | Pengambilalihan akun, penyalahgunaan voucher, XSS persisten | 24 jam | Nonaktifkan akun yang terkena dampak, rollback perubahan berbahaya, perbaiki kerentanan |
| 🟢 Sedang | Bypass pembatasan kecepatan, pendaftaran spam, IDOR tanpa akses data | 72 jam | Terapkan kontrol keamanan yang hilang, audit log selama 7 hari terakhir |
| 🔵 Rendah | Header hilang, masalah konfigurasi minor | Sprint berikutnya | Tambahkan ke backlog, perbaiki di rilis mendatang |

---

*Kembali ke [docs/](./)*