FROM trafex/alpine-nginx-php7
LABEL maintainer="marius.metzler@gmx.net"
COPY . /var/www/html
WORKDIR /var/www/html
# CMD [ "php", "-S", "0.0.0.0:8080" ]
#CMD [ "php", "./your-script.php" ]
