#!/usr/bin/env bash
# ═══════════════════════════════════════════════════════════════
#  Perla Vita v8 — Arch Linux Setup & Run Script
#  Stack: PHP built-in server + MariaDB (MySQL)
# ═══════════════════════════════════════════════════════════════
set -e

SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PROJECT_ROOT="$(cd "$SCRIPT_DIR/.." && pwd)"
HOST="localhost"
PORT="8080"
URL="http://${HOST}:${PORT}"

# ── DB settings (must match php/base_de_donnees.php) ──────────────────────
DB_NAME="perlavita"
DB_USER="root"
DB_PASS=""          # Change this if your MariaDB root has a password

echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║      Perla Vita v8 — Arch Linux Setup            ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""

# ── 1. PHP ────────────────────────────────────────────────────
if ! command -v php &>/dev/null; then
  echo "► PHP not found. Installing via pacman…"
  sudo pacman -Sy --noconfirm php php-pdo php-mysqlnd || {
    echo "✗ pacman failed. Run: sudo pacman -Sy php php-pdo php-mysqlnd"; exit 1; }
  echo "✔ PHP installed."
else
  PHP_VER=$(php -r 'echo PHP_MAJOR_VERSION.".".PHP_MINOR_VERSION;')
  echo "✔ PHP $PHP_VER found."
fi

# ── 2. PHP extensions: pdo_mysql ─────────────────────────────
if ! php -m 2>/dev/null | grep -qi 'pdo_mysql'; then
  echo "► pdo_mysql not loaded."
  echo "  Installing php-pdo / php-mysqlnd…"
  sudo pacman -Sy --noconfirm php-pdo php-mysqlnd 2>/dev/null || true
  # Enable in php.ini if present but commented out
  PHP_INI=$(php --ini | awk '/Loaded Configuration/{print $NF}')
  if [ -f "$PHP_INI" ]; then
    sudo sed -i 's/^;extension=pdo_mysql/extension=pdo_mysql/' "$PHP_INI"
    sudo sed -i 's/^;extension=mysqli/extension=mysqli/'       "$PHP_INI"
  fi
  echo "✔ pdo_mysql enabled."
fi

# ── 3. MariaDB ────────────────────────────────────────────────
if ! command -v mysqld &>/dev/null && ! command -v mariadbd &>/dev/null; then
  echo "► MariaDB not found. Installing…"
  sudo pacman -Sy --noconfirm mariadb || { echo "✗ Failed. Run: sudo pacman -Sy mariadb"; exit 1; }
  sudo mariadb-install-db --user=mysql --basedir=/usr --datadir=/var/lib/mysql
  echo "✔ MariaDB installed."
fi

# ── 4. Ensure MariaDB is running ─────────────────────────────
if ! mysqladmin -u root ${DB_PASS:+-p"$DB_PASS"} ping &>/dev/null 2>&1; then
  echo "► Starting MariaDB…"
  sudo systemctl start mariadb 2>/dev/null || sudo mariadbd-safe --user=mysql &
  sleep 2
  if ! mysqladmin -u root ${DB_PASS:+-p"$DB_PASS"} ping &>/dev/null 2>&1; then
    echo "✗ Could not connect to MariaDB."
    echo "  Try: sudo systemctl start mariadb"
    exit 1
  fi
fi
echo "✔ MariaDB is running."

# ── 5. Create database & tables ───────────────────────────────
echo "► Running SQL schema…"
if [ -n "$DB_PASS" ]; then
  mysql -u "$DB_USER" -p"$DB_PASS" < "$PROJECT_ROOT/sql/perlavita.sql"
else
  mysql -u "$DB_USER" < "$PROJECT_ROOT/sql/perlavita.sql"
fi
echo "✔ Database ready."

# ── 6. Validate project structure ─────────────────────────────
echo "► Validating project files…"
REQUIRED=(
  "index.php" "catalogue.php" "commande.php" "confirmation.php"
  "php/composants.php" "php/produits.php" "php/authentification.php" "php/base_de_donnees.php"
  "css/style.css" "script/app.js" "sql/perlavita.sql"
)
ALL_OK=true
for f in "${REQUIRED[@]}"; do
  [ ! -f "$PROJECT_ROOT/$f" ] && { echo "  ✗ Missing: $f"; ALL_OK=false; }
done
[ "$ALL_OK" = false ] && exit 1
echo "✔ All files present."

# ── 7. Port check ─────────────────────────────────────────────
if ss -tlnp 2>/dev/null | grep -q ":${PORT} "; then
  PORT="8081"; URL="http://${HOST}:${PORT}"
fi

# ── 8. Launch ────────────────────────────────────────────────
echo ""
echo "╔══════════════════════════════════════════════════╗"
echo "║  Perla Vita is ready!                            ║"
echo "╚══════════════════════════════════════════════════╝"
echo ""
echo "  URL  → $URL"
echo "  DB   → mysql://root@localhost/$DB_NAME"
echo "  Root → $PROJECT_ROOT"
echo ""
echo "  Admin login: admin@perlavita.com / admin123"
echo "  Press Ctrl+C to stop."
echo ""
command -v xdg-open &>/dev/null && (sleep 1 && xdg-open "$URL") &
cd "$PROJECT_ROOT"
exec php -S "${HOST}:${PORT}" -t .
