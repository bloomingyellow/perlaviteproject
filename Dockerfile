# Use the official PHP image with Apache
FROM php:8.2-apache

# Enable Apache mod_rewrite
RUN a2enmod rewrite

# Install the PDO MySQL extension required by Perla Vita
RUN docker-php-ext-install pdo pdo_mysql

# Copy your application code to the Apache document root
COPY . /var/www/html/

# Ensure the uploads directory exists and is writable
RUN mkdir -p /var/www/html/uploads/avatars/ \
    && chown -R www-data:www-data /var/www/html/uploads/ \
    && chmod -R 777 /var/www/html/uploads/

# Expose the default HTTP port
EXPOSE 80
