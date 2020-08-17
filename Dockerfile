FROM php:7.4-cli
LABEL maintainer="marius.metzler@gmx.net"
COPY . /usr/src/
WORKDIR /usr/src/
#CMD [ "php", "./your-script.php" ]
