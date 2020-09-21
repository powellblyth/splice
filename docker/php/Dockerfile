
FROM php:7.4-fpm
#RUN apt-get update && apt-get install -y \
#		#libfreetype6-dev \
#		#libjpeg62-turbo-dev \
#		libpng-dev libzip-dev \
#		 libmcrypt-dev \
#	wget libicu-dev \
#	autoconf gcc make g++ zlib1g-dev \
#	 && ffmpeg libmagickwand-dev \
#	&& docker-php-ext-configure gd --with-freetype --with-jpeg \
#	&& docker-php-ext-install mysqli pdo pdo_mysql zip soap bcmath pcntl -j$(nproc) \
#	&& docker-php-ext-configure pdo_mysql --with-pdo-mysql=mysqlnd \
#           && docker-php-ext-configure mysqli --with-mysqli=mysqlnd
#

RUN apt-get update && \
    apt-get install -y libxml2-dev git libpng-dev libmcrypt-dev wget libicu-dev autoconf gcc make g++ zlib1g-dev ffmpeg libmagickwand-dev \
    libzip-dev libfreetype6-dev libxpm-dev libwebp-dev libjpeg62-turbo-dev && \
   # pecl install imagick && \
   # docker-php-ext-enable imagick && \
   # pear install -a SOAP-0.13.0 && \
    docker-php-ext-configure intl && \
    #docker-php-ext-configure gd --with-freetype --with-jpeg && \
    docker-php-ext-install intl pdo pdo_mysql zip bcmath pcntl && \
    apt-get install npm -y

COPY --from=composer:1.9 /usr/bin/composer /usr/bin/composer

#RUN docker-php-ext-install mysqli

#//ENV APACHE_DOCUMENT_ROOT /var/www/html/docker/php-fpm

#RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
#RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf
