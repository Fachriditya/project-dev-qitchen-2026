# Threat Model & Security Specification — Qitchen 2026

> **Status:** Active  
**Classification:** Internal  
**Standards:** OWASP Top 10:2021, OWASP ASVS 4.0  
**Last Updated:** 2026

---

## 1. Attack Surface Overview

```
┌─────────────────────────────────────────────────────────────┐
│                      ATTACK SURFACE                         │
├─────────────────────────────────────────────────────────────┤
│                                                             │
│  [Public User]                                              │
│    ├── /menu, /articles              → Read-only, no auth   │
│    ├── Register / Login / OTP        → PII, credential      │
│    └── /track/{order}                → Order number (guess) │
│                                                             │
│  [Authenticated Customer]                                   │
│    ├── Checkout + Payment (Midtrans) → Transaction data     │
│    ├── Voucher apply / referral      → Discount abuse       │
│    └── Profile update                → PII modification     │
│                                                             │
│  [Staff Roles: Cashier, Kitchen, Courier]                   │
│    ├── POS Manual Order              → Price manipulation   │
│    ├── Kitchen status update         → State machine abuse  │
│    └── Delivery accept/complete      → Fraudulent delivery  │
│                                                             │
│  [Admin]                                                    │
│    ├── Full CRUD (menus, users, etc.) → Privileged access   │
│    ├── Report export (PDF)           → Data exfiltration    │
│    └── User management               → Privilege escalation │
│                                                             │
│  [External Integrations]                                    │
│    ├── Midtrans Webhook (HTTP POST)  → Forged callback      │
│    ├── Google OAuth callback         → CSRF token validation│
│    └── SMTP Mail                     → Email injection      │
│                                                             │
└─────────────────────────────────────────────────────────────┘
```

---

## 2. OWASP Top 10 Risk Assessment

### A01: Broken Access Control

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Privilege escalation via role manipulation | User modifies role in profile update | Critical | Low |
| IDOR — Access other user's orders | Guess `order_id` in URL | High | Medium |
| Admin route bypass | Direct `/admin/dashboard` access | Critical | Low |

**Mitigations:**
- ✅ Role-based middleware on ALL admin/staff routes (`IsAdmin`, `IsCashier`, `IsKitchen`, `IsCourier`)
- ✅ Route groups with prefix + middleware chaining
- ✅ Order tracking uses `order_number` (20-char unique), not sequential `order_id`
- ❌ Missing: `IsCustomer` middleware is never applied to customer routes (only `auth`)
- ❌ Missing: No rate-limiting on admin login attempts

### A02: Cryptographic Failures

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Password stored in plaintext | Database breach | Critical | Low |
| OTP token predictability | Weak random generation | High | Medium |
| Session ID in URL | Log/Referrer leak | Medium | Low |

**Mitigations:**
- ✅ Password hashed via Laravel `Hasher::make()` (bcrypt)
- ✅ HTTPS forced in production (`URL::forceScheme('https')`)
- ✅ Session cookies use `HttpOnly` + `Secure` flags (Laravel default)
- ✅ CSRF token on all state-changing forms

### A03: Injection (SQLi, XSS, Command Injection)

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| SQL Injection via search fields | `?search=term' OR 1=1--` | Critical | Low |
| Stored XSS via menu description | Admin posts malicious JS | High | Low |
| Reflected XSS via error messages | URL parameter reflected unsanitized | Medium | Medium |

**Mitigations:**
- ✅ Eloquent ORM uses parameterized queries (no raw SQL concatenation)
- ✅ Blade's `{{ }}` syntax auto-escapes HTML (XSS prevention)
- ✅ Input validation rules on all `store`/`update` methods (Laravel Form Requests)
- ✅ File upload validation (mimes, max size) for menu images & thumbnails

### A04: Insecure Design

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Referral reward abuse via self-referral | Create accounts → refer yourself | High | Medium |
| Voucher unlimited usage | Reuse same voucher code multiple times | High | Low |
| Price manipulation during checkout | Modify price in session/cart | Critical | Medium |

**Mitigations:**
- ✅ OTP verification required before accessing account (prevents bot registration)
- ✅ Voucher `is_used` flag prevents reuse
- ✅ Cart items reference `menu_price` from database at checkout, not session price
- ✅ Transaction uses `DB::beginTransaction()` — rollback if any step fails
- ❌ Missing: IP-based rate limiting on registration

### A05: Security Misconfiguration

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Debug mode enabled in production | `.env` `APP_DEBUG=true` | Critical | Low |
| Default credentials | admin@qitchen.test / password | Critical | Medium |
| CORS misconfiguration | Cross-origin API access | Medium | Low |

**Mitigations:**
- ✅ `.env.example` has `APP_DEBUG=false` as production default
- ✅ Seeder creates users with known credentials (should change in production)
- ❌ Missing: No automated check for debug mode in deployment pipeline
- ❌ Missing: No `APP_ENV=production` validation in boot()

### A06: Vulnerable & Outdated Components

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Laravel version with known CVE | Outdated composer dependencies | Varies | Medium |
| Midtrans SDK vulnerability | Outdated midtrans-php package | High | Low |

**Mitigations:**
- ✅ `composer.json` specifies `^13.8` (Laravel) and `^2.6` (Midtrans)
- ✅ Regular `composer update` recommended
- ❌ Missing: No Dependabot / Renovate configuration

### A07: Identification & Authentication Failures

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Brute-force login | Repeated POST to `/login` | High | Medium |
| OTP interception | Email account compromise | High | Low |
| Session fixation | Attacker sets session ID | Medium | Low |

**Mitigations:**
- ✅ OTP required after registration before accessing account
- ✅ Password reset via OTP (not predictable reset link)
- ✅ Laravel's built-in session regeneration on login
- ❌ Missing: Rate-limiting on login endpoint (`throttle:5,1`)
- ❌ Missing: Account lockout after N failed attempts

### A08: Software & Data Integrity Failures

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Malicious package installation | Supply chain attack via `composer install` | Critical | Low |
| Voucher code guessing | Sequential or predictable codes | High | Medium |

**Mitigations:**
- ✅ Voucher codes are user-defined (admin sets the code) — unpredictable if admin chooses well
- ✅ Referral codes use format `QITCHEN-XXXX-YYYY` with random suffix
- ❌ Missing: `composer.lock` is committed (good) but no integrity verification

### A09: Security Logging & Monitoring Failures

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Undetected brute force | No failed-login logging | Medium | Medium |
| Webhook fraud | Invalid signature not monitored | High | Low |

**Mitigations:**
- ✅ Webhook logs all invalid signature attempts via `Log::warning()`
- ✅ Laravel's default log channel (`stack`) writes to daily files
- ❌ Missing: Failed login audit log
- ❌ Missing: No centralized monitoring (Sentry, Grafana)

### A10: Server-Side Request Forgery (SSRF)

| Risk | Vector | Impact | Likelihood |
|------|--------|--------|------------|
| Internal network scanning | Admin export PDF with internal URL | Medium | Low |
| Metadata endpoint access | Cloud provider metadata | Critical | Low |

**Mitigations:**
- ✅ No user-supplied URLs are fetched by the server
- ✅ DomPDF renders from local Blade views, not external URLs

---

## 3. Access Control Matrix (RBAC)

### Permissions by Role

| Feature / Route | Guest | Customer | Cashier | Kitchen | Courier | Admin |
|-----------------|-------|----------|---------|---------|--------|-------|
| `/menu` (browse) | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `/articles` (public) | ✅ | ✅ | ✅ | ✅ | ✅ | ✅ |
| `/cart` (manage) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/checkout` (process) | ✅ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/track/{order}` | ✅ | ✅ | ✅ | ❌ | ✅ | ✅ |
| `/profile` (update) | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/history` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/vouchers` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/profile/referral` | ❌ | ✅ | ❌ | ❌ | ❌ | ❌ |
| `/cashier/*` (POS) | ❌ | ❌ | ✅ | ❌ | ❌ | ❌ |
| `/kitchen/*` (board) | ❌ | ❌ | ❌ | ✅ | ❌ | ❌ |
| `/courier/*` (delivery) | ❌ | ❌ | ❌ | ❌ | ✅ | ❌ |
| `/admin/dashboard` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/menus` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/categories` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/users` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/vouchers` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/transactions` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/deliveries` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/reports` | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |
| `/admin/articles` (CRUD) | ❌ | ❌ | ❌ | ❌ | ❌ | ✅ |

### Role Enum Values (Database)

| Role | users.role | Middleware Class | Route Group |
|------|------------|-----------------|-------------|
| Admin | `admin` | `IsAdmin` | `/admin/*` |
| Cashier | `cashier` | `IsCashier` | `/cashier/*` |
| Kitchen | `kitchen` | `IsKitchen` | `/kitchen/*` |
| Courier | `courier` | `IsCourier` | `/courier/*` |
| Customer | `customer` | `IsCustomer` | *(not used in routes)* |

---

## 4. Security-Relevant Code Review

### High-Severity Findings (Fixed)

| ID | Finding | File | Severity | Fix |
|----|---------|------|----------|-----|
| SEC-001 | Referral `status` → `order_status` type mismatch | `app/Models/Order.php` | 🔴 Critical | Changed to `order_status` |
| SEC-002 | Webhook looks for `REF-BONUS` but seeder creates `REF-REWARD-2026` | `MidtransWebhookController.php` | 🔴 Critical | Changed to `REF-REWARD-2026` |
| SEC-003 | Delivery address not saved during checkout | `CheckoutController.php` | 🔴 Critical | Added Delivery record creation |
| SEC-004 | Voucher validation doesn't accept `bogo` type | `Admin/VoucherController.php` | 🟡 High | Added `bogo` to validation rules |
| SEC-005 | Role validator includes `super_admin` not in DB enum | `Admin/UserController.php` | 🟡 High | Removed invalid role |

---

## 5. Security Verification Checklist (Pre-Release)

### Authentication & Session

- [x] Password hashed with bcrypt (Laravel default)
- [x] Google OAuth uses state parameter (CSRF prevention)
- [x] Session regenerated on login
- [x] Cookie HttpOnly + Secure flags
- [ ] Rate-limiting on login (`throttle:5,1` middleware)
- [ ] Account lockout after 5 failed attempts
- [ ] Session timeout after inactivity (configurable)

### Access Control

- [x] All admin routes behind `isAdmin` middleware
- [x] All cashier routes behind `isCashier` middleware
- [x] All kitchen routes behind `isKitchen` middleware
- [x] All courier routes behind `isCourier` middleware
- [ ] Apply `isCustomer` middleware to customer-only routes (profile, vouchers)
- [ ] Verify IDOR: Order tracking should check `user_id` ownership

### Input Validation

- [x] All form inputs validated via `$request->validate()`
- [x] File uploads restricted to images (mimes:jpeg,png,jpg)
- [x] File size limited to 2MB
- [ ] Add HTML entity encoding check for stored content (articles, descriptions)

### CSRF Protection

- [x] `@csrf` on all Blade forms
- [x] Webhook endpoint excluded from CSRF (uses SHA-512 signature instead)
- [x] API-stateless endpoints use token-based auth

### Payment Security

- [x] Webhook signature verification (SHA-512)
- [x] Idempotency check (prevents double-processing)
- [x] Transaction atomicity (`DB::beginTransaction/commit/rollback`)
- [ ] Midtrans production credentials stored in `.env`, never in code
- [ ] PCI compliance: Card data never touches Qitchen server (handled by Midtrans Snap)

### Database

- [x] Soft deletes on all resource tables
- [x] Foreign key constraints enabled
- [x] No raw SQL queries (Eloquent only)
- [ ] Database backup strategy documented
- [ ] `.env` excluded from version control

### Infrastructure

- [x] HTTPS forced in production
- [x] `APP_DEBUG=false` in production
- [ ] Web Application Firewall (WAF) — Cloudflare or similar
- [ ] Fail2ban for SSH/brute force protection
- [ ] Regular dependency updates (`composer update` / Dependabot)

### Logging & Monitoring

- [x] Webhook invalid signature logged
- [ ] Failed login attempts logged
- [ ] Admin actions logged (audit trail)
- [ ] Error tracking (Sentry / Flare integration)

> **Legend:** `[x]` = Implemented | `[ ]` = Not yet implemented (todo for production readiness)

---

## 6. Data Classification & Sensitivity

| Data Type | Classification | Storage | Encryption |
|-----------|---------------|---------|------------|
| Password | Critical | `users.password` (bcrypt hash) | At rest (hashed), In transit (HTTPS) |
| Email | High | `users.email` | At rest (plaintext — required for auth), In transit (HTTPS) |
| Phone | High | `users.phone` | At rest (plaintext), In transit (HTTPS) |
| Address + Coordinates | High | `deliveries` table | At rest (plaintext), In transit (HTTPS) |
| Payment transaction ID | Critical | `payments.midtrans_transaction_id` | At rest (plaintext), In transit (HTTPS) |
| Order data | Medium | `orders`, `order_items` | At rest (plaintext), In transit (HTTPS) |
| Session ID | High | Browser cookie | HttpOnly + Secure + SameSite |
| Google OAuth token | High | Session (temporary) | In transit (HTTPS) |

---

## 7. Incident Response Plan (Abbreviated)

| Severity | Definition | Response Time | Action |
|----------|------------|---------------|--------|
| 🔴 Critical | Data breach, payment compromise, unauthorized admin access | Immediate (1 hour) | Take app offline, reset all credentials, notify affected users, forensic analysis |
| 🟡 High | Account takeover, voucher abuse, persistent XSS | 24 hours | Disable affected accounts, rollback malicious changes, patch vulnerability |
| 🟢 Medium | Rate-limiting bypass, spam registration, IDOR without data access | 72 hours | Implement missing security control, audit logs for past 7 days |
| 🔵 Low | Missing headers, minor config issues | Next sprint | Add to backlog, remediate in upcoming release |

---

*Back to [docs/](./)*