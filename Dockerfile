# ==============================================================================
# SIPAKAR Dockerfile — Production Hardened
# PHP 8.3 Apache on Debian Bookworm (Official Supported Image)
# Framework: CodeIgniter 4.7.6 | Security Baseline: Semgrep / CIS Benchmark
# ==============================================================================

FROM php:8.3-apache-bookworm

LABEL maintainer="Pemerintah Kecamatan Siantar Barat <kec.siantarbarat@pematangsiantar.go.id>"
LABEL description="SIPAKAR - Sistem Informasi Pelayanan ASN Kecamatan Siantar Barat"
LABEL version="1.0"
LABEL php.version="8.3"
LABEL security.audit="SIP-03 COMPLIANT"

# Set environment defaults (production)
ENV CI_ENVIRONMENT=production \
    DEBIAN_FRONTEND=noninteractive \
    APACHE_DOCUMENT_ROOT=/var/www/html

# 1. Install necessary system dependencies (minimal attack surface)
RUN apt-get update && apt-get install -y --no-install-recommends \
        libicu-dev \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        libzip-dev \
        libonig-dev \
        libxml2-dev \
        curl \
        unzip \
    && rm -rf /var/lib/apt/lists/*

# 2. Configure & install required PHP extensions
# Required by CodeIgniter 4 & SIPAKAR: intl, mbstring, mysqli, pdo, pdo_mysql, gd, zip, xml, fileinfo, opcache
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        intl \
        mbstring \
        mysqli \
        pdo \
        pdo_mysql \
        gd \
        zip \
        xml \
        fileinfo \
        opcache

# 3. Configure Apache modules & security headers
RUN a2enmod rewrite headers remoteip \
    && a2dissite 000-default.conf

# Copy custom Apache configuration
COPY docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf
RUN a2ensite 000-default.conf

# 4. Copy custom hardened php.ini
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-sipakar-production.ini

# 5. Set working directory
WORKDIR /var/www/html

# 6. Copy application source code (excluding .dockerignore items)
COPY . /var/www/html/

# 7. Configure directory permissions & non-root ownership for web runtime
RUN mkdir -p /var/www/html/writable/cache \
             /var/www/html/writable/logs \
             /var/www/html/writable/session \
             /var/www/html/writable/uploads \
             /var/log/apache2 \
    && chown -R www-data:www-data /var/www/html \
    && find /var/www/html -type d -exec chmod 755 {} \; \
    && find /var/www/html -type f -exec chmod 644 {} \; \
    && chmod -R 775 /var/www/html/writable \
    && ([ ! -f /var/www/html/spark ] || chmod +x /var/www/html/spark)

# 8. Expose HTTP port
EXPOSE 80

# 9. Healthcheck to monitor container availability
HEALTHCHECK --interval=30s --timeout=5s --start-period=10s --retries=3 \
    CMD curl -f http://localhost/ || exit 1

# 10. Start Apache in foreground
CMD ["apache2-foreground"]
