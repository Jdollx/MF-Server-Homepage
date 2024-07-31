# Use the official PHP image with Apache
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies required to run Composer
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    gnupg \
    lsb-release \
    sudo \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | apt-key add - \
    && apt-get update

# Install Snapd and Certbot
RUN apt-get install -y snapd \
    && snap install core \
    && snap refresh core \
    && snap install --classic certbot

# Copy the Composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy application files to the container
COPY . .

# Expose ports for the web server
EXPOSE 80
EXPOSE 443

# Start Apache
CMD ["apache2-foreground"]
