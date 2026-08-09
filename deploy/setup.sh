#!/usr/bin/env bash
# YieldEmpire - one-time production host setup on this EC2 (run with sudo)
set -euo pipefail

cd "$(dirname "$0")/.."   # repo root

echo "==> [1/6] Ensuring docker + compose plugin are installed"
# t3.micro has ~1GB RAM; add swap so the PHP image build cannot OOM
if ! swapon --show | grep -q .; then
  echo "  adding 2G swapfile"
  fallocate -l 2G /swapfile 2>/dev/null || dd if=/dev/zero of=/swapfile bs=1M count=2048
  chmod 600 /swapfile
  mkswap /swapfile >/dev/null
  swapon /swapfile
  grep -q '^/swapfile' /etc/fstab || echo '/swapfile none swap sw 0 0' >> /etc/fstab
fi

if ! command -v docker >/dev/null 2>&1; then
  apt-get update
  apt-get install -y ca-certificates curl gnupg
  install -m 0755 -d /etc/apt/keyrings
  curl -fsSL https://download.docker.com/linux/ubuntu/gpg | gpg --dearmor -o /etc/apt/keyrings/docker.gpg
  chmod a+r /etc/apt/keyrings/docker.gpg
  echo "deb [arch=$(dpkg --print-architecture) signed-by=/etc/apt/keyrings/docker.gpg] https://download.docker.com/linux/ubuntu $(. /etc/os-release && echo "$VERSION_CODENAME") stable" > /etc/apt/sources.list.d/docker.list
  apt-get update
  apt-get install -y docker-ce docker-ce-cli containerd.io docker-buildx-plugin docker-compose-plugin
fi

echo "==> [2/6] Preparing production .env (live Supabase project)"
if [ ! -f .env ]; then
  cp .env.example .env
fi
# Point at the LIVE Supabase project pooler; wallet-safe quoted password.
python3 - <<'PY'
import re, pathlib
p = pathlib.Path('.env')
txt = p.read_text()
for k, v in {
  'APP_NAME': '"YieldEmpire"',
  'APP_ENV': '"production"',
  'APP_DEBUG': '"false"',
  'APP_URL': '"https://yieldempire.org"',
  'APP_MODE': '"live"',
  'DB_CONNECTION': '"pgsql"',
  'DB_HOST': '"aws-0-eu-west-1.pooler.supabase.com"',
  'DB_PORT': '"6543"',
  'DB_DATABASE': '"postgres"',
  'DB_USERNAME': '"postgres.psegntoetyywntszrsat"',
  'DB_PASSWORD': "'Madueke468$'",
}.items():
    if re.search(rf'^{re.escape(k)}=', txt, re.M):
        txt = re.sub(rf'^{re.escape(k)}=.*$', f'{k}={v}', txt, flags=re.M)
    else:
        txt += f'\n{k}={v}'
p.write_text(txt)
print('  .env updated for production')
PY

echo "==> [3/6] Building image (PHP 8.2 FPM + nginx)"
# Make Laravel storage + bootstrap cache writable by container www-data
chmod -R 775 storage bootstrap/cache 2>/dev/null || true
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
docker compose -f deploy/docker-compose.yml build

echo "==> [4/6] Starting stack"
docker compose -f deploy/docker-compose.yml up -d

echo "==> [5/6] Waiting for app container"
for i in $(seq 1 60); do
  if docker exec yieldempire-app test -f artisan 2>/dev/null; then break; fi
  sleep 2
done

echo "==> [6/6] Laravel post-deploy (package discovery + config/view cache + pending migrations + storage link)"
docker exec yieldempire-app php artisan package:discover || true
docker exec yieldempire-app php artisan config:cache || true
docker exec yieldempire-app php artisan view:cache || true
docker exec yieldempire-app php artisan migrate --force || true
docker exec yieldempire-app php artisan storage:link || true
docker exec yieldempire-app php artisan optimize:clear || true

echo "==> ALL DONE. Verify with:  curl -s -o /dev/null -w '%{http_code}' http://localhost"
docker ps --filter name=yieldempire-