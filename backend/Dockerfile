FROM php:8.4-cli-alpine

RUN apk --no-cache update && \
    apk --no-cache add $PHPIZE_DEPS && \
    pecl install ev && \
    docker-php-ext-enable ev && \
    apk --no-cache del $PHPIZE_DEPS

RUN echo "error_reporting = E_ALL & ~E_DEPRECATED" > /usr/local/etc/php/conf.d/config.ini

COPY --from=composer /usr/bin/composer /usr/local/bin/composer
