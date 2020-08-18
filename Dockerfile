FROM php:7.4-cli
LABEL maintainer="marius.metzler@gmx.net"
RUN apt-get update
RUN apt-get install -y libpq-dev \
  && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
  && docker-php-ext-install pdo pdo_pgsql pgsql
COPY . /var/www/MTGWeb
WORKDIR /var/www/MTGWeb/public
CMD ["php", "-S", "0.0.0.0:9000"]
