## Тестовый проект Maxmoll

* Схема связей таблиц в БД - ./doc/diagram-table-test.dia
* Коллекция Postman к API - ./doc/maxmoll.postman_collection.json
* Общее тестовое ТЗ - ./doc/Разработчик. Задания.docx
* Дамп БД - ./doc/db_maxmoll.sql

>
    по условию выполнения необходимо сделать только часть 1 ТЗ
    общее описание:
    - в заказе мы можем задавать\редактировать тип, статус, менеджера (пользователя), покупателя, контактный телефон и товары
    - для выбранных товаров мы можем задать количество и скидку
    - количество больше чем stock указывать нельзя
    - при создании заказа или перевода ИЗ status=canceled остатки товаров вычитаются (необходима серия проверок и сообщения с ошибками, если действие невозможно)
    - при переводе заказа В status=canceled товары возвращаются на склад
    - товары, цены, остатки и менеджеров (пользователей) необходимо наполнить самостоятельно

* пустой скелет проекта - ветка master
* back-end с REST API - ветка server
* back-end + front-end - ветка shop-spa

### Общие требования к используемым технологиям
* БД проекта - mysql 5.7
* back-end
  * php 7.4+
  * laravel 8.x
* front-end
  * JavaScript es6+
  * vue 2
* весь проект выполнить как SPA

### Дополнение к проекту
* стек разработки:
  * php-fpm 8.1.16
  * mysql 5.7.41
  * nginx 1.23.3
* docker-compose.local: php-fpm + nginx + mysql + phpmyadmin
* docker network: 192.168.222.0/28
* nginx ip: 192.168.222.1:80
* mysql host/port: mysql/3306
* phpmyadmin ip/port: 192.168.222.1:8080
* основные настройки в .env.local
* все настройки и пакеты в php:
  * http:ip_host/phpinfo.php
  * http:ip_host/xdebug.php
* запуск и управление docker-compose через make файл
  * build | down | up | stop | start | ps
* для продуктовой ветки необходимо отделить mysql и phpmyadmin в отдельный docker-compose
