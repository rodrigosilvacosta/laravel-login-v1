FROM php:8.3-fpm

ARG USER_UID
ARG USER_GID

RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    zip \
    unzip \
    curl \
    git \
    npm \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN groupadd -g ${USER_GID} mygroup && \
    useradd -G www-data,mygroup -u ${USER_UID} -d /home/localuser localuser && \
    chown -R localuser:mygroup /var/www

USER localuser

EXPOSE 9000

CMD ["php-fpm"]