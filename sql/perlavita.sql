-- ═══════════════════════════════════════════════════════════════
--  Perla Vita v8 — MySQL Schema
--  Compatible with: Arch Linux (MariaDB/MySQL) & Windows XAMPP
-- ═══════════════════════════════════════════════════════════════

CREATE DATABASE IF NOT EXISTS perlavita
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE perlavita;

-- ─── USERS ────────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS users (
  id         VARCHAR(40)  NOT NULL PRIMARY KEY,
  firstName  VARCHAR(80)  NOT NULL,
  lastName   VARCHAR(80)  NOT NULL,
  email      VARCHAR(180) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  role       ENUM('admin','user') NOT NULL DEFAULT 'user',
  avatar     VARCHAR(255) NOT NULL DEFAULT '',
  created    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── PRODUCTS (admin-created / custom only) ───────────────────
--  Base products live in php/products.php (static array).
--  This table stores only the products created via the Admin panel.
CREATE TABLE IF NOT EXISTS products_custom (
  id         INT          NOT NULL AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(255) NOT NULL,
  category   VARCHAR(80)  NOT NULL DEFAULT 'necklaces',
  price      DECIMAL(10,2) NOT NULL DEFAULT 0,
  icon       VARCHAR(10)  NOT NULL DEFAULT '✦',
  rating     TINYINT      NOT NULL DEFAULT 5,
  badge      VARCHAR(80)  NOT NULL DEFAULT '',
  material   VARCHAR(255) NOT NULL DEFAULT '',
  created    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── COUPONS ──────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS coupons (
  code       VARCHAR(50)  NOT NULL PRIMARY KEY,
  type       ENUM('percent','fixed') NOT NULL DEFAULT 'percent',
  value      DECIMAL(10,2) NOT NULL DEFAULT 10,
  minOrder   DECIMAL(10,2) NOT NULL DEFAULT 0,
  active     TINYINT(1)   NOT NULL DEFAULT 1,
  created    DATE         NOT NULL DEFAULT (CURRENT_DATE)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── ORDERS ───────────────────────────────────────────────────
CREATE TABLE IF NOT EXISTS orders (
  id         VARCHAR(40)  NOT NULL PRIMARY KEY,
  userId     VARCHAR(40)  NOT NULL,
  data       JSON         NOT NULL COMMENT 'Full order payload (items, shipping, totals)',
  created    DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_user (userId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ─── DEFAULT ADMIN (password: admin123) ───────────────────────
--  Only inserted when the users table is empty.
INSERT IGNORE INTO users (id, firstName, lastName, email, password, role, created)
SELECT
  'pv_default_admin',
  'Admin',
  'Perla Vita',
  'admin@perlavita.com',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', -- bcrypt of "admin123"
  'admin',
  NOW()
WHERE NOT EXISTS (SELECT 1 FROM users LIMIT 1);

-- ─── SAMPLE COUPON ────────────────────────────────────────────
INSERT IGNORE INTO coupons (code, type, value, minOrder, active, created)
VALUES ('WELCOME10', 'percent', 10.00, 100.00, 1, CURRENT_DATE);
