#!/usr/bin/env bash
# Azure App Service Post-Deployment Script
# Se ejecuta automáticamente tras cada git push

set -e
echo "=== [deploy.sh] Iniciando post-deploy ==="

# =============================================================================
# 0. Configurar Nginx para Laravel (try_files + public/)
# =============================================================================
echo ">>> Configurando Nginx para Laravel..."

NGINX_CONF="/etc/nginx/sites-available/default"

if [ -f "$NGINX_CONF" ]; then

    # 0a. Respaldar original
    cp "$NGINX_CONF" "${NGINX_CONF}.bak"

    # 0b. Cambiar root: /home/site/wwwroot → /home/site/wwwroot/public
    sed -i "s|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g" "$NGINX_CONF"
    sed -i "s|root /home/site/wwwroot |root /home/site/wwwroot/public |g" "$NGINX_CONF"

    # 0c. Inyectar try_files dentro del bloque location / { ... }
    # Busca 'location / {' y agrega try_files en la siguiente línea
    sed -i '/location \/ {/a \        try_files $uri $uri/ /index.php?$query_string;' "$NGINX_CONF"

    echo "[Nginx] Configuración aplicada: root → public/ + try_files agregado"

else
    echo "[Nginx] ADVERTENCIA: No se encontró $NGINX_CONF. Creando configuración mínima..."

    cat > "$NGINX_CONF" << 'NGINXEOF'
server {
    listen 8080;
    listen [::]:8080;
    root /home/site/wwwroot/public;
    index index.php index.html index.htm;
    server_name _;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }
}
NGINXEOF
fi

# 0d. Verificar sintaxis y recargar
nginx -t 2>&1 && service nginx reload 2>&1 || echo "[Nginx] ERROR al validar/recargar configuración"
echo ""

# =============================================================================
# 1. Instalar dependencias de Composer
# =============================================================================
echo ">>> Instalando dependencias de Composer (producción)..."
composer install --no-dev --optimize-autoloader 2>&1

# =============================================================================
# 2. Generar APP_KEY si no existe
# =============================================================================
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "None" ]; then
    echo ">>> Generando APP_KEY..."
    php artisan key:generate --force
fi

# =============================================================================
# 3-5. Cache de Laravel
# =============================================================================
echo ">>> Limpiando y cacheando config..."
php artisan config:clear
php artisan config:cache

echo ">>> Cacheando vistas..."
php artisan view:cache

echo ">>> Cacheando rutas..."
php artisan route:cache 2>&1 || echo "[SKIP] route:cache no disponible"

# =============================================================================
# 6. Migraciones
# =============================================================================
echo ">>> Ejecutando migraciones..."
php artisan migrate --force 2>&1

# =============================================================================
# 7. Limpiar archivo por defecto de Azure
# =============================================================================
rm -f /home/site/wwwroot/hostingstart.html

echo ""
echo "=== [deploy.sh] Post-deploy completado ==="
