# Spesifikasi API — [Nama Proyek]

> **Status:** Aktif  
**URL Dasar (Dev):** `http://localhost:8000`  
**URL Dasar (Prod):** `https://[domain].com`  
**Tipe Konten:** `application/json`  
**Standar:** RESTful  
**Terakhir Diperbarui:** YYYY-MM

---

## 1. Konvensi Kode Status HTTP

| Kode | Arti | Penggunaan |
|------|------|------------|
| `200 OK` | Permintaan berhasil | GET, PUT, PATCH |
| `201 Created` | Resource berhasil dibuat | POST |
| `302 Found` | Pengalihan | Setelah submit form |
| `400 Bad Request` | Error validasi | Input tidak valid |
| `401 Unauthorized` | Belum login | Sesi/token hilang |
| `403 Forbidden` | Tidak punya akses | Middleware reject |
| `404 Not Found` | Resource tidak ditemukan | `ModelNotFoundException` |
| `422 Unprocessable` | Validasi semantik gagal | State invalid |
| `429 Too Many Requests` | Rate limit | `throttle` middleware |
| `500 Server Error` | Error server | Exception |

---

## 2. Titik Akhir API

### 2.1 Publik (Tanpa Auth)

| Method | Path | Deskripsi | Batas Kecepatan |
|--------|------|-----------|----------------|
| `GET` | `/[resource]` | [Deskripsi] | [n]/menit |

**Contoh:**

```http
GET /[resource]/[id] HTTP/1.1
Host: localhost:8000
```

```json
{
  "key": "value"
}
```

---

### 2.2 Autentikasi

| Method | Path | Deskripsi | Auth |
|--------|------|-----------|------|
| `POST` | `/login` | Login | ❌ Guest |
| `POST` | `/register` | Daftar | ❌ Guest |
| `POST` | `/logout` | Logout | ✅ Auth |

**Contoh Register:**

```http
POST /register HTTP/1.1
Host: localhost:8000
Content-Type: application/x-www-form-urlencoded

field1=value1&field2=value2
```

---

### 2.3 Resource CRUD (Auth Diperlukan)

| Method | Path | Deskripsi |
|--------|------|-----------|
| `GET` | `/api/[resource]` | List all |
| `POST` | `/api/[resource]` | Create |
| `GET` | `/api/[resource]/{id}` | Show detail |
| `PUT` | `/api/[resource]/{id}` | Update |
| `DELETE` | `/api/[resource]/{id}` | Delete |

**Contoh Payload:**

```json
// Request POST /api/resource
{
  "field": "value"
}

// Response 201
{
  "id": 1,
  "field": "value",
  "created_at": "2026-01-01T00:00:00Z"
}
```

---

### 2.4 [Role-Specific] Endpoints

| Method | Path | Deskripsi | Auth |
|--------|------|-----------|------|
| `GET` | `/[role]/[fitur]` | [Deskripsi] | ✅ [Role] |

---

## 3. Contoh CURL

### 3.1 [Contoh 1]

```bash
curl -s [endpoint]
```

### 3.2 [Contoh 2]

```bash
curl -s -X POST [endpoint] \
  -H "Content-Type: application/json" \
  -d '{"key": "value"}'
```

---

## 4. Model Data

### [Resource Name]

```json
{
  "id": 1,
  "field": "value",
  "relation": {
    "id": 1,
    "name": "value"
  }
}
```

---

*Kembali ke [docs/](./)*