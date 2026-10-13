# Spesifikasi API — Qitchen 2026

> **Status:** Aktif  
**URL Dasar (Dev):** `http://localhost:8000`  
**URL Dasar (Prod):** `https://qitchen.example.com`  
**Tipe Konten:** `application/json`  
**Standar:** RESTful, Laravel Resource Controllers  
**Terakhir Diperbarui:** 2026

---

## 1. Konvensi Kode Status HTTP

| Kode | Arti | Penggunaan |
|------|------|------------|
| `200 OK` | Permintaan berhasil | GET, PUT, PATCH |
| `201 Created` | Resource berhasil dibuat | POST (simpan) |
| `302 Found` | Pengalihan | Setelah pengiriman formulir |
| `400 Bad Request` | Error validasi / Input salah | `ValidationException` Laravel |
| `401 Unauthorized` | Belum terautentikasi | Sesi/token tidak ada atau tidak valid |
| `403 Forbidden` | Terautentikasi tapi peran salah | Penolakan middleware (`isAdmin`, dll.) |
| `404 Not Found` | Resource tidak ditemukan | `ModelNotFoundException` |
| `422 Unprocessable Entity` | Gagal validasi semantik | Transisi status tidak valid |
| `429 Too Many Requests` | Batas kecepatan terlampaui | Middleware `throttle` |
| `500 Internal Server Error` | Error server | Exception tidak tertangani |

---

## 2. Titik Akhir API

### 2.1 Titik Akhir Publik (Tanpa Auth)

| Method | Path | Deskripsi | Batas Kecepatan |
|--------|------|-----------|----------------|
| `GET` | `/` | Halaman utama | 60/menit |
| `GET` | `/menu` | Daftar semua menu tersedia | 60/menit |
| `GET` | `/menu/{id}` | Detail menu tunggal | 60/menit |
| `GET` | `/articles` | Daftar artikel terbit | 60/menit |
| `GET` | `/articles/{slug}` | Detail artikel tunggal | 60/menit |
| `GET` | `/track/{order_number}` | Lacak pesanan berdasarkan nomor | 30/menit |
| `GET` | `/about` | Halaman tentang | 60/menit |
| `GET` | `/contact` | Halaman kontak | 60/menit |
| `POST` | `/contact` | Kirim formulir kontak | 5/menit |
| `POST` | `/webhook/midtrans` | Panggilan balik pembayaran Midtrans | Tanpa batas (disarankan whitelist IP) |

**Contoh: Lacak Pesanan**

```http
GET /track/QTC-ABC123 HTTP/1.1
Host: localhost:8000
```

```json
// Respons 200
{
  "order_number": "QTC-ABC123",
  "order_type": "delivery",
  "order_status": "on_delivery",
  "order_total": 85000,
  "items": [
    {
      "menu_name": "Nasi Goreng Spesial",
      "quantity": 2,
      "item_subtotal": 50000
    }
  ],
  "delivery": {
    "recipient_name": "Budi",
    "delivery_address": "Jl. Merdeka No. 10, Surabaya",
    "delivery_status": "on_delivery",
    "courier_latitude": -7.2756,
    "courier_longitude": 112.7939
  }
}
```

---

### 2.2 Titik Akhir Autentikasi

| Method | Path | Deskripsi | Auth |
|--------|------|-----------|------|
| `GET` | `/register` | Formulir pendaftaran | ❌ Tamu |
| `POST` | `/register` | Daftar pengguna baru | ❌ Tamu |
| `GET` | `/login` | Formulir masuk | ❌ Tamu |
| `POST` | `/login` | Autentikasi pengguna | ❌ Tamu |
| `POST` | `/logout` | Keluar pengguna saat ini | ✅ Auth |
| `GET` | `/verify-otp` | Halaman verifikasi OTP | ❌ Tamu |
| `POST` | `/verify-otp` | Kirim kode OTP | ❌ Tamu |
| `POST` | `/resend-otp` | Kirim ulang email OTP | ❌ Tamu |
| `GET` | `/forgot-password` | Formulir lupa password | ❌ Tamu |
| `POST` | `/forgot-password` | Minta OTP reset password | ❌ Tamu |
| `GET` | `/forgot-password/verify` | Formulir verifikasi OTP | ❌ Tamu |
| `POST` | `/forgot-password/verify` | Verifikasi OTP reset | ❌ Tamu |
| `GET` | `/reset-password` | Formulir password baru | ❌ Tamu |
| `POST` | `/reset-password` | Simpan password baru | ❌ Tamu |
| `GET` | `/auth/google` | Alihkan ke Google OAuth | ❌ Tamu |
| `GET` | `/auth/google/callback` | Panggilan balik Google OAuth | ❌ Tamu |

**Contoh: Daftar Pengguna**

```http
POST /register HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded

name=John+Customer&email=john@example.com&phone=08123456789&password=securepass123&password_confirmation=securepass123
```

```http
HTTP/1.1 302 Found
Location: /verify-otp
```

---

### 2.3 Titik Akhir Pelanggan (Auth Diperlukan)

| Method | Path | Deskripsi | Auth |
|--------|------|-----------|------|
| `GET` | `/profile` | Lihat profil | ✅ Auth |
| `PUT` | `/profile/update` | Perbarui profil (nama, telepon) | ✅ Auth |
| `PUT` | `/profile/password` | Ubah password | ✅ Auth |
| `GET` | `/profile/history` | Riwayat pesanan | ✅ Auth |
| `GET` | `/profile/vouchers` | Daftar voucher dimiliki | ✅ Auth |
| `GET` | `/profile/referral` | Statistik program referral | ✅ Auth |

**Contoh: Perbarui Profil**

```http
PUT /profile/update HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Cookie: XSRF-TOKEN=...

{
  "name": "John Updated",
  "phone": "08123456789"
}
```

```json
// Respons 302 Alihkan
{
  "success": "Profil berhasil diperbarui!"
}
```

---

### 2.4 Titik Akhir Keranjang & Checkout

| Method | Path | Deskripsi | Auth |
|--------|------|-----------|------|
| `GET` | `/cart` | Lihat keranjang | ❌ Tamu |
| `POST` | `/cart/add/{id}` | Tambah item ke keranjang | ❌ Tamu |
| `POST` | `/cart/update/{id}` | Perbarui jumlah item | ❌ Tamu |
| `POST` | `/cart/remove/{id}` | Hapus item dari keranjang | ❌ Tamu |
| `GET` | `/checkout` | Halaman checkout | ❌ Tamu |
| `POST` | `/checkout/process` | Kirim pesanan | ❌ Tamu |
| `POST` | `/checkout/voucher/apply` | Gunakan kode voucher | ✅ Auth |
| `POST` | `/checkout/voucher/remove` | Hapus voucher yang digunakan | ✅ Auth |

**Contoh: Tambah Item ke Keranjang**

```http
POST /cart/add/1 HTTP/1.1
Host: localhost:8000
Content-Type: application/json

{
  "quantity": 2,
  "notes": "Extra pedas ya"
}
```

```http
HTTP/1.1 302 Found
Location: /cart
```

**Contoh: Gunakan Voucher**

```http
POST /checkout/voucher/apply HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded
Cookie: XSRF-TOKEN=...

voucher_code=NEW-BOGO-2026
```

```http
HTTP/1.1 302 Found
Set-Cookie: applied_voucher=...
```

---

### 2.5 Titik Akhir Admin (`/admin/*`)

Semua titik akhir admin memerlukan middleware `auth` + `isAdmin`.

| Method | Path | Deskripsi |
|--------|------|-----------|
| `GET` | `/admin/dashboard` | Dashboard dengan KPI |
| `GET` | `/admin/menus` | Daftar semua menu |
| `POST` | `/admin/menus` | Buat menu |
| `GET` | `/admin/menus/{id}/edit` | Formulir edit menu |
| `PUT` | `/admin/menus/{id}` | Perbarui menu |
| `DELETE` | `/admin/menus/{id}` | Soft-delete menu |
| `GET` | `/admin/categories` | Daftar kategori |
| `POST` | `/admin/categories` | Buat kategori |
| `PUT` | `/admin/categories/{id}` | Perbarui kategori |
| `DELETE` | `/admin/categories/{id}` | Soft-delete kategori |
| `GET` | `/admin/users` | Daftar pengguna |
| `POST` | `/admin/users` | Buat pengguna (staf) |
| `PUT` | `/admin/users/{id}` | Perbarui pengguna |
| `DELETE` | `/admin/users/{id}` | Soft-delete pengguna |
| `GET` | `/admin/vouchers` | Daftar voucher |
| `POST` | `/admin/vouchers` | Buat voucher |
| `PUT` | `/admin/vouchers/{id}` | Perbarui voucher |
| `DELETE` | `/admin/vouchers/{id}` | Soft-delete voucher |
| `GET` | `/admin/transactions` | Daftar transaksi |
| `GET` | `/admin/transactions/{id}` | Detail transaksi |
| `GET` | `/admin/deliveries` | Daftar pengiriman |
| `GET` | `/admin/deliveries/{id}` | Detail pengiriman |
| `GET` | `/admin/reports` | Halaman laporan penjualan |
| `GET` | `/admin/reports/export-pdf` | Ekspor laporan sebagai PDF |
| `GET` | `/admin/articles` | Daftar artikel |
| `POST` | `/admin/articles` | Buat artikel |
| `PUT` | `/admin/articles/{id}` | Perbarui artikel |
| `DELETE` | `/admin/articles/{id}` | Soft-delete artikel |

**Contoh: Buat Menu (Admin)**

```http
POST /admin/menus HTTP/1.1
Host: localhost:8000
Content-Type: multipart/form-data
Cookie: XSRF-TOKEN=...

category_id=1
menu_name=Nasi Goreng Spesial
menu_price=35000
menu_description=Nasi goreng dengan telur, ayam, dan kerupuk
is_available=1
menu_image=@/path/to/image.jpg
```

```http
HTTP/1.1 302 Found
Location: /admin/menus
```

---

### 2.6 Titik Akhir Kasir (`/cashier/*`)

Semua titik akhir kasir memerlukan middleware `auth` + `isCashier`.

| Method | Path | Deskripsi |
|--------|------|-----------|
| `GET` | `/cashier/transactions` | Transaksi hari ini |
| `GET` | `/cashier/transactions/{id}` | Detail transaksi |
| `GET` | `/cashier/manual-order` | Halaman pesanan POS |
| `POST` | `/cashier/manual-order/add/{id}` | Tambah item ke keranjang POS |
| `POST` | `/cashier/manual-order/update/{id}` | Perbarui item keranjang POS |
| `POST` | `/cashier/manual-order/process` | Kirim pesanan POS |
| `GET` | `/cashier/manual-order/payment/{id}` | Halaman pembayaran POS |
| `POST` | `/cashier/manual-order/payment/{id}/complete` | Selesaikan pembayaran tunai |
| `GET` | `/cashier/pickup` | Antrean pengambilan |
| `POST` | `/cashier/pickup/{id}/confirm` | Konfirmasi pengambilan |

---

### 2.7 Titik Akhir Dapur (`/kitchen/*`)

Semua titik akhir dapur memerlukan middleware `auth` + `isKitchen`.

| Method | Path | Deskripsi |
|--------|------|-----------|
| `GET` | `/kitchen/board` | Papan display dapur |
| `POST` | `/kitchen/order/{id}/status` | Perbarui status pesanan |

**Permintaan (Perbarui Status):**

```http
POST /kitchen/order/5/status HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded
Cookie: XSRF-TOKEN=...

status=cooking
```

```http
HTTP/1.1 302 Found
Location: /kitchen/board
```

---

### 2.8 Titik Akhir Kurir (`/courier/*`)

Semua titik akhir kurir memerlukan middleware `auth` + `isCourier`.

| Method | Path | Deskripsi |
|--------|------|-----------|
| `GET` | `/courier/offers` | Tawaran pengiriman tersedia |
| `POST` | `/courier/offers/{id}/accept` | Terima tawaran pengiriman |
| `GET` | `/courier/my-deliveries` | Daftar pengiriman saya |
| `GET` | `/courier/map/{id}` | Tampilan peta pengiriman |
| `POST` | `/courier/map/{id}/update-location` | Perbarui posisi GPS kurir |
| `POST` | `/courier/map/{id}/complete` | Selesaikan pengiriman |

**Contoh: Perbarui Lokasi**

```http
POST /courier/map/5/update-location HTTP/1.1
Host: localhost:8000
Content-Type: application/json
Cookie: XSRF-TOKEN=...

{
  "latitude": -7.2756,
  "longitude": 112.7939
}
```

```json
// Respons 200
{
  "message": "Lokasi diperbarui"
}
```

**Contoh: Selesaikan Pengiriman**

```http
POST /courier/map/5/complete HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded
Cookie: XSRF-TOKEN=...
```

```http
HTTP/1.1 302 Found
Location: /courier/offers
```

---

## 3. Contoh CURL untuk Pengujian

### 3.1 Jelajahi Menu

```bash
curl -s http://localhost:8000/menu | head -50
```

### 3.2 Lacak Pesanan

```bash
curl -s -X GET http://localhost:8000/track/QTC-ABC123 \
  -H "Accept: application/json"
```

### 3.3 Simulasi Webhook Midtrans (Pembayaran Berhasil)

```bash
# Ganti SERVER_KEY dengan kunci server Midtrans Anda
SERVER_KEY="YOUR_MIDTRANS_SERVER_KEY"
ORDER_ID="QTC-ABC123-1720500000"
STATUS_CODE="200"
GROSS_AMOUNT="85000"
SIGNATURE=$(echo -n "$ORDER_ID$STATUS_CODE$GROSS_AMOUNT$SERVER_KEY" | openssl sha512 | awk '{print $2}')

curl -s -X POST http://localhost:8000/webhook/midtrans \
  -H "Content-Type: application/json" \
  -d '{
    "order_id": "'"$ORDER_ID"'",
    "status_code": "'"$STATUS_CODE"'",
    "gross_amount": "'"$GROSS_AMOUNT"'",
    "signature_key": "'"$SIGNATURE"'",
    "transaction_status": "settlement",
    "transaction_id": "mid-123456",
    "payment_type": "qris"
  }'
```

### 3.4 Gunakan Voucher Saat Checkout

```bash
curl -s -X POST http://localhost:8000/checkout/voucher/apply \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "Cookie: XSRF-TOKEN=$(cat /tmp/xsrf-token); laravel_session=$(cat /tmp/session)" \
  -d "voucher_code=NEW-BOGO-2026"
```

---

## 4. Alur Webhook Pembayaran

```
┌──────────┐         ┌──────────┐         ┌──────────┐
│ Pelanggan│  Bayar  │ Midtrans │ Webhook │ Qitchen  │
│ Browser  │ ───────▶│ Snap API │ ───────▶│ Server   │
│          │         │          │  POST   │          │
│ Pop Snap │◀────────│          │  /webhook│          │
│(QR/VA)   │  Token  │          │         │          │
└──────────┘         └──────────┘         └──────────┘
                                               │
                                          ┌────▼────┐
                                          │ Verifikasi│
                                          │ SHA-512  │
                                          │ Tanda    │
                                          │ Tangan   │
                                          └────┬────┘
                                               │
                                    ┌──────────▼──────────┐
                                    │ Perbarui Status     │
                                    │ Pesanan             │
                                    │ pending → paid      │
                                    │                     │
                                    │ [Opsional]          │
                                    │ Trigger Hadiah      │
                                    │ Referral jika       │
                                    │ berlaku             │
                                    └─────────────────────┘
```

### Muatan Webhook (dari Midtrans)

```json
{
  "transaction_time": "2026-06-25 14:30:00",
  "transaction_status": "settlement",
  "transaction_id": "3b6d8c9f-1a2b-4e5f-8a7b-9c0d1e2f3a4b",
  "status_code": "200",
  "merchant_id": "M123456",
  "gross_amount": "85000.00",
  "order_id": "QTC-ABC123-1720500000",
  "payment_type": "qris",
  "signature_key": "a1b2c3d4e5f6...SHA512hash",
  "currency": "IDR"
}
```

### Respons Webhook (dari Qitchen)

```json
// Berhasil
HTTP 200
{
  "message": "Webhook Diproses & Hadiah Didistribusikan"
}

// Tanda Tangan Tidak Valid
HTTP 403
{
  "message": "Tanda Tangan Tidak Valid"
}

// Pesanan Tidak Ditemukan
HTTP 404
{
  "message": "Pesanan Tidak Ditemukan"
}

// Sudah Diproses (Idempotensi)
HTTP 200
{
  "message": "Pesanan sudah diproses sebelumnya"
}
```

---

## 5. Model Data (Muatan API)

### Menu

```json
{
  "menu_id": 1,
  "menu_name": "Nasi Goreng Spesial",
  "menu_slug": "nasi-goreng-spesial",
  "menu_price": 35000.00,
  "menu_description": "Nasi goreng dengan telur, ayam, dan kerupuk",
  "menu_image": "/storage/menus/nasi-goreng.jpg",
  "is_available": true,
  "category": {
    "category_id": 1,
    "category_name": "Makanan"
  }
}
```

### Pesanan (setelah checkout)

```json
{
  "order_id": 42,
  "order_number": "QTC-ABC123",
  "order_type": "delivery",
  "order_status": "pending",
  "order_subtotal": 75000.00,
  "order_discount": 0,
  "order_total": 87500.00,
  "payment": {
    "payment_method": "qris",
    "payment_status": "pending",
    "snap_token": "snap-mid-xxxxxxxxxxxx"
  },
  "items": [
    {
      "menu_name": "Nasi Goreng Spesial",
      "quantity": 2,
      "item_subtotal": 70000
    },
    {
      "menu_name": "Es Teh Manis",
      "quantity": 1,
      "item_subtotal": 5000
    }
  ]
}
```

---

*Kembali ke [docs/](./)*