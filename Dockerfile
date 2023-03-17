# Base image
FROM php:8.0-apache

# Enable Apache rewrite module
RUN a2enmod rewrite

# Set working directory
WORKDIR /var/www/html

# Copy the application
COPY . .

# Install dependencies
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Expose port 80
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
