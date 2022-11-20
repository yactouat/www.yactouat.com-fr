FROM php:8.1.12-apache

# install required system dependencies
RUN apt-get update \
    && apt-get install -y sudo git zip unzip

# copy Apache virtual host
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# enabling Apache mod rewrite
RUN a2enmod rewrite

# create system user ("wwwyactouatdotcom" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/wwwyactouatdotcom wwwyactouatdotcom
RUN mkdir /home/wwwyactouatdotcom && \
    chown -R wwwyactouatdotcom:wwwyactouatdotcom /home/wwwyactouatdotcom

# copy existing application directory contents with its permissions
COPY --chown=wwwyactouatdotcom:wwwyactouatdotcom . /var/www/html

# setting PHP conf
# XDebug
RUN pecl install xdebug \
    && docker-php-ext-enable xdebug
# shared PHP conf
RUN mv /var/www/html/docker/php/shared.ini /usr/local/etc/php/conf.d/shared.ini
# error reporting is suitable for DEV here
RUN mv /var/www/html/docker/php/dev.ini /usr/local/etc/php/conf.d/dev.ini

# get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# changing user (because cannot run Composer as root inside container)
USER wwwyactouatdotcom

# getting into app' directory
WORKDIR /var/www/html

# installing Composer deps, the vendor folder will only be populated inside the container
RUN composer install

# running Apache
CMD apache2-foreground


