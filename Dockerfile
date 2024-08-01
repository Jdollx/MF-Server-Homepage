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
    unzip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Copy the Composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy application files to the container
COPY . .

# Copy custom Apache configuration file
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Expose port 80 for the web server (localhost:8080)
EXPOSE 80
EXPOSE 443

# Enable mod_rewrite
RUN a2enmod rewrite

# Start Apache
CMD ["apache2-foreground"]