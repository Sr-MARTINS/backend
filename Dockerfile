FROM php:8.2-fpm

# Instala dependências do sistema
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libicu-dev \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql zip gd

# Instala Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define diretório de trabalho
WORKDIR /var/www/html

# Copia arquivos do projeto
#COPY . .

# Instala dependências do Symfony
#RUN composer install --no-interaction --prefer-dist --optimize-autoloader

# Permissões para cache/log
#RUN chown -R www-data:www-data var

EXPOSE 9000
CMD ["php-fpm"]