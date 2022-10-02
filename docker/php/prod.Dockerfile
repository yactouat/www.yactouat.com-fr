FROM php:8.1.10-apache

# install required system dependencies
RUN apt-get update \
    && apt-get install -y sudo git zip unzip

# copy Apache virtual host
COPY ./docker/apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# Use the PORT environment variable in Apache configuration files.
# https://cloud.google.com/run/docs/reference/container-contract#port
RUN sed -i 's/80/${PORT}/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# enabling Apache mod rewrite
RUN a2enmod rewrite

# create system user ("wwwyactouatdotcom" with uid 1000)
RUN useradd -G www-data,root -u 1000 -d /home/wwwyactouatdotcom wwwyactouatdotcom
RUN mkdir /home/wwwyactouatdotcom && \
    chown -R wwwyactouatdotcom:wwwyactouatdotcom /home/wwwyactouatdotcom

# copy existing application directory contents with its permissions
COPY --chown=wwwyactouatdotcom:wwwyactouatdotcom . /var/www/html

# removing git folder
RUN rm -rf /var/www/html/.git

# Configure PHP for production
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"
# https://github.com/docker-library/docs/blob/master/php/README.md#configuration

# configure PHP for Cloud Run
# precompile PHP code with opcache
# the `-j "$(nproc)"` option tells the compiler to execute this recipe in a parallel job
RUN docker-php-ext-install -j "$(nproc)" opcache
# specific ini options for Cloud Run and opcache
RUN set -ex; \
  { \
    echo "; Cloud Run enforces memory & timeouts"; \
    echo "memory_limit = -1"; \
    echo "max_execution_time = 0"; \
    echo "; File upload at Cloud Run network limit"; \
    echo "upload_max_filesize = 32M"; \
    echo "post_max_size = 32M"; \
    echo "; Configure Opcache for Containers"; \
    echo "opcache.enable = On"; \
    echo "opcache.validate_timestamps = Off"; \
    echo "; Configure Opcache Memory (Application-specific)"; \
    echo "opcache.memory_consumption = 32"; \
  } > "$PHP_INI_DIR/conf.d/cloud-run.ini"

# setting PHP conf
# shared PHP conf
RUN mv /var/www/html/docker/php/shared.ini /usr/local/etc/php/conf.d/shared.ini

# get latest Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # changing user (because cannot run Composer as root inside container)
USER wwwyactouatdotcom

# getting into app' directory
WORKDIR /var/www/html

# copy the composer.json for prod (no hooks scripts, dev dependencies, etc.)
COPY ./composer.prod.json /var/www/html/composer.json
# installing Composer deps for prod, the vendor folder will only be populated inside the container
RUN composer install --no-dev

# setting the app' environment
ENV APP_ENV="PROD"

# running Apache
CMD apache2-foreground
