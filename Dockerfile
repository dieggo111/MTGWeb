FROM bitnami/php-fpm:latest
LABEL maintainer="marius.metzler@gmx.net"
COPY . /var/www/MTGWeb
WORKDIR /var/www/MTGWeb/public
CMD ["php", "-S", "0.0.0.0:9000"]
