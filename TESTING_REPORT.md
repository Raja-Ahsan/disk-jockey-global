# Feature Testing Report — Disk Jockey Global

**Date:** 2026-07-15  
**Environment:** Local XAMPP / MySQL `disk_jockey_global` / `http://127.0.0.1:8001`

## Implemented Features

### 1. Navigation
- Added **Marketplace** and **Plan My Event** to desktop + mobile nav
- Active state highlighting via `.is-active` on current route

### 2. Marketplace (separate from Merchandise)
- Tables: `marketplace_categories`, `marketplace_products`, variations + attributes
- Public listing/search/category filter/pagination + product detail + gallery
- Session cart (`marketplace_cart`) + Stripe checkout path (`source=marketplace`)
- Admin CRUD for products & categories (role: admin only)
- Seeded sample products

### 3. Plan My Event
- Public form reusing Book-a-DJ fields + shared `BookDjValidation`
- Stored in `plan_my_event_requests` (not booking tables)
- Email via `PLAN_MY_EVENT_EMAIL` / `config('services.plan_my_event.email')`
- Admin list/search/filter/status/notes/delete (no merge with Book My DJ)

### 4. Google Calendar (DJ profiles)
- Table `dj_google_calendars` with encrypted tokens
- Connect / callback / resync / disconnect (DJ role)
- Admin can view status + disconnect
- Booking request + legacy booking store check FreeBusy when connected
- Friendly message when busy: *"This DJ is unavailable for the selected date..."*

---

## Verified Scenarios

| Scenario | Result |
|----------|--------|
| Home page loads | Pass (200) |
| Merchandise still works | Pass (200) |
| Marketplace listing | Pass (after route name fix) |
| Marketplace products seeded | Pass (3 products) |
| Plan My Event page | Pass (200) |
| Plan My Event submit + DB insert | Pass (row `status=new`) |
| Route registration marketplace/admin/google | Pass |
| Migrations applied | Pass |
| Admin sidebar entries present | Pass |
| DJ profile calendar UI | Pass (code + view) |
| Book My DJ flow unchanged (code path) | Pass (shared validation only) |

## Manual / Config-dependent

| Scenario | Notes |
|----------|--------|
| Google OAuth connect | Requires `GOOGLE_CLIENT_*` in `.env` |
| Calendar busy blocking | Requires connected DJ calendar |
| Marketplace Stripe checkout | Requires existing Stripe keys |
| Plan My Event email delivery | Set `PLAN_MY_EVENT_EMAIL`; mailer currently `log` locally |
| Certification workflow | Not present in codebase — N/A |

## Roles

- **Guest:** Marketplace browse, Plan My Event submit
- **User:** Marketplace cart/checkout (auth), Book My DJ
- **DJ:** Google Calendar connect/resync/disconnect
- **Admin:** Marketplace CRUD, Plan My Event enquiries, disconnect DJ calendar

## Env vars

See `FEATURES_ENV.md`.
