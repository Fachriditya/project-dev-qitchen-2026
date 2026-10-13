# Development Log — Qitchen 2026

> **Status:** Active  
**Owner:** Muhammad Fachriditya  
**Repository:** [Fachriditya/project-dev-qitchen-2026](https://github.com/Fachriditya/project-dev-qitchen-2026)  
**Last Updated:** 2026

---

## 1. Project Setup

### 1.1 Initial Scaffold

```bash
# Created using Laravel 13.x + Breeze
composer create-project laravel/laravel qitchen-2026
composer require laravel/breeze --dev
php artisan breeze:install blade

# Core dependencies
composer require laravel/socialite
composer require midtrans/midtrans-php
composer require barryvdh/laravel-dompdf

# Frontend
npm install && npm run build
```

### 1.2 Database

```bash
# Development: SQLite
touch database/database.sqlite

# Production: MySQL (configure .env)
# DB_CONNECTION=mysql
# DB_DATABASE=qitchen
# DB_USERNAME=root
# DB_PASSWORD=...
```

### 1.3 Environment Variables

```env
# .env (required)
APP_NAME=Qitchen
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=sqlite

# Midtrans
MIDTRANS_SERVER_KEY=
MIDTRANS_CLIENT_KEY=
MIDTRANS_IS_PRODUCTION=false

# Google OAuth
GOOGLE_CLIENT_ID=
GOOGLE_CLIENT_SECRET=
GOOGLE_REDIRECT_URL=${APP_URL}/auth/google/callback

# Mail (OTP)
MAIL_MAILER=smtp
MAIL_HOST=sandbox.smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=
MAIL_PASSWORD=
```

### 1.4 Directory Structure (Key Paths)

```
qitchen-2026/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/       # 9 controllers
│   │   │   ├── Api/         # MidtransWebhookController
│   │   │   ├── Auth/        # Registration, Login, OTP, Google, ForgotPassword
│   │   │   ├── Cashier/     # ManualOrder, Pickup, Transaction
│   │   │   ├── Courier/     # DeliveryOfferController
│   │   │   ├── Customer/    # Profile, Order, Voucher, Referral
│   │   │   ├── Kitchen/     # KitchenController
│   │   │   └── Public/      # Home, Menu, Cart, Checkout, Tracking, Article
│   │   ├── Middleware/       # IsAdmin, IsCashier, IsKitchen, IsCourier, IsCustomer
│   │   └── Requests/        # Auth form requests
│   ├── Models/               # 11 Eloquent models
│   └── Providers/
├── config/                   # midtrans.php, app.php
├── database/
│   ├── migrations/           # 11 migration files
│   └── seeders/              # User, Category, Menu, Voucher, Article
├── resources/views/          # Blade templates (7 subdirectories)
├── routes/
│   ├── web.php               # Main route definitions
│   └── auth.php              # Breeze auth routes
├── docs/                     # Documentation
└── tests/                    # PHPUnit test files
```

---

## 2. Milestone Tracking

### 2.1 Milestone Template

```markdown
## Milestone: [MILESTONE_NAME]

**Target Date:** YYYY-MM-DD  
**Status:** 🟢 On Track / 🟡 At Risk / 🔴 Delayed  
**Owner:** @username

### Scope
- [ ] Feature A
- [ ] Feature B

### Deliverables
- [ ] PR #xx merged
- [ ] Deployed to staging

### Blockers
- [ ] Blocker description

### Metrics
- [ ] Test coverage ≥ 80%
- [ ] All acceptance criteria passed
```

### 2.2 Milestone Timeline

| Milestone | Description | Target | Status |
|-----------|-------------|--------|--------|
| M0 | Foundation — Auth, Roles, Middleware, Database | Week 1-2 | ✅ Complete |
| M1 | Core Ordering — Menu, Cart, Checkout, Payment | Week 3-5 | ✅ Complete |
| M2 | Operations — Kitchen Board, Delivery, Cashier POS | Week 6-8 | ✅ Complete |
| M3 | Loyalty — Vouchers, Referral, Articles | Week 9-10 | ✅ Complete |
| M4 | Management — Dashboard, Reports, PDF Export | Week 11-12 | ✅ Complete |
| M5 | Polish — Bug fixes, Security review, Documentation | Week 13-14 | 🟡 In Progress |

---

## 3. Bug Fix Log

### Template

```markdown
## BUG-[ID]: [Short Title]

**Severity:** 🔴 Critical / 🟡 High / 🟢 Medium / 🔵 Low  
**Status:** Open / In Progress / Resolved / Verified  
**Found:** YYYY-MM-DD  
**Fixed:** YYYY-MM-DD  
**Commit:** `[hash]`

### Description

### Root Cause

### Reproduction Steps

### Fix

### Regression Risk
```

### Bug Entries

---

## BUG-001: Referral Reward Not Triggering Due to Wrong Column Name

**Severity:** 🔴 Critical  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

Referral reward voucher is never distributed to the referrer because the Order model event checks `$order->isDirty('status')` and `$order->status === 'completed'`, but the database column is named `order_status`, not `status`.

### Root Cause

Typo in Eloquent model event. The `updated` event on `Order` model referenced the wrong attribute name.

### Fix

Changed `status` → `order_status` in all 4 locations within the `Order::booted()` method.

### Regression Risk

Low. No other code references `$order->status` as a direct attribute.

---

## BUG-002: Webhook Voucher Code Mismatch

**Severity:** 🔴 Critical  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

`MidtransWebhookController` searches for voucher code `REF-BONUS` but the `VoucherSeeder` creates a voucher with code `REF-REWARD-2026`. The referral reward is never created via webhook.

### Root Cause

Hardcoded string mismatch between controller and seeder.

### Fix

Changed `REF-BONUS` → `REF-REWARD-2026` in webhook controller. Also added `Referral::create()` call to persist the reward record.

### Regression Risk

Low. Only affects the referral reward pathway which was previously non-functional.

---

## BUG-003: Delivery Record Not Created During Checkout

**Severity:** 🔴 Critical  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

When a customer selects "delivery" as order type during checkout, no `deliveries` table record is created. Address and coordinates from the checkout form are ignored. Delivery record is only created when a courier clicks "Accept", using placeholder data.

### Root Cause

`CheckoutController@process` method did not include `Delivery::create()` call for delivery-type orders.

### Fix

Added delivery record creation inside the transaction block, saving recipient name, phone, address, latitude, and longitude.

### Regression Risk

Low. Delivery creation only triggers for `order_type === 'delivery'`.

---

## BUG-004: Admin Cannot Create BOGO Vouchers

**Severity:** 🟡 High  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

Voucher validation rule only allows `percentage,fixed,free_delivery` — but the database enum and PRD specify 4 types including `bogo`.

### Root Cause

Validation rule missing the `bogo` option.

### Fix

Added `bogo` to `in:percentage,fixed,bogo,free_delivery` in both `store()` and `update()` methods.

### Regression Risk

Low.

---

## BUG-005: User Role Validation Includes Invalid Role

**Severity:** 🟡 High  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

`UserController@store` and `@update` validate role with `in:super_admin,admin,customer,cashier,kitchen,courier`, but the database enum only has `admin,cashier,kitchen,courier,customer`. Submitting `super_admin` crashes the database insert.

### Root Cause

Validation rule not aligned with database migration enum.

### Fix

Changed to `in:admin,cashier,kitchen,courier,customer`.

### Regression Risk

Low.

---

## BUG-006: Courier Map Shows Hardcoded Coordinates

**Severity:** 🟡 High  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

Leaflet map in both courier and tracking views uses hardcoded coordinates `(-7.2756, 112.7939)` instead of reading from `delivery.latitude` / `delivery.longitude`.

### Root Cause

Static placeholder coordinates in Blade template.

### Fix

Updated map views to use `{{ $order->delivery->latitude ?? -7.2756 }}` and `{{ $order->delivery->longitude ?? 112.7939 }}`.

### Regression Risk

Low.

---

## BUG-007: Inconsistent Voucher Type Name

**Severity:** 🟡 High  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

Checkout discount calculation uses `case 'delivery':` but the database enum and migration use `free_delivery`. Free delivery voucher never applies a discount.

### Root Cause

Inconsistent naming between PRD (which calls it `free_delivery`) and the checkout logic.

### Fix

Changed `case 'delivery':` → `case 'free_delivery':` in both `index()` and `process()` methods.

### Regression Risk

Low.

---

## BUG-008: Missing Courier Location Update Endpoint

**Severity:** 🟢 Medium  
**Status:** Resolved  
**Found:** 2026-06-25  
**Fixed:** 2026-06-25  
**Commit:** `f521539`

### Description

FR-38 specifies courier must be able to update their latitude/longitude. The route and controller method were missing.

### Fix

Added `POST /courier/map/{id}/update-location` route and `updateLocation()` method to `DeliveryOfferController`. Updated map view with "Update My Location" button using browser Geolocation API.

### Regression Risk

Low.

---

## 4. Refactoring Log

### Template

```markdown
## REF-[ID]: [Short Title]

**Scope:** [File/Module]  
**Status:** Proposed / In Progress / Complete  
**Date:** YYYY-MM-DD

### Motivation

### Changes

### Before vs After

### Verification
```

### Refactoring Entries

---

## REF-001: Extract Discount Calculation Logic

**Scope:** `CheckoutController.php`  
**Status:** ⏳ Proposed  
**Date:** 2026

### Motivation

The discount calculation logic (applying voucher types: percentage, fixed, bogo, free_delivery) is duplicated exactly in both `index()` and `process()` methods. Any change to discount rules must be made in two places — a maintenance risk.

### Proposed Solution

Extract into a private method `calculateDiscount($cart, $subtotal, $serviceFee, $activeVoucher)` that returns `$discount`. Both `index()` and `process()` call this method.

### Before

```php
// CheckoutController@index — 30 lines of discount logic
// CheckoutController@process — identical 30 lines of discount logic
```

### After

```php
private function calculateDiscount($cart, $subtotal, $serviceFee, $activeVoucher) {
    $discount = 0;
    // ... logic once
    return $discount;
}

public function index() {
    // ...
    $discount = $this->calculateDiscount($cart, $subtotal, $serviceFee, $activeVoucher);
    // ...
}

public function process() {
    // ...
    $discount = $this->calculateDiscount($cart, $subtotal, $serviceFee, $activeVoucher);
    // ...
}
```

---

## REF-002: Consolidate Referral Reward Logic

**Scope:** `MidtransWebhookController.php` + `Order.php`  
**Status:** ⏳ Proposed  
**Date:** 2026

### Motivation

Referral reward distribution logic exists in TWO places:
1. `Order::booted()` `updated` event — triggers when order becomes `completed`
2. `MidtransWebhookController@handler` — triggers when payment is confirmed `paid`

Both do the same thing (find referrer, check first order, create voucher reward) but with slightly different implementations. This is a DRY violation and a race condition risk.

### Proposed Solution

Extract referral logic into a dedicated Laravel action class or service class `ReferralService::rewardReferrer($order)`.

```php
class ReferralService {
    public static function rewardReferrer(Order $order): void {
        $customer = $order->user;
        if (!$customer || !$customer->referred_by) return;

        // Check first completed order
        // Find referrer
        // Create UserVoucher reward
        // Log to referrals table
    }
}
```

---

## 5. Technical Debt Register

| ID | Item | Area | Severity | Created | 
|----|------|------|----------|---------|
| TD-001 | Discount logic duplicated in `index()` and `process()` | CheckoutController | Medium | 2026 |
| TD-002 | Referral reward logic duplicated in two places | Order + Webhook | High | 2026 |
| TD-003 | `IsCustomer` middleware exists but unused in routes | Middleware | Low | 2026 |
| TD-004 | No throttle/rate-limiting on login endpoint | Auth | Medium | 2026 |
| TD-005 | Kitchen board uses full page reload (30s polling) | Kitchen | Medium | 2026 |
| TD-006 | No failed-login audit logging | Security | Low | 2026 |
| TD-007 | Hardcoded restaurant location (-7.2756, 112.7939) | Config | Low | 2026 |
| TD-008 | Test coverage below 80% | Testing | High | 2026 |

---

## 6. Environment & Deployment Notes

### Dev Environment

```bash
# Start all services (from composer.json)
composer run dev

# This runs:
# - php artisan serve          (Server on :8000)
# - php artisan queue:listen   (Queue worker)
# - php artisan pail           (Log viewer)
# - npm run dev                (Vite HMR)
```

### Database Commands

```bash
# Fresh migration with seeders
php artisan migrate:fresh --seed

# Run specific seeder
php artisan db:seed --class=VoucherSeeder
```

### Testing

```bash
# Run all tests
php artisan test

# Run specific test file
php artisan test --filter=CheckoutTest
```

---

*Back to [docs/](./)*