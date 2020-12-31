FROM composer:1.6.5 as build

WORKDIR /app
COPY . /app
COPY cicd/configfile/.dockerignore /app
RUN composer install

FROM php:7.4.2-apache

RUN docker-php-ext-install bcmath opcache mysqli pdo pdo_mysql\
    && rm -rf /tmp/* \
    && echo "PHP extension installed" \
    && php -m

ENV PHP_OPCACHE_ENABLE="1" \
    PHP_OPCACHE_REVALIDATE_FREQ="0" \
    PHP_OPCACHE_VALIDATE_TIMESTAMPS="0" \
    PHP_OPCACHE_MAX_ACCELERATED_FILES="8000" \
    PHP_OPCACHE_MEMORY_CONSUMPTION="192" \
    PHP_OPCACHE_MAX_WASTED_PERCENTAGE="10" \
    PHP_OPCACHE_INTERNED_STRINGS_BUFFER="0" \
    PHP_OPCACHE_FAST_SHUTDOWN="1" \
    PHP_OPCACHE_SAVE_COMMENTS="1" \
    APP_KEY="base64:1t/Z1pSMKFYcHXsgxDb+q/Maf5KUp/bJSlIlxT+DkkU=" \
    APP_ENV="local" \
    APP_NAME="intools" \
    APP_DEBUG="true" \
    APP_URL="http://localhost" \
    LOG_CHANNEL="stack" \
    AUTH_CLIENT_ID="4368c6b6-89ad-42c4-8d7b-845d0d485ad7" \
    AUTH_CLIENT_SECRET="e2XXvhowNC04MlvktrhH" \
    ACCOUNTSVC_URL="https://accountsvc.staging-0.cilientname.id" \
    RATESVC_URL="https://ratesvc.staging-0.cilientname.id"



EXPOSE 80

COPY --from=build /app /var/www/html
COPY cicd/configfile/.dockerignore /var/www/html

COPY cicd/docker/override /
RUN chown -R www-data:www-data /var/www/html/storage \
    && a2enmod rewrite
