FROM php:8.2-cli

RUN pecl install swoole && docker-php-ext-enable swoole
