#!/usr/bin/env bash
# Azure App Service Post-Deployment Script
# Se ejecuta automáticamente tras cada git push

set -e
echo "=== [deploy.sh] Iniciando post-deploy ==="

# =============================================================================
# 0. Configurar Nginx para Laravel (try_files + public/)
# =============================================================================
echo ">>> Configurando Nginx para Laravel..."

# Azure App Service Linux PHP 8.2 usa /etc/nginx/conf.d/default.conf
# NO usa sites-available (estructura Debian/Ubuntu clásica)
NGINX_CONF="/etc/nginx/conf.d/default.conf"

if [ -f "$NGINX_CONF" ]; then

    # 0a. Respaldar original
    cp "$NGINX_CONF" "${NGINX_CONF}.bak"

    # 0b. Cambiar root: /home/site/wwwroot → /home/site/wwwroot/public
    sed -i "s|root /home/site/wwwroot;|root /home/site/wwwroot/public;|g" "$NGINX_CONF"
    sed -i "s|root /home/site/wwwroot |root /home/site/wwwroot/public |g" "$NGINX_CONF"

    # 0c. Inyectar try_files dentro del bloque location / { ... }
    sed -i '/location \/ {/a \        try_files $uri $uri/ /index.php?$query_string;' "$NGINX_CONF"

    echo "[Nginx] Configuración aplicada: root → public/ + try_files agregado"

else
    echo "[Nginx] ERROR: No se encontró $NGINX_CONF. Abortando."
    exit 1
fi

# 0d. Verificar sintaxis y recargar
nginx -t 2>&1 && service nginx reload 2>&1 || echo "[Nginx] ERROR al validar/recargar configuración"
echo ""

# =============================================================================
# 1. Composer ya fue ejecutado en GitHub Actions (vendor incluido en el zip)
#    NO ejecutar aquí: el contenedor de Azure no tiene composer instalado
# =============================================================================
echo ">>> [SKIP] Composer — vendor/ ya viene compilado desde CI/CD"

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
