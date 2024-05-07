# с какого образа будем брать информацию
FROM php:fpm

# устанавливаем mysqli
RUN docker-php-ext-install mysqli && docker-php-ext-enable mysqli
