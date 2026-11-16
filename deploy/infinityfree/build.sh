#!/usr/bin/env bash
# Builds an upload-ready package for InfinityFree (no SSH needed).
# Usage: ADMIN_EMAIL=you@example.com ./build.sh     (run inside deploy/infinityfree)
# Needs: php, composer (COMPOSER_CMD="php /path/composer.phar" to override), git, tar
set -euo pipefail
HERE="$(cd "$(dirname "$0")" && pwd)"
SRC="$(cd "$HERE/../.." && pwd)"
DIST="${DIST:-$SRC/../dist}"; mkdir -p "$DIST"; DIST="$(cd "$DIST" && pwd)"
COMPOSER_CMD="${COMPOSER_CMD:-composer}"
ADMIN_EMAIL="${ADMIN_EMAIL:-admin@dreamhomes.test}"

rm -rf "$DIST/stage" "$DIST/htdocs" "$DIST/dreamhomes-infinityfree.zip"
mkdir -p "$DIST/stage" "$DIST/htdocs/laravel"

# 1. Copy project files (tracked + new, honouring .gitignore: no .env, vendor, sqlite, uploads)
( cd "$SRC" && git ls-files -co --exclude-standard -z | xargs -0 -I{} cp --parents {} "$DIST/stage/" )

# 2. Production dependencies only
( cd "$DIST/stage" && $COMPOSER_CMD install --no-dev --optimize-autoloader --no-interaction --no-scripts )
find "$DIST/stage/vendor" -type d \( -iname tests -o -iname test -o -iname docs -o -iname examples \) -prune -exec rm -rf {} + 2>/dev/null || true

# 3. App code -> htdocs/laravel (everything except public/ and dev-only files)
cd "$DIST/stage"
for item in app bootstrap config database resources routes storage vendor artisan composer.json composer.lock; do cp -r "$item" "$DIST/htdocs/laravel/"; done
L="$DIST/htdocs/laravel"
rm -f "$L"/database/*.sqlite "$L"/bootstrap/cache/*.php "$L"/storage/logs/*.log
mkdir -p "$L"/storage/framework/{cache/data,sessions,views} "$L"/storage/logs "$L"/storage/app/public "$L"/bootstrap/cache
cp "$HERE/laravel.htaccess" "$L/.htaccess"
rm -rf "$L"/storage/app/public/* "$L"/storage/framework/views/* "$L"/storage/framework/sessions/*

# 4. Public files -> htdocs (web root)
cp -r public/. "$DIST/htdocs/"
rm -rf "$DIST/htdocs/storage" "$DIST/htdocs/robots.txt"
cp "$HERE/index.php" "$DIST/htdocs/index.php"
cp "$HERE/htdocs.htaccess" "$DIST/htdocs/.htaccess"
mkdir -p "$DIST/htdocs/storage" && cp "$HERE/uploads.htaccess" "$DIST/htdocs/storage/.htaccess"

# 5. .env with a fresh APP_KEY (fill DB + URL before uploading)
KEY="base64:$(php -r 'echo base64_encode(random_bytes(32));')"
sed "s#__APP_KEY__#$KEY#" "$HERE/env.template" > "$L/.env"

# 6. Database import file with a random admin password
PASS="$(php -r '$c="abcdefghjkmnpqrstuvwxyzABCDEFGHJKLMNPQRSTUVWXYZ23456789";$p="";for($i=0;$i<16;$i++)$p.=$c[random_int(0,strlen($c)-1)];echo $p;')"
HASH="$(php -r 'echo password_hash($argv[1], PASSWORD_BCRYPT);' "$PASS")"
sed "s#__ADMIN_EMAIL__#$ADMIN_EMAIL#; s#__ADMIN_HASH__#$(printf '%s' "$HASH" | sed 's/[&#]/\&/g')#" "$HERE/schema.sql" > "$DIST/database.sql"
printf 'Admin login\nURL:      https://YOURDOMAIN/admin/login\nEmail:    %s\nPassword: %s\n\nKeep this file private. Do NOT upload it.\n' "$ADMIN_EMAIL" "$PASS" > "$DIST/ADMIN-LOGIN.txt"

# 7. Zip for convenience (FTP of the htdocs folder is more reliable for ~9k files)
rm -rf "$DIST/stage"
( cd "$DIST" && tar -a -c -f dreamhomes-infinityfree.zip htdocs )
echo "Built: $DIST/htdocs  +  database.sql  +  ADMIN-LOGIN.txt  +  dreamhomes-infinityfree.zip"
