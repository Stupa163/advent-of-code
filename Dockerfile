FROM php:latest

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod +x /usr/local/bin/install-php-extensions
RUN install-php-extensions xdebug zip
RUN rm -f /usr/local/bin/install-php-extensions

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer

WORKDIR /app
CMD ["php", "-a"]