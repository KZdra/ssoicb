# =============================================================================
# Stage 1: Node.js - Build frontend assets (Vite/TailwindCSS)
# =============================================================================
FROM node:20-alpine AS node_builder

WORKDIR /app

# Copy package files dulu agar layer cache optimal
COPY package.json package-lock.json ./

RUN npm ci --prefer-offline

# Copy source untuk build assets
COPY resources/ ./resources/
COPY vite.config.js tailwind.config.js ./
COPY public/ ./public/

RUN npm run build

# =============================================================================
# Stage 2: PHP/Apache - Production image berbasis Debian
# =============================================================================
FROM php:8.2-apache AS app

LABEL maintainer="ssoICB Dev Team"
LABEL description="Laravel 12 SSO ICB Application"

# Set timezone
ENV TZ=Asia/Jakarta

# Install system dependencies & PHP extensions yang dibutuhkan Laravel
RUN apt-get update && apt-get install -y --no-install-recommends \
    # Tools dasar
    curl \
    unzip \
    git \
    # Library untuk PHP extensions
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libxml2-dev \
    libonig-dev \
    libssl-dev \
    # For timezone
    tzdata \
    # Untuk health check
    default-mysql-client \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        mysqli \
        mbstring \
        zip \
        exif \
        bcmath \
        gd \
        xml \
        opcache \
        pcntl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Composer dari official image (best practice)
COPY --from=composer:2.7 /usr/bin/composer /usr/bin/composer

# Konfigurasi PHP untuk production
RUN cp "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
COPY docker/php/custom.ini $PHP_INI_DIR/conf.d/custom.ini

# Konfigurasi OPcache untuk production
COPY docker/php/opcache.ini $PHP_INI_DIR/conf.d/opcache.ini

# Aktifkan Apache modules yang diperlukan
RUN a2enmod rewrite headers expires deflate

# Copy konfigurasi Apache VirtualHost
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Set working directory
WORKDIR /var/www/html

# Copy composer files dan install dependencies (tanpa dev, dan optimized)
COPY composer.json composer.lock ./
RUN composer install \
    --no-dev \
    --no-interaction \
    --no-plugins \
    --no-scripts \
    --prefer-dist \
    --optimize-autoloader \
    --no-progress

# Copy seluruh source code aplikasi
COPY . .

# Copy built assets dari stage Node.js
COPY --from=node_builder /app/public/build ./public/build

# Jalankan post-install scripts setelah semua file ada
RUN composer dump-autoload --optimize --no-dev

# Set permission untuk Laravel (storage & bootstrap/cache)
RUN chown -R www-data:www-data /var/www/html \
    && find /var/www/html/storage -type d -exec chmod 775 {} \; \
    && find /var/www/html/storage -type f -exec chmod 664 {} \; \
    && find /var/www/html/bootstrap/cache -type d -exec chmod 775 {} \; \
    && find /var/www/html/bootstrap/cache -type f -exec chmod 664 {} \;

# Copy & set permission entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expose port Apache
EXPOSE 80

# Gunakan entrypoint script
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
