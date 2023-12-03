FROM php:8.1-fpm

RUN apt-get update && apt-get install -y \
            git \
            curl \
            libpng-dev \
            libonig-dev \
            libxml2-dev \
            zip \
            unzip\
	    libfreetype6-dev \
	    libjpeg62-turbo-dev \
	    && docker-php-ext-configure gd --enable-gd --with-freetype --with-jpeg \
	    && docker-php-ext-install gd

RUN apt-get update && apt-get install -y default-mysql-client && rm -rf /var/lib/apt

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath

RUN php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
RUN php -r "if (hash_file('sha384', 'composer-setup.php') === '55ce33d7678c5a611085589f1f3ddf8b3c52d662cd01d4ba75c0ee0459970c2200a51f492d557530c71c15d8dba01eae') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
RUN php composer-setup.php
RUN php -r "unlink('composer-setup.php');"
RUN mv composer.phar /usr/local/bin/composer

# ADD php.ini /usr/local/etc/php/conf.d/php.ini

WORKDIR /var/www

CMD ["php-fpm"]
