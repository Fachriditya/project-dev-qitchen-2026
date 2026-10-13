# Model Ancaman & Spesifikasi Keamanan — [Nama Proyek]

> **Status:** Aktif  
**Klasifikasi:** Internal  
**Standar:** OWASP Top 10:2021, OWASP ASVS 4.0  
**Terakhir Diperbarui:** YYYY-MM

---

## 1. Ikhtisar Permukaan Serangan

```
┌─────────────────────────────────────────────────────────────┐
│                   PERMUKAAN SERANGAN                        │
├─────────────────────────────────────────────────────────────┤
│  [Kategori Pengguna 1]                                      │
│    ├── [Endpoint]              → [Risiko]                   │
│    └── [Endpoint]              → [Risiko]                   │
│  [Kategori Pengguna 2]                                      │
│    ├── [Endpoint]              → [Risiko]                   │
│    └── [Endpoint]              → [Risiko]                   │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. Penilaian Risiko OWASP Top 10

### A01: Kerusakan Kontrol Akses

| Risiko | Vektor | Dampak | Kemungkinan |
|--------|--------|--------|-------------|
| [Risiko] | [Vektor] | [Kritis/Tinggi/Sedang] | [Tinggi/Sedang/Rendah] |

**Mitigasi:**
- ✅ [Mitigasi yang sudah diimplementasikan]
- ❌ [Mitigasi yang belum diimplementasikan]

### A02: Kegagalan Kriptografi

[Risiko dan mitigasi...]

<!-- Ulangi untuk A03–A10 -->

### A03: Injeksi (SQLi, XSS)

...

### A04: Desain Tidak Aman

...

### A05: Salah Konfigurasi Keamanan

...

### A06: Komponen Rentan

...

### A07: Kegagalan Identifikasi & Autentikasi

...

### A08: Kegagalan Integritas

...

### A09: Kegagalan Pencatatan & Pemantauan

...

### A10: SSRF

...

---

## 3. Matriks Kontrol Akses (RBAC)

### Izin berdasarkan Peran

| Fitur / Rute | Peran 1 | Peran 2 | Peran 3 |
|--------------|---------|---------|---------|
| `/endpoint` | ✅ | ✅ | ❌ |
| `/admin/*` | ❌ | ❌ | ✅ |

### Peran dalam Sistem

| Peran | Nilai Database | Middleware | Grup Rute |
|-------|---------------|------------|-----------|
| [Role 1] | `[value]` | `[Class]` | `/[prefix]` |

---

## 4. Tinjauan Kode Terkait Keamanan

| ID | Temuan | Berkas | Severitas | Perbaikan |
|----|--------|--------|-----------|-----------|
| SEC-001 | [Temuan] | [File] | 🔴 Kritis | [Perbaikan] |

---

## 5. Daftar Periksa Verifikasi Keamanan (Pra-Rilis)

### Autentikasi & Sesi

- [ ] Checklist item 1
- [ ] Checklist item 2

### Kontrol Akses

- [ ] Checklist item 1

### Validasi Input

- [ ] Checklist item 1

### Keamanan Pembayaran (jika relevan)

- [ ] Checklist item 1

> **Legenda:** `[x]` = Terimplementasi | `[ ]` = Belum terimplementasi

---

## 6. Klasifikasi & Sensitivitas Data

| Tipe Data | Klasifikasi | Penyimpanan | Enkripsi |
|-----------|-------------|-------------|----------|
| [Data] | [Kritis/Tinggi/Sedang] | [Lokasi] | [Metode] |

---

## 7. Rencana Respons Insiden

| Severitas | Definisi | Waktu Respons | Tindakan |
|-----------|----------|---------------|----------|
| 🔴 Kritis | [Definisi] | [Waktu] | [Tindakan] |
| 🟡 Tinggi | [Definisi] | [Waktu] | [Tindakan] |
| 🟢 Sedang | [Definisi] | [Waktu] | [Tindakan] |

---

*Kembali ke [docs/](./)*