FROM ubuntu:22.04

# UPDATE & INSTALL
RUN apt update && \
    apt install -y mc \
    wget \
    build-essential \
    libpcre3 \
    libpcre3-dev \
    zlib1g \
    zlib1g-dev \
    libssl-dev \
    libgd-dev \
    libxml2 \
    libxml2-dev \
    uuid-dev \
    cron \
    curl

# BUILD NGINX
# http://nginx.org/en/docs/configure.html
RUN wget http://nginx.org/download/nginx-1.28.0.tar.gz && \
    tar xfz nginx-1.28.0.tar.gz && \
    cd nginx-1.28.0 && \
    ./configure \
    --prefix=/var/www/html \
    --sbin-path=/usr/sbin/nginx \
    --conf-path=/etc/nginx/nginx.conf \
    --http-log-path=/var/log/nginx/access.log \
    --error-log-path=/var/log/nginx/error.log \
    --with-pcre  \
    --lock-path=/var/lock/nginx.lock \
    --pid-path=/var/run/nginx.pid \
    --with-http_ssl_module \
    --with-http_image_filter_module=dynamic \
    --modules-path=/etc/nginx/modules \
    --with-http_v2_module \
    --with-stream=dynamic \
    --with-http_addition_module \
    --with-http_mp4_module && \
    make && \
    make install && \
    cd .. && \
    rm nginx-1.28.0.tar.gz && \
    rm -rf nginx-1.28.0

# OpenSSL
RUN mkdir /etc/nginx/creds
COPY ./openssl/openssl.cnf /etc/nginx/creds

ARG CERT_PASSWORD=pass
ARG CERT_DOMAIN=local.sch

RUN cd /etc/nginx/creds && \
    openssl genrsa -des3 -passout pass:$CERT_PASSWORD -out ./$CERT_DOMAIN.key 2048 && \
    openssl rsa -in ./$CERT_DOMAIN.key -passin pass:$CERT_PASSWORD -out ./$CERT_DOMAIN.key && \
    openssl req -config ./openssl.cnf -new -key ./$CERT_DOMAIN.key -out ./$CERT_DOMAIN.csr -passin pass:$CERT_PASSWORD && \
    openssl x509 -req -days 365 -in ./$CERT_DOMAIN.csr -signkey ./$CERT_DOMAIN.key -out ./$CERT_DOMAIN.crt 


# INSTALL PHP
# https://tecadmin.net/how-to-install-php-on-ubuntu-22-04/
RUN apt install -y software-properties-common ca-certificates lsb-release apt-transport-https && \
    LC_ALL=C.UTF-8 add-apt-repository ppa:ondrej/php && \
    apt update 

ARG DEBIAN_FRONTEND=noninteractive

RUN apt install -y --no-install-recommends php8.3 && \
    apt install -y \
    php8.3-bcmath \
    php8.3-ctype \
    php8.3-curl \
    php8.3-dom \
    php8.3-fpm \
    php8.3-gd \
    php8.3-iconv \
    php8.3-intl \
    php8.3-mbstring \
    php8.3-mysql \
    php8.3-soap \
    php8.3-sockets \
    php8.3-xsl \
    php8.3-zip \
    php8.3-xdebug

# https://www.digitalocean.com/community/tutorials/how-to-install-php-8-1-and-set-up-a-local-development-environment-on-ubuntu-22-04
RUN curl -sS https://getcomposer.org/installer -o /tmp/composer-setup.php && \
    php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

RUN usermod -u 1000 www-data && groupmod -g 1000 www-data
RUN mkdir -p /var/log/app && chown www-data:www-data /var/log/app

COPY ./start.sh /usr/bin
RUN chmod u+x /usr/bin/start.sh

WORKDIR /usr/share/nginx

EXPOSE 80
EXPOSE 443

CMD ["/usr/bin/start.sh"]
