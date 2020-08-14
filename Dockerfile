FROM php:7.4-cli
LABEL maintainer="marius.metzler@gmx.net"
COPY . /usr/src/myapp
WORKDIR /usr/src/myapp
#CMD [ "php", "./your-script.php" ]
