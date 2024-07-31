# Use the official PHP image with Apache
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Install dependencies required to run Composer and Certbot
RUN apt-get update && apt-get install -y \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    unzip \
    software-properties-common \
    && add-apt-repository ppa:certbot/certbot \
    && apt-get update \
    && apt-get install -y certbot python3-certbot-apache

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Copy the Composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy application files to the container
COPY . .

# Copy Apache configuration file
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

# Enable SSL and Rewrite modules
RUN a2enmod ssl
RUN a2enmod rewrite

# Expose port 80 for HTTP and 443 for HTTPS
EXPOSE 80 443

# Start Apache
CMD ["apache2-foreground"]
