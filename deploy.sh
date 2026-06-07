#!/usr/bin/env bash
# Azure App Service Post-Deployment Script
# Este script se ejecuta automáticamente después de cada git push

echo "=== [deploy.sh] Iniciando post-deploy ==="

# 1. Instalar dependencias de Composer
echo ">>> Instalando dependencias de Composer (producción)..."
php composer.phar install --no-dev --optimize-autoloader 2>&1 || composer install --no-dev --optimize-autoloader 2>&1

# 2. Generar APP_KEY si no existe
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "None" ]; then
    echo ">>> Generando APP_KEY..."
    php artisan key:generate --force
fi

# 3. Cache de configuraciones
echo ">>> Limpiando y cacheando config..."
php artisan config:clear
php artisan config:cache

# 4. Cache de rutas
echo ">>> Cacheando rutas..."
php artisan route:cache 2>&1 || echo "[SKIP] route:cache no disponible (posiblemente por closures)"

# 5. Cache de vistas
echo ">>> Cacheando vistas..."
php artisan view:cache

# 6. Migraciones
echo ">>> Ejecutando migraciones..."
php artisan migrate --force 2>&1

# 7. Build de assets con Vite
echo ">>> Construyendo assets (Vite)..."
npm install --silent 2>&1
npm run build 2>&1

echo "=== [deploy.sh] Post-deploy completado ==="
