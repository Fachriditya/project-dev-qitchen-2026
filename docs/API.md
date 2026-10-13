# API Specification — Qitchen 2026

> **Status:** Active  
**Base URL (Dev):** `http://localhost:8000`  
**Base URL (Prod):** `https://qitchen.example.com`  
**Content-Type:** `application/json`  
**Standards:** RESTful, Laravel Resource Controllers  
**Last Updated:** 2026

---

## 1. HTTP Status Code Conventions

| Code | Meaning | Usage |
|------|---------|-------|
| `200 OK` | Request succeeded | GET, PUT, PATCH |
| `201 Created` | Resource created | POST (store) |
| `302 Found` | Redirect | After form submission |
| `400 Bad Request` | Validation error / Malformed input | Laravel `ValidationException` |
| `401 Unauthorized` | Not authenticated | Missing/invalid session or token |
| `403 Forbidden` | Authenticated but wrong role | Middleware rejection (`isAdmin`, etc.) |
| `404 Not Found` | Resource not found | `ModelNotFoundException` |
| `422 Unprocessable Entity` | Semantic validation failure | Invalid state transition |
| `429 Too Many Requests` | Rate limit exceeded | `throttle` middleware |
| `500 Internal Server Error` | Server error | Unhandled exception |

---

## 2. API Endpoints

### 2.1 Public Endpoints (No Auth Required)

| Method | Path | Description | Rate Limit |
|--------|------|-------------|------------|
| `GET` | `/` | Home page | 60/min |
| `GET` | `/menu` | List all available menus | 60/min |
| `GET` | `/menu/{id}` | Single menu detail | 60/min |
| `GET` | `/articles` | List published articles | 60/min |
| `GET` | `/articles/{slug}` | Single article detail | 60/min |
| `GET` | `/track/{order_number}` | Track order by number | 30/min |
| `GET` | `/about` | About page | 60/min |
| `GET` | `/contact` | Contact page | 60/min |
| `POST` | `/contact` | Submit contact form | 5/min |
| `POST` | `/webhook/midtrans` | Midtrans payment callback | No limit (IP whitelist recommended) |

**Example: Track Order**

```http
GET /track/QTC-ABC123 HTTP/1.1
Host: localhost:8000
```

```json
// Response 200
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

### 2.2 Authentication Endpoints

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| `GET` | `/register` | Registration form | ❌ Guest |
| `POST` | `/register` | Register new user | ❌ Guest |
| `GET` | `/login` | Login form | ❌ Guest |
| `POST` | `/login` | Authenticate user | ❌ Guest |
| `POST` | `/logout` | Logout current user | ✅ Auth |
| `GET` | `/verify-otp` | OTP verification page | ❌ Guest |
| `POST` | `/verify-otp` | Submit OTP code | ❌ Guest |
| `POST` | `/resend-otp` | Resend OTP email | ❌ Guest |
| `GET` | `/forgot-password` | Forgot password form | ❌ Guest |
| `POST` | `/forgot-password` | Request password reset OTP | ❌ Guest |
| `GET` | `/forgot-password/verify` | OTP verification form | ❌ Guest |
| `POST` | `/forgot-password/verify` | Verify reset OTP | ❌ Guest |
| `GET` | `/reset-password` | New password form | ❌ Guest |
| `POST` | `/reset-password` | Save new password | ❌ Guest |
| `GET` | `/auth/google` | Redirect to Google OAuth | ❌ Guest |
| `GET` | `/auth/google/callback` | Google OAuth callback | ❌ Guest |

**Example: Register User**

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

### 2.3 Customer Endpoints (Auth Required)

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| `GET` | `/profile` | View profile | ✅ Auth |
| `PUT` | `/profile/update` | Update profile (name, phone) | ✅ Auth |
| `PUT` | `/profile/password` | Change password | ✅ Auth |
| `GET` | `/profile/history` | Order history | ✅ Auth |
| `GET` | `/profile/vouchers` | List owned vouchers | ✅ Auth |
| `GET` | `/profile/referral` | Referral program stats | ✅ Auth |

**Example: Update Profile**

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
// Response 302 Redirect
{
  "success": "Profile updated successfully!"
}
```

---

### 2.4 Cart & Checkout Endpoints

| Method | Path | Description | Auth |
|--------|------|-------------|------|
| `GET` | `/cart` | View cart | ❌ Guest |
| `POST` | `/cart/add/{id}` | Add menu item to cart | ❌ Guest |
| `POST` | `/cart/update/{id}` | Update item quantity | ❌ Guest |
| `POST` | `/cart/remove/{id}` | Remove item from cart | ❌ Guest |
| `GET` | `/checkout` | Checkout page | ❌ Guest |
| `POST` | `/checkout/process` | Submit order | ❌ Guest |
| `POST` | `/checkout/voucher/apply` | Apply voucher code | ✅ Auth |
| `POST` | `/checkout/voucher/remove` | Remove applied voucher | ✅ Auth |

**Example: Add Item to Cart**

```http
POST /cart/add/1 HTTP/1.1
Host: localhost:8000
Content-Type: application/json

{
  "quantity": 2,
  "notes": "Extra spicy please"
}
```

```http
HTTP/1.1 302 Found
Location: /cart
```

**Example: Apply Voucher**

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

### 2.5 Admin Endpoints (`/admin/*`)

All admin endpoints require `auth` + `isAdmin` middleware.

| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/admin/dashboard` | Dashboard with KPIs |
| `GET` | `/admin/menus` | List all menus |
| `POST` | `/admin/menus` | Create menu |
| `GET` | `/admin/menus/{id}/edit` | Edit menu form |
| `PUT` | `/admin/menus/{id}` | Update menu |
| `DELETE` | `/admin/menus/{id}` | Soft-delete menu |
| `GET` | `/admin/categories` | List categories |
| `POST` | `/admin/categories` | Create category |
| `PUT` | `/admin/categories/{id}` | Update category |
| `DELETE` | `/admin/categories/{id}` | Soft-delete category |
| `GET` | `/admin/users` | List users |
| `POST` | `/admin/users` | Create user (staff) |
| `PUT` | `/admin/users/{id}` | Update user |
| `DELETE` | `/admin/users/{id}` | Soft-delete user |
| `GET` | `/admin/vouchers` | List vouchers |
| `POST` | `/admin/vouchers` | Create voucher |
| `PUT` | `/admin/vouchers/{id}` | Update voucher |
| `DELETE` | `/admin/vouchers/{id}` | Soft-delete voucher |
| `GET` | `/admin/transactions` | List transactions |
| `GET` | `/admin/transactions/{id}` | Transaction detail |
| `GET` | `/admin/deliveries` | List deliveries |
| `GET` | `/admin/deliveries/{id}` | Delivery detail |
| `GET` | `/admin/reports` | Sales report page |
| `GET` | `/admin/reports/export-pdf` | Export report as PDF |
| `GET` | `/admin/articles` | List articles |
| `POST` | `/admin/articles` | Create article |
| `PUT` | `/admin/articles/{id}` | Update article |
| `DELETE` | `/admin/articles/{id}` | Soft-delete article |

**Example: Create Menu (Admin)**

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

### 2.6 Cashier Endpoints (`/cashier/*`)

All cashier endpoints require `auth` + `isCashier` middleware.

| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/cashier/transactions` | Today's transactions |
| `GET` | `/cashier/transactions/{id}` | Transaction detail |
| `GET` | `/cashier/manual-order` | POS order page |
| `POST` | `/cashier/manual-order/add/{id}` | Add item to POS cart |
| `POST` | `/cashier/manual-order/update/{id}` | Update POS cart item |
| `POST` | `/cashier/manual-order/process` | Submit POS order |
| `GET` | `/cashier/manual-order/payment/{id}` | POS payment page |
| `POST` | `/cashier/manual-order/payment/{id}/complete` | Complete cash payment |
| `GET` | `/cashier/pickup` | Pickup queue |
| `POST` | `/cashier/pickup/{id}/confirm` | Confirm pickup |

---

### 2.7 Kitchen Endpoints (`/kitchen/*`)

All kitchen endpoints require `auth` + `isKitchen` middleware.

| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/kitchen/board` | Kitchen display board |
| `POST` | `/kitchen/order/{id}/status` | Update order status |

**Request (Update Status):**

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

### 2.8 Courier Endpoints (`/courier/*`)

All courier endpoints require `auth` + `isCourier` middleware.

| Method | Path | Description |
|--------|------|-------------|
| `GET` | `/courier/offers` | Available delivery offers |
| `POST` | `/courier/offers/{id}/accept` | Accept delivery offer |
| `GET` | `/courier/my-deliveries` | My deliveries list |
| `GET` | `/courier/map/{id}` | Delivery map view |
| `POST` | `/courier/map/{id}/update-location` | Update courier GPS position |
| `POST` | `/courier/map/{id}/complete` | Complete delivery |

**Example: Update Location**

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
// Response 200
{
  "message": "Location updated"
}
```

**Example: Complete Delivery**

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

## 3. CURL Examples for Testing

### 3.1 Browse Menu

```bash
curl -s http://localhost:8000/menu | head -50
```

### 3.2 Track Order

```bash
curl -s -X GET http://localhost:8000/track/QTC-ABC123 \
  -H "Accept: application/json"
```

### 3.3 Simulate Midtrans Webhook (Payment Success)

```bash
# Replace SERVER_KEY with your Midtrans server key
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

### 3.4 Apply Voucher During Checkout

```bash
curl -s -X POST http://localhost:8000/checkout/voucher/apply \
  -H "Content-Type: application/x-www-form-urlencoded" \
  -H "Cookie: XSRF-TOKEN=$(cat /tmp/xsrf-token); laravel_session=$(cat /tmp/session)" \
  -d "voucher_code=NEW-BOGO-2026"
```

---

## 4. Payment Webhook Flow

```
┌──────────┐         ┌──────────┐         ┌──────────┐
│ Customer │  Pay    │ Midtrans │ Webhook │ Qitchen  │
│ Browser  │ ───────▶│ Snap API │ ───────▶│ Server   │
│          │         │          │  POST   │          │
│ Snap Pop │◀────────│          │  /webhook│          │
│  (QR/VA) │  Token  │          │         │          │
└──────────┘         └──────────┘         └──────────┘
                                               │
                                          ┌────▼────┐
                                          │ Verify  │
                                          │ SHA-512 │
                                          │ Signature│
                                          └────┬────┘
                                               │
                                    ┌──────────▼──────────┐
                                    │ Update Order Status  │
                                    │ pending → paid       │
                                    │                      │
                                    │ [Optional]           │
                                    │ Trigger Referral     │
                                    │ Reward if applicable │
                                    └─────────────────────┘
```

### Webhook Payload (from Midtrans)

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

### Webhook Response (from Qitchen)

```json
// Success
HTTP 200
{
  "message": "Webhook Processed & Rewards Distributed"
}

// Invalid Signature
HTTP 403
{
  "message": "Invalid Signature"
}

// Order Not Found
HTTP 404
{
  "message": "Order Not Found"
}

// Already Processed (Idempotency)
HTTP 200
{
  "message": "Order already processed"
}
```

---

## 5. Data Models (API Payloads)

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

### Order (after checkout)

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

*Back to [docs/](./)*