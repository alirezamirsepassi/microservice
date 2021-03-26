FROM php:7.4-cli

RUN apt-get update --fix-missing \
    && apt-get install -y libpq-dev curl wget zip unzip git \
    && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN git clone --depth 1 --branch v1.5.0 https://github.com/edenhill/librdkafka.git \
    && ( \
        cd librdkafka \
        && ./configure \
        && make \
        && make install \
    ) \
    && pecl install rdkafka \
    && echo "extension=rdkafka.so" > /usr/local/etc/php/conf.d/rdkafka.ini


WORKDIR /srv/app

COPY --from=composer:2.0 /usr/bin/composer /usr/bin/composer

ENV COMPOSER_ALLOW_SUPERUSER=1

COPY ./composer.json ./composer.lock /srv/app/

RUN set -eux; \
	composer install --prefer-dist --no-dev --no-scripts --no-progress; \
	composer clear-cache

RUN composer dump-autoload --no-interaction

COPY src src/

VOLUME /srv/app


CMD ["php", "./src/index.php"]
