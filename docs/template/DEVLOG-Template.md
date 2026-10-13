# Log Pengembangan — [Nama Proyek]

> **Status:** Aktif  
**Pemilik:** [Nama]  
**Repositori:** [URL GitHub]  
**Terakhir Diperbarui:** YYYY-MM

---

## 1. Persiapan Proyek

### 1.1 Scaffold Awal

```bash
# Perintah inisialisasi proyek
[command 1]
[command 2]
```

### 1.2 Basis Data

```bash
# Perintah setup database
[command]
```

### 1.3 Variabel Lingkungan

```env
# .env example
KEY=VALUE
```

### 1.4 Struktur Direktori

```
project/
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Models/
├── config/
├── database/
├── routes/
└── tests/
```

---

## 2. Pelacakan Milestone

### Template Milestone

```markdown
## Milestone: [NAMA]

**Target:** YYYY-MM-DD  
**Status:** 🟢 On Track / 🟡 Beresiko / 🔴 Terlambat

### Lingkup
- [ ] Task A
- [ ] Task B

### Hasil
- [ ] PR #xx merged
```

### Linimasa

| Milestone | Deskripsi | Target | Status |
|-----------|-----------|--------|--------|
| M0 | [Deskripsi] | [Minggu] | ✅ Selesai |
| M1 | [Deskripsi] | [Minggu] | 🔄 Dalam Progress |

---

## 3. Log Perbaikan Bug

### Template Bug

```markdown
## BUG-[ID]: [Judul Singkat]

**Severitas:** 🔴 Kritis / 🟡 Tinggi / 🟢 Sedang / 🔵 Rendah  
**Status:** Terbuka / Terselesaikan  
**Ditemukan:** YYYY-MM-DD  
**Diperbaiki:** YYYY-MM-DD  
**Commit:** `[hash]`

### Deskripsi
[Apa yang terjadi]

### Akar Masalah
[Mengapa terjadi]

### Perbaikan
[Apa yang diubah]

### Risiko Regresi
[Rendah/Sedang/Tinggi]
```

### Entri Bug

<!-- Salin template di atas dan isi untuk setiap bug -->

---

## 4. Log Refactoring

### Template Refactoring

```markdown
## REF-[ID]: [Judul Singkat]

**Lingkup:** [File/Modul]  
**Status:** Diusulkan / Selesai  
**Tanggal:** YYYY-MM-DD

### Motivasi
[Mengapa refactoring diperlukan]

### Sebelum vs Sesudah
```php
// Sebelum
[old code]

// Sesudah
[new code]
```

### Verifikasi
[Bagaimana memastikan tidak rusak]
```

---

## 5. Daftar Utang Teknis

| ID | Item | Area | Severitas | Dibuat |
|----|------|------|-----------|--------|
| TD-001 | [Item] | [Area] | [Tinggi/Sedang/Rendah] | YYYY |

---

## 6. Catatan Lingkungan & Deployment

### Dev Environment

```bash
# Perintah menjalankan aplikasi
composer run dev
```

### Database Commands

```bash
php artisan migrate:fresh --seed
```

### Testing

```bash
php artisan test
```

---

*Kembali ke [docs/](./)*