# Perla Vita v8 — MySQL Setup Guide

## What changed from v7
- All data (users, orders, coupons, custom products) moved from JSON files → **MySQL/MariaDB**
- New file: `php/db.php` — all database functions
- New file: `sql/perlavita.sql` — schema + default admin seed
- Updated: `php/auth.php`, `php/products.php`, `admin.php`
- Removed: `php/users.json`, `php/orders.json`, `php/coupons.json`, `php/products_custom.json`

---

## Option A — Arch Linux (PHP built-in server + MariaDB)

### Quick start
```bash
chmod +x script/run-arch.sh
./script/run-arch.sh
```

The script will:
1. Install PHP + `pdo_mysql` if missing (via `pacman`)
2. Install MariaDB if missing and run `mariadb-install-db`
3. Start MariaDB if it isn't running
4. Create the `perlavita` database and tables
5. Launch PHP's built-in server on `http://localhost:8080`

### Manual DB setup (if the script can't connect)
```bash
sudo systemctl start mariadb
mysql -u root < sql/perlavita.sql
php -S localhost:8080 -t .
```

### If your MariaDB root has a password
Edit `script/run-arch.sh` and `php/db.php`:
```php
// php/db.php
define('DB_PASS', 'your_password_here');
```

---

## Option B — Windows XAMPP

### Quick start
1. Open XAMPP Control Panel and start **Apache** and **MySQL**
2. Double-click `script\run-windows-xampp.bat`

The script will:
1. Detect XAMPP location automatically
2. Create the `perlavita` database and tables via `mysql.exe`
3. Copy all files to `C:\xampp\htdocs\perlavita\`
4. Open `http://localhost/perlavita/` in your browser

### Manual DB setup
1. Open phpMyAdmin at `http://localhost/phpmyadmin`
2. Click **Import** → choose `sql/perlavita.sql` → Go

### If you set a MySQL root password in XAMPP
Edit `php/db.php`:
```php
define('DB_PASS', 'your_password_here');
```
Also update `script\run-windows-xampp.bat`:
```batch
set "DB_PASS=your_password_here"
```

---

## Default admin credentials
```
Email:    admin@perlavita.com
Password: admin123
```
Change these immediately via the Admin panel → Users tab.

---

## File structure
```
perlavita_v8/
├── php/
│   ├── db.php           ← MySQL connection + all data functions
│   ├── auth.php         ← Auth logic (uses db.php)
│   ├── products.php     ← Static product catalog
│   ├── partials.php     ← Shared HTML (nav, head, footer)
│   └── images.php       ← Product image URLs
├── sql/
│   └── perlavita.sql    ← Schema (run once to set up DB)
├── script/
│   ├── run-arch.sh      ← Arch Linux auto-setup
│   └── run-windows-xampp.bat  ← Windows XAMPP auto-setup
└── uploads/
    └── avatars/         ← User profile pictures (file-based, unchanged)
```

---

## Troubleshooting

**Blank page / "Database connection failed"**
- Make sure MySQL/MariaDB is running
- Check credentials in `php/db.php` (`DB_USER`, `DB_PASS`)
- Run `sql/perlavita.sql` to create the database

**`pdo_mysql` not found (Arch)**
```bash
sudo pacman -Sy php-pdo php-mysqlnd
# Then enable in /etc/php/php.ini:
# uncomment: extension=pdo_mysql
```

**Port 8080 already in use (Arch)**
The script auto-tries 8081. Or start manually:
```bash
php -S localhost:8082 -t .
```
