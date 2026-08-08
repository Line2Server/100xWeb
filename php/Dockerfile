FROM php:8.1-fpm-alpine

# Instalar dependências
RUN apk add --no-cache     freetype-dev     libjpeg-turbo-dev     libpng-dev     libzip-dev     zip     unzip     && docker-php-ext-configure gd --with-freetype --with-jpeg     && docker-php-ext-install -j$(nproc)         pdo_mysql         mysqli         gd         opcache         zip

# Configurar OPcache para performance
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=1'; \
} > /usr/local/etc/php/conf.d/opcache-recommended.ini

# Configurar PHP para produção
RUN { \
    echo 'upload_max_filesize=64M'; \
    echo 'post_max_size=64M'; \
    echo 'max_execution_time=300'; \
    echo 'memory_limit=256M'; \
} > /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www/html
