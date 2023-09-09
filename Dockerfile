FROM php:8.2-cli
COPY . /usr/src/jungo_test
WORKDIR /usr/src/jungo_test
CMD [ "php", "./test.php" ]