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

# Instala e configura o Xdebug
# COPY 90-xdebug.ini "${PHP_INI_DIR}/conf.d"
RUN { \
    echo "xdebug.mode=debug"; \
    echo "xdebug.discover_client_host=0"; \
    echo "xdebug.client_host=host.docker.internal"; \
    echo "xdebug.start_with_request=yes"; \
    echo "xdebug.client_port=9003"; \
    echo "xdebug.log_level=0"; \
    } > "${PHP_INI_DIR}/conf.d/90-xdebug.ini"

RUN pecl install -o -f xdebug \
    && docker-php-ext-enable xdebug

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

RUN groupadd -g ${USER_GID} mygroup && \
    useradd -G www-data,mygroup -u ${USER_UID} -d /home/localuser localuser && \
    chown -R localuser:mygroup /var/www

USER localuser

EXPOSE 9000

CMD ["php-fpm"]