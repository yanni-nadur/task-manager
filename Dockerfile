FROM php:8.0-apache

# Instala extensões necessárias para o PDO e MySQL
RUN docker-php-ext-install pdo pdo_mysql

# Habilita o mod_rewrite para uso do .htaccess
RUN a2enmod rewrite

# Instala o Composer (cópia da imagem oficial)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

EXPOSE 80
