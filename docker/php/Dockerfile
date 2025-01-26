FROM php:8.4-fpm

RUN apt-get update && apt-get install -y \
        unzip \
		libzip-dev \
		libxml2-dev \
		less \
		libsodium-dev -y \
		libpq-dev \
		nginx-full \
		bash \
		cron \
		nano \
	&& docker-php-ext-install \
        sodium \
        pdo \
        pdo_pgsql \
        zip \
        intl \
        soap \
	&& rm -rf /var/lib/apt/lists/*

RUN echo 'umask 0000' >> /root/.bashrc

COPY docker/php/conf.d/xdebug.ini $PHP_INI_DIR/conf.d/xdebug.ini
 
RUN pecl channel-update pecl.php.net && \
    pecl install xdebug-3.4.1 && \
    docker-php-ext-enable xdebug

# Nginx configuration
COPY docker/nginx/snippets /etc/nginx/snippets
COPY docker/nginx/default.conf /etc/nginx/sites-available/
RUN ln -s /etc/nginx/sites-available/default.conf /etc/nginx/sites-enabled && rm /etc/nginx/sites-enabled/default

RUN mkdir -p /opt/service /var/www/.composer /var/www/.config;chown -R www-data:www-data /opt/service /var/www/.composer /var/www/.config

RUN apt-get update && apt-get install -y --no-install-recommends --no-install-suggests supervisor
COPY docker/supervisord/supervisor.conf /etc/supervisor/conf.d/supervisor.conf

USER www-data

WORKDIR /opt/service

COPY --chown=www-data:www-data ./ /opt/service
RUN rm -f /opt/service/src/log/*.log && mkdir -p /opt/service/var/cache
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY --chown=www-data:www-data ./.env ./.env.local
RUN composer install

USER root

COPY ./docker/cron/cron.txt /home/cron.txt
RUN crontab -u root /home/cron.txt && rm /home/cron.txt

EXPOSE 9000

CMD ["supervisord"]