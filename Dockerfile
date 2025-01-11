# Use PHP 7.4 FPM (or 7.2 if still preferred, just replace 7.4 with 7.2)
FROM php:7.4-fpm

# Update the base image repositories to use 'buster' or 'bullseye'
RUN sed -i 's/stretch/buster/g' /etc/apt/sources.list \
    && apt-get update \
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
    libssl-dev \
    libexif-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql intl xsl exif zip

# Install Node.js & npm (specific version matching your requirements, or latest LTS)
RUN curl -sL https://deb.nodesource.com/setup_14.x | bash - && \
    apt-get install -y nodejs

# Install Composer (version 1.10.19, matching the PHP version)
RUN curl -sS https://getcomposer.org/installer | php -- --version=1.10.19 --install-dir=/usr/local/bin --filename=composer

# Copy the application files to the container
COPY . .

# Install PHP dependencies using Composer
RUN composer install --no-dev --optimize-autoloader --prefer-dist

# Install npm dependencies defined in package.json
RUN npm install -g npm@6

# Build frontend assets (JS/CSS) using
RUN npm run prod

CMD ["php-fpm"]