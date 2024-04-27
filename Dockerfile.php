FROM php:8.1-apache

# Update system and install required packages
RUN apt-get update && \
    apt-get upgrade -y && \
    DEBIAN_FRONTEND="noninteractive" apt-get install -y \
    wget \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libxml2-dev \
    libtidy-dev \
    libxslt-dev \
    unzip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-configure gd --with-jpeg && \
    docker-php-ext-install -j$(nproc) \
    gd \
    opcache \
    pdo_mysql \
    mysqli \
    && docker-php-ext-enable opcache

# Set PHP memory limit and timezone
ENV PHP_MEMORY_LIMIT="256M"
ENV PHP_TIMEZONE="UTC"
RUN echo "memory_limit = ${PHP_MEMORY_LIMIT}" > /usr/local/etc/php/php.ini && \
    echo "date.timezone = ${PHP_TIMEZONE}" >> /usr/local/etc/php/php.ini

# Download and install Drupal 10
WORKDIR /var/www/html
RUN wget -qO- https://ftp.drupal.org/files/projects/drupal-10.2.5.tar.gz | tar xz --strip-components=1

# Change ownership and permissions
RUN chown -R www-data:www-data /var/www/html && \
    chmod -R 755 /var/www/html

# Copy Apache configuration
COPY apache.conf /etc/apache2/sites-available/drupal.conf

# Disable default Apache page and enable Drupal site
RUN a2dissite 000-default.conf && \
    a2ensite drupal.conf && \
    a2dismod mpm_event && \
    a2enmod mpm_prefork && \
    a2enmod rewrite && \
    service apache2 restart

# Expose the port used by docker-compose
EXPOSE 80

# Run Apache in foreground for development
CMD ["apache2ctl", "-D", "FOREGROUND"]
