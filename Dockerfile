FROM php:7.4-cli
LABEL maintainer="marius.metzler@gmx.net"
COPY . /usr/src/
WORKDIR /usr/src/
EXPOSE 8080
CMD [ "php", "-S", "0.0.0.0:8080" ]
#CMD [ "php", "./your-script.php" ]
