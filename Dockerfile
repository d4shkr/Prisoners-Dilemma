# с какого образа будем брать информацию
FROM php:fpm

# устанавливаем pdo и mysql модули
RUN docker-php-ext-install pdo pdo_mysql
