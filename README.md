# JuanArt – Art Marketplace & Commission Platform

PHP 8.x + MySQL full-stack marketplace: browse artworks, cart/checkout, artist KYC, subscription tiers, commission escrow, admin panel.

## Requirements

- PHP 8.x with PDO MySQL
- MySQL 5.7+ or MariaDB
- Web server (Apache with mod_rewrite or PHP built-in)

## Setup

1. **Create database**
   ```sql
   CREATE DATABASE juanart CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```

2. **Configure**
   - Copy or edit `config/database.php`: set host, db name, user, password.
   - Or use env: `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`.

3. **Import schema and seeds**
   - In phpMyAdmin or MySQL client, run in order:
     - `sql/schema.sql`
     - `sql/seeds.sql`

4. **Document root**
   - Point your vhost document root to the `public/` folder (so `index.php` runs from there).
   - Or run from project root: `php -S localhost:8000 -t public`

5. **Optional: first admin user**
   - Register normally, then in MySQL:
     ```sql
     UPDATE users SET role_id = 3 WHERE email = 'your@email.com';
     ```
   - Or uncomment the demo admin line in `sql/seeds.sql` (password: `admin123`) and re-run seeds once.

## Structure

- `config/` – database and app config
- `public/` – `index.php` (front controller), `actions.php`, CSS/JS, `.htaccess`
- `src/` – Database, Auth, RoleGuard
- `sql/` – schema.sql, seeds.sql
- `views/` – PHP templates (dashboard, auth, cart, artist, commission, admin)
- `uploads/` – for KYC/artwork/delivery files (optional; currently URLs in DB)

## Entry flow

1. **Dashboard first** – visitors see home with browse, categories, artworks; no login required.
2. **Auth when needed** – Login/Register for cart, commissions, “Become artist”, admin.
3. **Roles** – Client (default), Artist (after KYC + subscribe), Admin.

## Payments

Placeholder only: GCash, PayMaya, Bank, Card, PayPal are selected and a transaction reference can be stored. Replace with real gateway integration for production.
