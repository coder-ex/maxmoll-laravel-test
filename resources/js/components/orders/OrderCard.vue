<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-2">
                <h3 style="text-align: center;">Заказ</h3>
            </div>
            <div v-if="read" class="col-md-2">
                <h3 style="text-align: center;">ПРОСМОТР</h3>
            </div>
            <div v-else class="col-md-2">
                <h3 style="text-align: center;">РЕДАКТИРОВАНИЕ</h3>
            </div>
        </div>

        <div class="row justify-content-left">
            <div class="col-md-9">
                <div style="font-weight: 700;">заказ: ID {{ order.id }}
                    <span>&nbsp&nbsp&nbsp</span>
                    <a v-if="read" type="button" class="btn-order" v-on:click="isRead">Редактировать</a>
                    <a v-else type="button" class="btn-order-save" v-on:click="checkOrder">Сохранить</a>
                    <a v-show="!read" type="button" class="btn-order-save" v-on:click="addGoods">&nbspтовар+</a>
                </div>
            </div>
        </div>

        <!-- Карточка заказа -->
        <div class="row justify-content-left">
            <div class="col-md-6">
                <div class="card-body">
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-1">CUSTOMER</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-1" v-model="order.customer"
                            v-bind:readonly="read">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-2">PHONE</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-2" v-model="order.phone"
                            v-bind:readonly="read">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-3">MANAGER</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-3" v-model="order.manager"
                            v-bind:readonly="read">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-4">TYPE</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-4" v-model="order.type"
                            v-bind:readonly="read">
                    </div>
                    <div class="input-group input-group-sm mb-3">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-5">STATUS</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-5" v-model="order.status"
                            v-bind:readonly="read">
                    </div>
                </div>
            </div>
        </div>

        <!-- товары в заказе -->
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card-body">
                    <div style="font-weight: 700;">товаров в заказе: {{ order.products.length }}</div>
                    <div class="input-group input-group-sm mb-3" v-for="(item, idx,) in order.products" v-bind:item="item"
                        v-bind:key="idx">
                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-6">Наименование</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-6" v-model="item.name"
                            v-bind:readonly="read">

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-7">Количество</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-7" v-model="item.count"
                            v-bind:readonly="read">

                        <div class="input-group-prepend">
                            <span class="input-group-text" id="order-8">Скидка</span>
                        </div>
                        <input type="text" class="form-control" aria-describedby="order-8" v-model="item.discount"
                            v-bind:readonly="read">
                        <div class="input-group-prepared">
                            <a v-show="!read" type="button" class="btn-order-save" v-on:click="delGoods(idx)">-</a>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <!-- ошибки вылетают и радуют глаз -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="isError">
            Ошибка загрузки данных !! {{ this.error }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="isShow">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <spin v-if="loading"></spin>
    </div>
</template>

<script>
import axios from "axios";
import Spin from "../Spiner";

export default {
    name: 'OrderCard',
    props: [
        'orderId'
    ],
    components: {
        Spin,
    },
    data() {
        return {
            order: {
                user: "",
                products: []
            },
            loading: true,
            isError: false,
            read: true,
            error: {},
        };
    },

    async mounted() {
        try {
            let res = await axios.get('http://school.loc/api/orders/' + this.orderId);
            this.order = this.serializeOrder(res.data.order);

            if (this.order !== undefined) this.loading = false;
        } catch (error) {
            //console.log(error);
            this.isError = true;
            this.error = error.message;
        }
    },
    methods: {
        async saveData() {
            try {
                let res = await axios.put('http://school.loc/api/orders/edit', this.order);
                //console.log(res);
            } catch (error) {
                //console.log(error);
                this.isError = true;
                this.error = error.message;
            }
        },
        checkOrder() {
            let flag = false;
            for (let i = 0; i < this.order.products.length; i++) {
                if (this.order.products[i].name === "" || this.order.products[i].count == 0) {
                    flag = true;
                    //console.log(this.order.products[i]);
                    break;
                }
            }

            //console.log(flag);

            if(!flag) {
                this.order.status = 'active';
                this.isRead();
                this.saveData();
            }
        },
        serializeOrder(data) {
            let order = {};
            order.id = data.id;
            order.type = data.type;
            order.status = data.status;
            order.manager = data.user.name;
            order.customer = data.customer;
            order.phone = data.phone;
            order.products = [];
            for (let i = 0; i < data.order_items.length; i++) {
                let product = {
                    name: data.order_items[i].product.name,
                    count: data.order_items[i].count,
                    discount: data.order_items[i].discount,
                }
                order.products.push(product);
            }
            //---
            return order;
        },
        addGoods() {
            this.order.products.push({
                name: "",
                count: 0,
                discount: 0.0,
            });
        },
        delGoods(value) {
            this.order.products.splice(value, 1);
        },
        isRead() {
            this.read = !this.read;
        },
        isShow() {
            this.isError = !this.isError;
        }
    },
};
</script>

<style lang="scss" scoped>
.tbody-edit {
    background: #fc8874;
}

.btn-order {
    padding-left: 5px;
    padding-right: 5px;
    border-style: solid;
    border-width: 1px;
    border-radius: 5px;
    border-color: #6c757d;
    color: #6c757d;
}

.btn-order-save {
    padding-left: 5px;
    padding-right: 5px;
    border-style: solid;
    border-width: 1px;
    border-radius: 5px;
    border-color: #ff2433;
    color: #ff2433;
}

// a.btn-order:link {
//     color: #0000d0;
//     /* Цвет ссылок */
//     padding: 0px;
//     /* Поля вокруг текста */
// }

a.btn-order:hover {
    background: #6c757d;
    /* Цвет фона под ссылкой */
    color: #ffffff;
    /* Цвет ссылки */
    text-decoration: none;
}

a.btn-order-save:hover {
    background: #ff2433;
    /* Цвет фона под ссылкой */
    color: #ffffff;
    /* Цвет ссылки */
    text-decoration: none;
}
</style>
