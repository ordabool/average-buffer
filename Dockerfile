FROM php:7.4.3-cli

RUN apt-get update

# Install Xdebug 3.1.6
RUN apt-get install curl -y
RUN apt-get install automake -y
RUN curl -O https://xdebug.org/files/xdebug-3.1.6.tgz
RUN tar -xvzf xdebug-3.1.6.tgz
WORKDIR /xdebug-3.1.6
RUN phpize
RUN ./configure
RUN make
RUN cp modules/xdebug.so /usr/local/lib/php/extensions/no-debug-non-zts-20190902
RUN touch /usr/local/etc/php/conf.d/99-xdebug.ini
RUN echo "zend_extension = xdebug" >> /usr/local/etc/php/conf.d/99-xdebug.ini

# Install git for easy development
RUN apt-get install git -y

WORKDIR /usr/src/jungo_test

# Hang the container by running bash
CMD [ "bash" ] 