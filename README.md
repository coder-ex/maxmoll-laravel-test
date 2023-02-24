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


### установка vue в laravel и тестовая настройка

* npm install --save-dev vue@2
* npm run dev - установит дополнительные зависимости 
> _[  npm install vue-template-compiler vue-loader@^15.9.5 --save-dev --legacy-peer-deps ]_
* npm run dev - запросит запустить еще раз
> если все сделано правильно, то установятся пакеты
>   * "vue": "^2.6.12"
>   * "vue-loader": "^15.9.6"
>   * "vue-template-compiler": "^2.6.12"

* ./resources/js/app.js - добавить 
>
    require('./bootstrap');
    window.Vue = require('vue').default;
    /**
     * The following block of code may be used to automatically register your
     * Vue components. It will recursively scan this directory for the Vue
     * components and automatically register them with their "basename".
     *
     * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
     */
    
    // const files = require.context('./', true, /\.vue$/i)
    // files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

    Vue.component('example-component', require('./components/ExampleComponent.vue').default);

    /**
    * Next, we will create a fresh Vue application instance and attach it to
    * the page. Then, you may begin adding components to this application
    * or customize the JavaScript scaffolding to fit your unique needs.
    */

    const app = new Vue({
        el: '#app',
    });

* ./resources/js/components - добавить компонент ExampleComponent.vue
>
    <template>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">Example Component</div>

                        <div class="card-body">
                            I'm an example component.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </template>

    <script>
        export default {
            mounted() {
                console.log('Component mounted.')
            }
        }
    </script

* npm run dev - выполнить для сборки js
* добавить vue компонент в шаблон blade
>
    <body>
        <div id="app">
            <example-component></example-component>
        </div>
    </body>

* ./route/web.php добавить общий маршрут
>
    Route::get('/{any}', function () {
        return view('index');
    })->where('any', '.*');
