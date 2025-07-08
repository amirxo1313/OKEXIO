FROM php:8.1-fpm

# نصب افزونه‌های لازم برای PDO و MySQL
RUN docker-php-ext-install pdo_mysql