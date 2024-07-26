# Use the official PHP image with Apache
FROM php:8.1-apache

# Set the working directory in the container
WORKDIR /var/www/html

# Define build arguments
ARG USE
ARG PASSWORD
ARG DISCORDWIDGET
ARG EMAIL
ARG EMAIL_PASSWORD
ARG TOKEN
ARG JENN_ID
ARG MAKI_ID
ARG STRIKE_ID
ARG SONO_ID
ARG TAV_ID
ARG DAVID_ID
ARG AMAZE_ID
ARG TOAST_ID
ARG SERVER_ID

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

# Expose port 80 for the web server (localhost:8080)
EXPOSE 80

# Start Apache
CMD ["apache2-foreground"]
