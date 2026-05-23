#!/bin/bash
# =============================================================
#  Kroxy Panel — One-Command Installer
#  Powered by Pterodactyl
# =============================================================

set -e

RED='\033[0;31m'; GREEN='\033[0;32m'; YELLOW='\033[1;33m'
CYAN='\033[0;36m'; WHITE='\033[1;37m'; DIM='\033[2m'; NC='\033[0m'
BOLD='\033[1m'

banner() {
    echo ""
    echo -e "${WHITE}${BOLD}"
    echo "  ██╗  ██╗██████╗  ██████╗ ██╗  ██╗██╗   ██╗"
    echo "  ██║ ██╔╝██╔══██╗██╔═══██╗╚██╗██╔╝╚██╗ ██╔╝"
    echo "  █████╔╝ ██████╔╝██║   ██║ ╚███╔╝  ╚████╔╝ "
    echo "  ██╔═██╗ ██╔══██╗██║   ██║ ██╔██╗   ╚██╔╝  "
    echo "  ██║  ██╗██║  ██║╚██████╔╝██╔╝ ██╗   ██║   "
    echo "  ╚═╝  ╚═╝╚═╝  ╚═╝ ╚═════╝ ╚═╝  ╚═╝   ╚═╝   "
    echo -e "${NC}"
    echo -e "${DIM}  Premium Black & White Panel — Powered by Pterodactyl${NC}"
    echo -e "${DIM}  ─────────────────────────────────────────────────────${NC}"
    echo ""
}

step() { echo -e "  ${CYAN}→${NC} ${WHITE}$1${NC}"; }
ok()   { echo -e "  ${GREEN}✓${NC} $1"; }
warn() { echo -e "  ${YELLOW}⚠${NC}  $1"; }
err()  { echo -e "  ${RED}✗${NC}  $1"; exit 1; }
hr()   { echo -e "  ${DIM}────────────────────────────────────────${NC}"; }

banner

# ── Root check ──────────────────────────────────────────────
if [ "$EUID" -ne 0 ]; then
    err "Please run this installer as root (sudo bash install.sh)"
fi

# ── Collect inputs ───────────────────────────────────────────
echo -e "  ${BOLD}Setup — Admin Account${NC}"
hr

read -rp "  Admin username   : " ADMIN_USER
ADMIN_EMAIL="admin@gmail.com"
echo -e "  Admin email      : ${DIM}admin@gmail.com (fixed)${NC}"

while true; do
    read -rsp "  Admin password   : " ADMIN_PASS; echo
    read -rsp "  Confirm password : " ADMIN_PASS2; echo
    [ "$ADMIN_PASS" = "$ADMIN_PASS2" ] && break
    warn "Passwords do not match. Try again."
done

echo ""
echo -e "  ${BOLD}Setup — Database${NC}"
hr
read -rp "  DB username      : " DB_USER
while true; do
    read -rsp "  DB password      : " DB_PASS; echo
    read -rsp "  Confirm DB pass  : " DB_PASS2; echo
    [ "$DB_PASS" = "$DB_PASS2" ] && break
    warn "Passwords do not match. Try again."
done
DB_NAME="panel"
echo -e "  DB name          : ${DIM}panel (default)${NC}"

echo ""
echo -e "  ${BOLD}Setup — Domain / URL${NC}"
hr
read -rp "  Panel URL (e.g. https://panel.yourdomain.com) : " APP_URL
APP_URL="${APP_URL%/}"

echo ""
hr
echo -e "  ${BOLD}Summary${NC}"
echo -e "  Admin user   : ${WHITE}${ADMIN_USER}${NC}"
echo -e "  Admin email  : ${WHITE}${ADMIN_EMAIL}${NC}"
echo -e "  DB user      : ${WHITE}${DB_USER}${NC}"
echo -e "  DB name      : ${WHITE}${DB_NAME}${NC}"
echo -e "  Panel URL    : ${WHITE}${APP_URL}${NC}"
hr
echo ""
read -rp "  Continue installation? [y/N] : " CONFIRM
if [[ ! "$CONFIRM" =~ ^[Yy]$ ]]; then
    echo -e "  ${YELLOW}Installation cancelled.${NC}"
    exit 0
fi
echo ""

# ── System update ────────────────────────────────────────────
step "Updating system packages..."
apt-get update -qq && apt-get upgrade -y -qq
ok "System updated"

# ── Dependencies ─────────────────────────────────────────────
step "Installing dependencies (PHP 8.1, MariaDB, Nginx, Redis, etc.)..."
apt-get install -y -qq \
    software-properties-common curl apt-transport-https ca-certificates gnupg2 \
    lsb-release unzip tar git wget \
    php8.1 php8.1-{cli,gd,mysql,pdo,mbstring,tokenizer,bcmath,xml,fpm,curl,zip,intl} \
    mariadb-server redis-server nginx composer \
    2>/dev/null || {
        # Try adding PHP 8.1 repo for Ubuntu/Debian if not found
        add-apt-repository -y ppa:ondrej/php 2>/dev/null || true
        curl -sSo /etc/apt/trusted.gpg.d/php.gpg https://packages.sury.org/php/apt.gpg 2>/dev/null || true
        apt-get update -qq
        apt-get install -y -qq \
            php8.1 php8.1-{cli,gd,mysql,pdo,mbstring,tokenizer,bcmath,xml,fpm,curl,zip,intl} \
            mariadb-server redis-server nginx composer
    }
ok "Dependencies installed"

# ── MariaDB setup ─────────────────────────────────────────────
step "Configuring MariaDB..."
systemctl enable --now mariadb 2>/dev/null || service mariadb start 2>/dev/null || true
mysql -u root <<SQL
CREATE DATABASE IF NOT EXISTS \`${DB_NAME}\`;
CREATE USER IF NOT EXISTS '${DB_USER}'@'127.0.0.1' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON \`${DB_NAME}\`.* TO '${DB_USER}'@'127.0.0.1';
FLUSH PRIVILEGES;
SQL
ok "Database '${DB_NAME}' and user '${DB_USER}' created"

# ── Panel download & setup ────────────────────────────────────
PANEL_DIR="/var/www/pterodactyl"
step "Downloading Kroxy panel to ${PANEL_DIR}..."
mkdir -p "$PANEL_DIR"
# Copy from current directory (installer is shipped with the panel source)
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
cp -r "$SCRIPT_DIR"/. "$PANEL_DIR/"
ok "Panel files copied"

cd "$PANEL_DIR"

step "Installing Composer dependencies..."
composer install --no-dev --optimize-autoloader --no-interaction --quiet
ok "Composer done"

step "Writing .env file..."
cp .env.example .env
APP_KEY=$(php artisan key:generate --show --no-interaction 2>/dev/null || openssl rand -base64 32)

cat > .env <<ENV
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:$(php artisan key:generate --show --no-interaction 2>/dev/null | sed 's/base64://')
APP_URL=${APP_URL}
APP_NAME="Kroxy"
APP_LOCALE=en
APP_TIMEZONE=UTC

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=${DB_NAME}
DB_USERNAME=${DB_USER}
DB_PASSWORD=${DB_PASS}

CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

REDIS_HOST=127.0.0.1
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_FROM=no-reply@kroxy.panel
ENV

# Regenerate a proper key into the file
php artisan key:generate --force --no-interaction > /dev/null
ok ".env configured"

step "Running database migrations..."
php artisan migrate --force --no-interaction > /dev/null
ok "Migrations complete"

step "Creating admin user..."
php artisan p:user:make \
    --email="${ADMIN_EMAIL}" \
    --username="${ADMIN_USER}" \
    --name-first="Admin" \
    --name-last="User" \
    --password="${ADMIN_PASS}" \
    --admin=1 \
    --no-interaction > /dev/null 2>&1 || \
php artisan p:user:make \
    --email="${ADMIN_EMAIL}" \
    --username="${ADMIN_USER}" \
    --name-first="Admin" \
    --name-last="User" \
    --password="${ADMIN_PASS}" \
    --admin=1 2>&1 | tail -1
ok "Admin user created"

step "Setting file permissions..."
chown -R www-data:www-data "$PANEL_DIR"
chmod -R 755 "$PANEL_DIR"
chmod -R 750 "$PANEL_DIR/storage" "$PANEL_DIR/bootstrap/cache"
ok "Permissions set"

# ── Redis ─────────────────────────────────────────────────────
step "Enabling Redis..."
systemctl enable --now redis-server 2>/dev/null || service redis-server start 2>/dev/null || true
ok "Redis running"

# ── Nginx ─────────────────────────────────────────────────────
step "Configuring Nginx..."
DOMAIN=$(echo "$APP_URL" | sed 's|https\?://||' | sed 's|/.*||')
cat > /etc/nginx/sites-available/kroxy.conf <<NGINX
server {
    listen 80;
    server_name ${DOMAIN};
    root /var/www/pterodactyl/public;

    index index.php;
    charset utf-8;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log /var/log/nginx/kroxy-access.log;
    error_log  /var/log/nginx/kroxy-error.log warn;

    client_max_body_size 100m;
    client_body_timeout 120s;
    sendfile off;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/run/php/php8.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param PHP_VALUE "upload_max_filesize = 100M \n post_max_size=100M";
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param HTTP_PROXY "";
        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht { deny all; }
}
NGINX

ln -sf /etc/nginx/sites-available/kroxy.conf /etc/nginx/sites-enabled/kroxy.conf
rm -f /etc/nginx/sites-enabled/default 2>/dev/null || true
nginx -t > /dev/null 2>&1 && systemctl reload nginx 2>/dev/null || service nginx reload 2>/dev/null || true
ok "Nginx configured for ${DOMAIN}"

# ── PHP-FPM ───────────────────────────────────────────────────
step "Starting PHP-FPM..."
systemctl enable --now php8.1-fpm 2>/dev/null || service php8.1-fpm start 2>/dev/null || true
ok "PHP-FPM running"

# ── Queue worker ─────────────────────────────────────────────
step "Setting up queue worker service..."
cat > /etc/systemd/system/kroxy-queue.service <<SVC
[Unit]
Description=Kroxy Queue Worker
After=redis-server.service

[Service]
User=www-data
Group=www-data
Restart=always
ExecStart=/usr/bin/php /var/www/pterodactyl/artisan queue:work --queue=high,standard,low --sleep=3 --tries=3
StartLimitInterval=180
StartLimitBurst=30
RestartSec=5s

[Install]
WantedBy=multi-user.target
SVC

systemctl enable --now kroxy-queue 2>/dev/null || true
ok "Queue worker enabled"

# ── Cron ─────────────────────────────────────────────────────
step "Adding cron job..."
(crontab -l 2>/dev/null | grep -v pterodactyl; echo "* * * * * php /var/www/pterodactyl/artisan schedule:run >> /dev/null 2>&1") | crontab -
ok "Cron job added"

# ── Optimize ─────────────────────────────────────────────────
step "Optimizing Laravel..."
cd "$PANEL_DIR"
php artisan config:cache > /dev/null
php artisan route:cache  > /dev/null
php artisan view:cache   > /dev/null
ok "Laravel cache built"

# ── Done ─────────────────────────────────────────────────────
echo ""
echo -e "  ${GREEN}${BOLD}╔═══════════════════════════════════════════╗${NC}"
echo -e "  ${GREEN}${BOLD}║   Kroxy Panel installed successfully! ✓  ║${NC}"
echo -e "  ${GREEN}${BOLD}╚═══════════════════════════════════════════╝${NC}"
echo ""
echo -e "  ${WHITE}Panel URL  :${NC} ${APP_URL}"
echo -e "  ${WHITE}Admin user :${NC} ${ADMIN_USER}"
echo -e "  ${WHITE}Admin email:${NC} ${ADMIN_EMAIL}"
echo ""
echo -e "  ${DIM}Powered by Pterodactyl Panel${NC}"
echo ""
