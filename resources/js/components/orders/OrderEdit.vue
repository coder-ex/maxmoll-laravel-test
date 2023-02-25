<template>
    <div class="container">
        <h3 style="text-align: center;">Редактирование заказа</h3>
        <div style="font-weight: 700;">заказ: ID {{ order.id }}</div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-1">CUSTOMER</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-1" v-model="order.customer">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-2">PHONE</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-2" v-model="order.phone">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-3">MANAGER</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-3" v-model="order.manager">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-4">TYPE</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-4" v-model="order.type">
        </div>
        <div class="input-group input-group-sm mb-3">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-5">STATUS</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-5" v-model="order.status">
        </div>

        <div style="font-weight: 700;">товаров в заказе: {{ order.order_items.length }}</div>
        <div class="input-group input-group-sm mb-3" v-for="item in order.order_items" v-bind:item="item" v-bind:key="item.id">
            <div class="input-group-prepend">
                <span class="input-group-text" id="order-5">COST</span>
            </div>
            <input type="text" class="form-control" aria-describedby="order-5" v-model="item.cost">
        </div>


        <router-link class="card-title" :to="{ name: 'orders', params: { orderId: order.id } }">
            <button type="button" class="btn btn-sm btn-success" v-on:click="saveData">Save</button>
        </router-link>

        <!-- ошибки вылетают и радуют глаз -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="isError">
            Ошибка загрузки данных !!
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
    name: 'OrderEdit',
    props: [
        'orderId'
    ],
    components: {
        Spin,
    },
    data() {
        return {
            order: {
                user: ""
            },
            loading: true,
            isError: false,
        };
    },

    async mounted() {
        try {
            let res = await axios.get('http://school.loc/api/orders/' + this.orderId);
            this.order = this.serializeOrder(res.data.order);

            console.log(res.data.order);

            if (this.order !== undefined) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
        }
    },
    methods: {
        saveData() {
            let body = JSON.stringify({
                id: this.order.id,
                type: this.order.id,
                status: this.order.id,
                manager: this.order.id,
                customer: this.order.id,
                phone: this.order.id,
                products: {
                    name: this.order.user.name,
                    count: this.order.user.name,
                    discount: this.order.user.name,
                }
            });
            console.log(body);

            // try {

            //     await axios.put('http://school.loc/api/orders/edit', {
            //         body: JSON.stringify(this.order)
            //     });
            // } catch (error) {
            //     console.log(error);
            //     this.isError = true;
            // }
        },
        serializeOrder(data) {
            let order = {};
            order.id = data.id;
            order.type = data.type;
            order.status = data.status;
            order.manager = data.user.name;
            order.customer = data.customer;
            order.phone = data.phone;
            order.order_items = [];
            for (let i = 0; i < data.order_items.length; i++) {
                order.order_items.push( data.order_items[i]);
            }

            // products: {
            //     name: data.user.name,
            //     count: data.user.name,
            //     discount: data.user.name,
            // }

            return order;
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
</style>
