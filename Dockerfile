FROM php:8.3-apache

# Installer extensions PHP utiles
RUN apt-get update && apt-get install -y \
    libicu-dev libzip-dev zip unzip git vim nano \
    libpng-dev libonig-dev libxml2-dev \
    && docker-php-ext-install pdo pdo_mysql mysqli intl opcache


# Configurer l'affichage des erreurs PHP
RUN echo "error_reporting = E_ALL" > /usr/local/etc/php/conf.d/error_reporting.ini \
    && echo "display_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini \
    && echo "display_startup_errors = On" >> /usr/local/etc/php/conf.d/error_reporting.ini

# Activer mod_rewrite pour les routes
RUN a2enmod rewrite

# Copier le code source
WORKDIR /var/www/html
COPY . /var/www/html

# Installer Composer depuis l'image officielle
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Configuration Apache pour réécrire les URLs : rediriger tout vers /public
RUN cat > /etc/apache2/sites-available/000-default.conf <<'EOF'
<VirtualHost *:80>
    DocumentRoot /var/www/html/public
    <Directory /var/www/html/public>
        AllowOverride All
        Options Indexes FollowSymLinks
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
EOF
