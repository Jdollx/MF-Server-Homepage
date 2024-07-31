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
    gnupg \
    certbot \
    python3-certbot-apache \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy the Composer files
COPY composer.json composer.lock ./

# Install PHP dependencies
RUN composer install

# Copy application files to the container
COPY . .

# Copy custom Apache configuration
COPY musicfeedbackdisc.conf /etc/apache2/sites-available/musicfeedbackdisc.conf

# Enable modules and site
RUN a2enmod rewrite ssl \
    && a2ensite musicfeedbackdisc.conf

# Test Apache configuration
RUN apache2ctl configtest

# Reload Apache service
RUN service apache2 reload

# Expose ports 80 and 443 for HTTP and HTTPS
EXPOSE 80
EXPOSE 443

# Start Apache
CMD ["apache2-foreground"]
