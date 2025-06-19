FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    nano \
    cron \
    bash \
    curl \
    libpng-dev \
    libzip-dev \
    zlib1g-dev \
    unzip \
    procps \
    supervisor \
    && docker-php-ext-install gd zip pdo pdo_mysql pcntl

RUN docker-php-ext-configure pcntl --enable-pcntl

RUN ln -snf /usr/share/zoneinfo/America/Sao_Paulo /etc/localtime \
    && echo "America/Sao_Paulo" > /etc/timezone

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . /var/www/html
COPY .env /var/www/html/.env

COPY dockerfiles/supervisor-queue.conf /etc/supervisor/conf.d/supervisor-queue.conf
RUN chmod 0644 /etc/supervisor/conf.d/supervisor-queue.conf

COPY dockerfiles/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

EXPOSE 9000