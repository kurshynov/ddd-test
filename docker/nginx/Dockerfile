FROM ubuntu:22.04 AS ddd-nginx

ENV TZ=Europe/Kiev
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

ARG PHP_VERSION
ARG PROJECT_NAME
ARG WORKDIR
ARG LOGDIR=$WORKDIR/docker/nginx/logs
ARG UID
ARG GID
ARG CONTAINER_USER_NAME
ARG CONTAINER_GROUP_NAME=$CONTAINER_USER_NAME
ARG CONTAINER_USER_PASSWORD=$CONTAINER_USER_NAME

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && \
    apt-get install --reinstall -y ubuntu-keyring

RUN rm -rf /var/lib/apt/lists/* && \
    apt-get clean && \
    apt-get update

# Установка необходимых инструментов
RUN apt-get update && apt-get install -y \
    curl \
    ca-certificates \
    gnupg \
    lsb-release \
    software-properties-common \
    apt-transport-https \
    git \
    nginx \
    sudo \
    cron \
    nano \
    htop \
    zip \
    unzip \
    net-tools

RUN add-apt-repository ppa:ondrej/php

# Обновление и установка PHP
RUN apt-get update && apt-get install -y \
    php${PHP_VERSION} \
    php${PHP_VERSION}-fpm \
    php${PHP_VERSION}-dev \
    php${PHP_VERSION}-xml \
    php${PHP_VERSION}-pgsql \
    php${PHP_VERSION}-zip \
    php${PHP_VERSION}-intl \
    php${PHP_VERSION}-ldap \
    php${PHP_VERSION}-mbstring \
    php${PHP_VERSION}-curl \
    php${PHP_VERSION}-gd \
    php${PHP_VERSION}-bcmath \
    php${PHP_VERSION}-sockets \
    php${PHP_VERSION}-gmp \
    php${PHP_VERSION}-opcache \
    php${PHP_VERSION}-imagick

# Очистка
RUN apt-get clean && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -r -g $GID $CONTAINER_GROUP_NAME \
    && useradd -rm -u $UID -g $CONTAINER_GROUP_NAME $CONTAINER_USER_NAME \
    && echo "$CONTAINER_USER_NAME:$CONTAINER_USER_PASSWORD" | chpasswd \
    && usermod -aG sudo $CONTAINER_USER_NAME


RUN mkdir -p $WORKDIR && mkdir -p $LOGDIR

RUN touch $LOGDIR/nginx_access.log \
    && touch $LOGDIR/nginx_error.log \
    && touch $LOGDIR/nginx_notice.log \
    && touch $LOGDIR/server_error.log

RUN chmod -R 777 $LOGDIR

# Nginx config
RUN rm -rf /etc/nginx/sites*
COPY config/nginx.conf /etc/nginx/nginx.conf
COPY config/server.conf /etc/nginx/conf.d/$PROJECT_NAME.conf

# Replacing variable templates in config files
RUN sed -i \
# cli
        -e "s/;date.timezone =.*/date.timezone = UTC/g" /etc/php/${PHP_VERSION}/cli/php.ini \
# fpm php.ini
        -e "s/;date.timezone =.*/date.timezone = UTC/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/;extension =.*/extension = php_gmp.so/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/display_errors = Off/display_errors = On/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/upload_max_filesize = .*/upload_max_filesize = 10M/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/post_max_size = .*/post_max_size = 12M/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/;cgi.fix_pathinfo=1/cgi.fix_pathinfo=0/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
        -e "s/max_execution_time = .*/max_execution_time = 60/g" /etc/php/${PHP_VERSION}/fpm/php.ini \
# php-fpm.conf
        -e "s/pid =.*/pid = \/var\/run\/php${PHP_VERSION}-fpm.pid/g" /etc/php/${PHP_VERSION}/fpm/php-fpm.conf \
        -e "s/error_log =.*/error_log = \/proc\/self\/fd\/2/g" /etc/php/${PHP_VERSION}/fpm/php-fpm.conf \
        -e "s/;daemonize\s*=\s*yes/daemonize = no/g" /etc/php/${PHP_VERSION}/fpm/php-fpm.conf \
# www.conf
        -e "s/;catch_workers_output = .*/catch_workers_output = yes/g" /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
        -e "s/www-data/$CONTAINER_USER_NAME/" /etc/php/${PHP_VERSION}/fpm/pool.d/www.conf \
# nginx.conf
        -e "s|\$CONTAINER_USER_NAME|$CONTAINER_USER_NAME|" /etc/nginx/nginx.conf \
        -e "s|\$LOGDIR|$LOGDIR|" /etc/nginx/nginx.conf \
# server.conf
        -e "s|\${WORKDIR}|$WORKDIR|" /etc/nginx/conf.d/$PROJECT_NAME.conf \
        -e "s|\${LOGDIR}|$LOGDIR|" /etc/nginx/conf.d/$PROJECT_NAME.conf \
        -e "s|\${PHP_VERSION}|$PHP_VERSION|" /etc/nginx/conf.d/$PROJECT_NAME.conf \
        -e "s|\${PROJECT_NAME}|$PROJECT_NAME|" /etc/nginx/conf.d/$PROJECT_NAME.conf \
    && mkdir -p /run/php

WORKDIR $WORKDIR
EXPOSE 80 9000
CMD /usr/sbin/php-fpm8.3 --force-stderr --nodaemonize --fpm-config /etc/php/8.3/fpm/php-fpm.conf & nginx -g "daemon off;"
