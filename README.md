## REST API к тестовому проету maxmoll

__back-end с REST API - ветка server__

* Схема связей таблиц в БД - ./doc/diagram-table-test.dia
* Коллекция Postman к API - ./doc/maxmoll.postman_collection.json
* Общее тестовое ТЗ - ./doc/Разработчик. Задания.docx
* Дамп БД - ./doc/db_maxmoll.sql

>
    общее описание REST API:
    - в заказе мы можем задавать\редактировать тип, статус, менеджера (пользователя), покупателя, контактный телефон и товары
    - для выбранных товаров мы можем задать количество и скидку
    - количество больше чем stock указывать нельзя
    - при создании заказа или перевода ИЗ status=canceled остатки товаров вычитаются (необходима серия проверок и сообщения с ошибками, если действие невозможно)
    - при переводе заказа В status=canceled товары возвращаются на склад
    - товары, цены, остатки и менеджеров (пользователей) необходимо наполнить самостоятельно

### REST API
__[ /api/users ]__
* POST /api/users/add - добавление пользователя
* GET  /api/users/{id} - получение пользователя по id
* PUT  /api/users/edit - редактирование пользователя
* GET  /api/users/remove/{id} - удаление пользователя по id
* GET  /api/users/ - получение всех пользователей

__[ /api/products ]__
* POST /add - добавление товара
* GET  /api/products/{id} - получение товара по id
* PUT  /api/products/edit - редактирование товара
* GET  /api/products/remove/{id} - удаление товара
* GET  /api/products/ - получение всех товаров

__[ /api/orders ]__
* POST /api/orders/add - создание заказа
* GET  /api/orders/{id} - получение заказа по id
* PUT  /api/orders/edit - редактирование заказа
* GET  /api/orders/cancelled/{id} - перевод заказа В отмененные
* GET  /api/orders/recancelled/{id} - перевод заказа ИЗ отмененных в активные
* GET  /api/orders/ - получение всех заказов

