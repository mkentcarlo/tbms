# Use PHP 8.2 FPM (required for Laravel 11)
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update \
    && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    git \
    curl \
    libicu-dev \
    libxml2-dev \
    libxslt1-dev \
    libssl-dev \
    libcurl4-openssl-dev \
    libexif-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl xsl exif zip \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Install Node.js & npm (LTS version)
RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - && \
    apt-get install -y nodejs

# Install Composer (latest version)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the application files to the container
COPY . .

# Note: Dependencies will be installed after container starts
# This allows us to update composer.lock first

CMD ["php-fpm"]