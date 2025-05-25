FROM php:7.4-cli

WORKDIR /usr/src/myapp
COPY public/ ./

CMD ["php", "-S", "0.0.0.0:10000"]
