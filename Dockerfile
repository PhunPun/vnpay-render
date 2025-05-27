FROM php:8.1-apache

# Copy source code vào thư mục web gốc
COPY . /var/www/html/

# Bật mod_rewrite (nếu cần rewrite URL)
RUN a2enmod rewrite
