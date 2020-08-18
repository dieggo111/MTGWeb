FROM bitnami/php-fpm:latest
LABEL maintainer="marius.metzler@gmx.net"
COPY . /var/www/html
WORKDIR /var/www/html/public
CMD ["php", "-S", "0.0.0.0:9000"]
