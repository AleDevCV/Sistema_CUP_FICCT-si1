#!/usr/bin/env bash
# Azure App Service Post-Deployment Script
# Se ejecuta automáticamente tras cada git push

set -e
echo "=== [deploy.sh] Iniciando post-deploy ==="

# =============================================================================
# 0. Configurar Nginx para Laravel (Bulletproof — sobrescribe todo)
# =============================================================================
echo ">>> Configurando Nginx para Laravel..."

NGINX_CONF="/etc/nginx/conf.d/default.conf"
NGINX_CONF_DIR="/etc/nginx/conf.d"

# 0a. Detectar el socket PHP-FPM que usa Azure en este contenedor
#     Buscar en cualquier archivo .conf existente; si no hay, usar TCP por defecto
#     tr -d ';' evita el doble ;; que rompe la sintaxis de nginx
PHP_SOCKET=$(grep -rhoP 'fastcgi_pass\s+\K\S+' /etc/nginx/ 2>/dev/null | head -1 | tr -d ';')
if [ -z "$PHP_SOCKET" ]; then
    PHP_SOCKET="127.0.0.1:9000"
    echo "[Nginx] Socket PHP-FPM no detectado → usando $PHP_SOCKET"
else
    echo "[Nginx] Socket PHP-FPM detectado: $PHP_SOCKET"
fi

# 0b. Asegurar que el directorio conf.d existe
mkdir -p "$NGINX_CONF_DIR"

# 0c. Sobrescribir con configuración completa y limpia para Laravel
cat > "$NGINX_CONF" << NGINXEOF
server {
    listen 8080;
    listen [::]:8080;
    server_name _;

    root /home/site/wwwroot/public;
    index index.php index.html index.htm;

    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-Content-Type-Options "nosniff" always;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_pass $PHP_SOCKET;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME \$realpath_root\$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.ht {
        deny all;
    }

    # Denegar acceso a archivos sensibles de Laravel
    location ~ /\.(?!well-known).* {
        deny all;
    }
}
NGINXEOF

echo "[Nginx] Configuración Laravel escrita en $NGINX_CONF"

# 0d. Verificar sintaxis y recargar
nginx -t 2>&1 && service nginx reload 2>&1 || echo "[Nginx] ERROR al validar/recargar"
echo ""

# =============================================================================
# 1. Composer ya fue ejecutado en GitHub Actions (vendor incluido en el zip)
#    NO ejecutar aquí: el contenedor de Azure no tiene composer instalado
# =============================================================================
echo ">>> [SKIP] Composer — vendor/ ya viene compilado desde CI/CD"

# =============================================================================
# 1b. Crear .env con APP_KEY= si no existe
#     artisan key:generate necesita el string "APP_KEY=" literal en el
#     archivo para poder reemplazarlo. touch no sirve (archivo vacío falla).
#     El resto de variables (DB, etc.) las inyecta Azure desde App Settings.
# =============================================================================
if [ ! -f /home/site/wwwroot/.env ]; then
    echo ">>> .env no encontrado → creando con APP_KEY=..."
    echo "APP_KEY=" > /home/site/wwwroot/.env
fi

# =============================================================================
# 2. Generar APP_KEY si no existe
# =============================================================================
if [ -z "$APP_KEY" ] || [ "$APP_KEY" = "None" ]; then
    echo ">>> Generando APP_KEY..."
    php artisan key:generate --force
fi

# =============================================================================
# 3. Recrear estructura de storage (GitHub no sube directorios vacíos)
# =============================================================================
echo ">>> Creando estructura storage/framework..."
mkdir -p /home/site/wwwroot/storage/framework/{sessions,views,cache}
mkdir -p /home/site/wwwroot/storage/logs
chmod -R 775 /home/site/wwwroot/storage /home/site/wwwroot/bootstrap/cache

# =============================================================================
# 4-6. Cache de Laravel
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
