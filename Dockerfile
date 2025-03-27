FROM php:8.0-apache

# Atualiza pacotes e instala extensões do PHP necessárias
RUN apt-get update && apt-get install -y \
    libzip-dev unzip git \
    && docker-php-ext-install pdo pdo_mysql zip \
    && a2enmod rewrite headers

# Define configurações do Apache
RUN sed -i 's/Listen 80/Listen 9000/' /etc/apache2/ports.conf
RUN sed -i 's/<VirtualHost \*:80>/<VirtualHost \*:9000>/' /etc/apache2/sites-available/000-default.conf
RUN echo "ServerName localhost" >> /etc/apache2/apache2.conf

# Define configurações do PHP
RUN echo "display_errors=On\nlog_errors=On\nerror_log=/var/log/php_errors.log" > /usr/local/etc/php/conf.d/custom.ini

# Instala o Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Define o diretório de trabalho
WORKDIR /var/www/html

# Copia e instala dependências do Composer
COPY ./backend/composer.json ./backend/composer.lock ./
RUN composer install --no-dev --optimize-autoloader

# Copia o código do backend
COPY ./backend /var/www/html

# Exposição da porta correta
EXPOSE 9000

# Comando de inicialização do Apache
CMD ["apache2-foreground"]
