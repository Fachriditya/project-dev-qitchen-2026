# Architectural Decision Records (ADR) — Qitchen 2026

> **Status:** Active  
**Owner:** Muhammad Fachriditya  
**Last Updated:** 2026  
**Format:** [ADR Nathaniel](https://github.com/joelparkerhenderson/architecture-decision-record)

---

## ADR-001: Backend Framework — Laravel

| Field | Value |
|-------|-------|
| **ID** | ADR-001 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Foundation |

### Context

Qitchen needs a full-stack web framework that supports MVC architecture, Eloquent ORM, built-in authentication, role-based middleware, and rapid development for a restaurant management system. The alternatives considered were Laravel (PHP), Django (Python), and Next.js (TypeScript).

### Decision

Use **Laravel 13.x** as the backend framework.

### Consequences

#### ✅ Pros
- Built-in authentication scaffolding (Breeze)
- Eloquent ORM simplifies complex restaurant relationships (Order → Items → Menu → Category)
- Middleware system supports role-based access control natively
- Queue system (Horizon) ready for future WebSocket/notification features
- Blade templating allows server-side rendering without SPA complexity
- Rich ecosystem: Socialite (OAuth), DomPDF, Midtrans SDK

#### ❌ Cons
- PHP is less performant than Go/Rust for high-concurrency scenarios
- Blade + Tailwind = server-rendered HTML; no SPA interactivity without Alpine.js
- Session-based cart doesn't persist across devices (user must stay on same browser)

---

## ADR-002: Frontend Rendering — Blade + TailwindCSS (No SPA)

| Field | Value |
|-------|-------|
| **ID** | ADR-002 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Presentation |

### Context

Qitchen targets restaurant staff (cashiers, kitchen, couriers) and customers. Staff need fast page loads on low-end devices. A heavy SPA like React/Vue would add complexity for a team of one. The UI also needs to be responsive for mobile.

### Decision

Use **Blade** server-side templates with **TailwindCSS** utility classes. No JS framework. Minimal Alpine.js for interactive components (cart, checkout forms).

### Consequences

#### ✅ Pros
- Fast initial page load (no JS bundle to download)
- SEO-friendly for public pages (menu, articles)
- Single developer can maintain full stack
- TailwindCSS guarantees responsive design out of the box

#### ❌ Cons
- Kitchen board requires full page reload (30s polling) — no WebSocket real-time updates
- No client-side state management — forms reset on validation error if not careful
- Future mobile app would need a separate API layer

---

## ADR-003: Database — MySQL with Soft Deletes

| Field | Value |
|-------|-------|
| **ID** | ADR-003 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Data Layer |

### Context

Restaurant data is mission-critical: losing orders, payments, or user data is unacceptable. The schema involves complex relational joins (Order → Items → Menu → Category → Payment → Delivery). Development uses SQLite for portability; production needs MySQL.

### Decision

Use **MySQL** for production, **SQLite** for development. Implement **SoftDeletes** on ALL resource tables.

### Consequences

#### ✅ Pros
- Soft deletes prevent permanent data loss (".deleted_at" column on all 11 tables)
- Foreign key constraints enforce referential integrity (e.g., cannot delete menu with active orders)
- MySQL's JSON support ready for future analytics
- SQLite in dev means zero database setup for new contributors

#### ❌ Cons
- Soft deletes require additional query scoping (`->whereNull('deleted_at')`)
- Schema migrations must be carefully ordered (foreign keys reference earlier tables)
- MySQL full-text search not as capable as PostgreSQL tsvector

---

## ADR-004: Authentication — Email/Password + Google OAuth + OTP Verification

| Field | Value |
|-------|-------|
| **ID** | ADR-004 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Security |

### Context

Customers need a frictionless sign-up, but the system must prevent fake accounts (which could abuse referral rewards). Restaurants also need role-based access for staff. Options: pure email/password, phone OTP, Google-only, or hybrid.

### Decision

Implement **3-factor enrollment**: Register with email/password OR Google OAuth → OTP verification via email → Role-based access. Password reset uses OTP (not email link) for faster UX.

### Consequences

#### ✅ Pros
- OTP prevents bot registrations (referral fraud mitigation)
- Google OAuth reduces password fatigue for customers
- Roles map directly to middleware for clean authorization
- OTP reset is faster than email-link flow (no click-through delay)

#### ❌ Cons
- OTP delivery depends on SMTP reliability (downtime = no registration)
- No phone SMS OTP (email only) — customers must check inbox
- Google OAuth dependency — if Google API changes, login breaks

---

## ADR-005: Cart Implementation — Server-Side Session Storage

| Field | Value |
|-------|-------|
| **ID** | ADR-005 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Customer Feature |

### Context

The cart needs to persist during a customer's browsing session. Options: localStorage (client-side), session (server-side), or database (persistent across logins). Restaurants have low item counts (menu < 100 items), so performance is not a concern.

### Decision

Store cart data in **PHP session** (server-side).

### Consequences

#### ✅ Pros
- Simple implementation — no extra database table required
- Cart data is not visible or manipulable by the client (security)
- Works for guest users without requiring login
- Cleans up automatically when session expires

#### ❌ Cons
- Cart does not persist across devices or browsers
- Lost if session expires or cache is cleared
- Not suitable for multi-device restaurant scenario

---

## ADR-006: Payment Gateway — Midtrans (Snap API)

| Field | Value |
|-------|-------|
| **ID** | ADR-006 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Payment |

### Context

Qitchen requires Indonesian payment methods: QRIS (QR code), Virtual Account (BCA/BNI/Mandiri), and cash. Midtrans is the market leader in Indonesia for online payments. Alternatives: Xendit, iPaymu, or manual bank transfer.

### Decision

Integrate **Midtrans Snap API** (formerly Veritrans) for online payments. Cash payments handled through cashier POS flow (offline).

### Consequences

#### ✅ Pros
- Supports QRIS + Multiple Virtual Accounts out of the box
- Snap popup mode allows inline payment without redirect
- Webhook (HTTP notification) updates payment status automatically
- Sandbox environment available for development

#### ❌ Cons
- Requires merchant account approval (can take days)
- Transaction fees per payment (typically 2-3% for QRIS)
- API dependency — if Midtrans is down, online payments fail

---

## ADR-007: Payment Status Synchronization — Webhook + Signature Verification

| Field | Value |
|-------|-------|
| **ID** | ADR-007 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Payment |

### Context

After a customer pays via Midtrans Snap, the order status must update from "pending" to "paid" automatically. Polling the Midtrans API is wasteful; callback-based webhook is standard but needs security verification.

### Decision

Use **Midtrans Webhook (HTTP POST)** with **SHA-512 signature verification**. The webhook endpoint is public (no CSRF) but validates the `signature_key` hash before processing.

### Consequences

#### ✅ Pros
- Instant payment notification (no polling delay)
- SHA-512 verification prevents forged callbacks
- Idempotency check (`order_status == 'paid'` guard) prevents double-processing
- Logs all webhook attempts for audit trail

#### ❌ Cons
- Webhook URL must be publicly accessible (requires deployed server)
- Midtrans may send duplicate notifications (handled via idempotency check)
- No queue — webhook processed synchronously; if database is slow, Midtrans may timeout

---

## ADR-008: Role-Based Access Control — Middleware per Role (5 Roles)

| Field | Value |
|-------|-------|
| **ID** | ADR-008 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Authorization |

### Context

Restaurant operations require strict separation of concerns: customers should not access admin dashboards, couriers should not edit menus, etc. Laravel's built-in auth guards + custom middleware offers a clean way to enforce this.

### Decision

Define **5 roles** as an enum column on `users` table: `admin`, `cashier`, `kitchen`, `courier`, `customer`. Create **5 middleware classes** (`IsAdmin`, `IsCashier`, `IsKitchen`, `IsCourier`, `IsCustomer`) that check `auth()->user()->role`.

### Consequences

#### ✅ Pros
- Explicit, readable middleware names
- Each route group simply chains `['auth', 'isAdmin']` — no boolean logic
- Role column is indexed for fast queries
- Easy to add new roles (just create a new middleware class)

#### ❌ Cons
- Hardcoded role strings — no dynamic role management (new roles require code change)
- Middleware throws 403 with no redirect — user sees error page instead of being redirected to their dashboard
- `IsCustomer` middleware exists but is **never used** in routes (customer routes only use `auth` middleware)

---

## ADR-009: Map & Location Tracking — Leaflet.js + OpenStreetMap

| Field | Value |
|-------|-------|
| **ID** | ADR-009 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Delivery Feature |

### Context

Couriers need to see delivery destinations on a map. Customers need to track courier location. Options: Google Maps (requires API key, paid after $200 credit), Mapbox (paid), or OpenStreetMap (free).

### Decision

Use **Leaflet.js** (frontend library) with **OpenStreetMap** tile layer. No API key required. Courier location updates via browser Geolocation API.

### Consequences

#### ✅ Pros
- Zero cost — no API usage fees
- No API key management or billing concerns
- Leaflet is lightweight (~40KB gzipped)
- OpenStreetMap tiles are community-maintained and reliable

#### ❌ Cons
- OpenStreetMap tiles are less detailed than Google Maps (no indoor views, fewer POIs)
- Browser Geolocation API requires HTTPS (localhost works, but production must have SSL)
- No turn-by-turn navigation — just a visual map
- Background location tracking requires mobile app (not available in browser)

---

## ADR-010: PDF Report Generation — Laravel DomPDF

| Field | Value |
|-------|-------|
| **ID** | ADR-010 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Reporting |

### Context

Admin needs to export sales reports as PDF for accounting and audit. Options: DomPDF (PHP, server-rendered), Browsershot (Puppeteer, heavier), or client-side print-to-PDF.

### Decision

Use **barryvdh/laravel-dompdf** — a Laravel wrapper for DomPDF.

### Consequences

#### ✅ Pros
- Server-side rendering — PDF is generated on the server, not reliant on client browser
- Uses Blade views as templates — reuse existing report view structure
- Simple API: `PDF::loadView('report', $data)->download()`
- No external service dependency (unlike Puppeteer which needs Chrome)

#### ❌ Cons
- DomPDF has limited CSS support (Flexbox not fully supported, Grid unsupported)
- Large reports with many orders may timeout or exceed memory
- No streaming — entire PDF is built in memory before download

---

## ADR-011: Referral System — Event-Driven Reward Distribution

| Field | Value |
|-------|-------|
| **ID** | ADR-011 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Loyalty |

### Context

When a referred customer completes their first order, the referrer should receive a reward voucher. This needs to happen reliably without manual intervention. Two approaches: synchronous (in controller/webhook) or event-driven (Laravel Events + Listeners).

### Decision

Implement referral reward distribution **synchronously** in two places:
1. **Order model `updated` event** — when order status changes to `completed`
2. **Webhook controller** — when payment is confirmed as `paid`

Both check `$completedOrdersCount === 1` to ensure first-order-only reward.

### Consequences

#### ✅ Pros
- Immediate reward distribution (no queue delay)
- Dual guard prevents reward bypass (both Order model + Webhook check)
- `referred_by` column on users table simplifies query
- Referral history stored in `referrals` table for audit

#### ❌ Cons
- Logic duplicated in two places (Order model event + MidtransWebhookController) — violates DRY
- No queuing — if voucher creation fails during webhook, reward is lost
- Race condition: if webhook and model event fire simultaneously, reward could be given twice (mitigated by the count check, but still risky)

---

## ADR-012: No WebSocket / Real-Time — Polling Fallback

| Field | Value |
|-------|-------|
| **ID** | ADR-012 |
| **Date** | 2026-05-01 |
| **Status** | ✅ Accepted |
| **Scope** | Infrastructure |

### Context

Kitchen board, order tracking, and courier location ideally update in real-time. Full WebSocket support (Laravel Reverb or Pusher) adds significant infrastructure and cost overhead for a personal project.

### Decision

Use **polling** (periodic page reload / AJAX polling) instead of WebSockets. Kitchen board refreshes every 30 seconds. Courier location is sent via HTTP POST, not streamed.

### Consequences

#### ✅ Pros
- Zero infrastructure cost — no WebSocket server needed
- Simple implementation — `setTimeout(window.location.reload(), 30000)`
- Works on all hosting environments (shared hosting, VPS)
- No connection state management

#### ❌ Cons
- 30-second delay on kitchen board updates
- Higher server load due to repeated page requests
- Not truly "real-time" — courier position updates are "last known"
- Bandwidth waste from full page reloads instead of partial DOM updates

---

## ADR Summary Table

| ID | Decision | Status | Priority |
|----|----------|--------|----------|
| ADR-001 | Laravel 13.x Framework | ✅ Accepted | Critical |
| ADR-002 | Blade + TailwindCSS (No SPA) | ✅ Accepted | Critical |
| ADR-003 | MySQL + Soft Deletes | ✅ Accepted | Critical |
| ADR-004 | Email/Password + Google OAuth + OTP | ✅ Accepted | Critical |
| ADR-005 | Server-Side Session Cart | ✅ Accepted | High |
| ADR-006 | Midtrans Snap API | ✅ Accepted | Critical |
| ADR-007 | Webhook + SHA-512 Verification | ✅ Accepted | Critical |
| ADR-008 | Middleware per Role (5 Roles) | ✅ Accepted | Critical |
| ADR-009 | Leaflet.js + OpenStreetMap | ✅ Accepted | High |
| ADR-010 | Laravel DomPDF | ✅ Accepted | Medium |
| ADR-011 | Event-Driven Referral Rewards | ✅ Accepted | High |
| ADR-012 | Polling (No WebSocket) | ✅ Accepted | Medium |

---

*Back to [docs/](./)*