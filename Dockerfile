FROM php:8.3-apache
MAINTAINER Giorgio Santini <giorgiom.santini@gmail.com>

EXPOSE 80

RUN apt-get update && apt-get -y upgrade && apt-get install -y  \
        ntp  \
        zip  \
        libzip-dev \
        git  \
        curl  \
        libcurl4 \
        libcurl4-openssl-dev \
        libonig-dev  \
        nano  \
        wget  \
        sqlite3  \
        libgd3  \
        zlib1g-dev  \
        systemctl  \
        libmcrypt-dev \
        libonig-dev \
        libxml2-dev \
        coreutils \
        sudo \
        unzip \
        libicu-dev \
        libsqlite3-dev \
        && docker-php-ext-configure intl \
        && docker-php-ext-install -j$(nproc) opcache pdo_sqlite mbstring pcntl posix intl curl zip \
        && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
        && echo Europe/Rome > /etc/timezone && ln -sf /usr/share/zoneinfo/Europe/Rome /etc/localtime && dpkg-reconfigure -f noninteractive tzdata


COPY apache.conf /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

COPY application /var/www/html
COPY entrypoint.sh /var/www/entrypoint.sh
RUN chown -R www-data: /var/www && chmod +x /var/www/entrypoint.sh

USER www-data

WORKDIR /var/www/html
RUN composer self-update
RUN composer config --global repo.packagist composer https://packagist.org
RUN composer install --no-ansi --no-interaction --no-progress --optimize-autoloader --prefer-dist


USER root

ENTRYPOINT ["docker-php-entrypoint", "/var/www/entrypoint.sh"]
