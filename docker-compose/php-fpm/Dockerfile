FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
            git \
            curl \
            libpng-dev \
            libonig-dev \
            libxml2-dev \
            zip \
            librabbitmq-dev \
            librabbitmq4 \
            unzip\
	        libfreetype6-dev \
	        libjpeg62-turbo-dev \
	&& docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd \
    && docker-php-ext-install sockets

RUN pecl install amqp && docker-php-ext-enable amqp

RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === 'e21205b207c3ff031906575712edab6f13eb0b361f2085f1f1237b7126d785e826a450292b6cfd1d64d92e6563bbde02') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# ADD php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www

CMD ["php-fpm"]
