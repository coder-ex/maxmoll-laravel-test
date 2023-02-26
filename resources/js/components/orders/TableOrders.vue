<template>
    <div class="container">

        <!-- Таблица заказов -->
        <table class="table table-sm table-bordered table-dark table-hover">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">CUSTOMER</th>
                    <th scope="col">PHONE</th>
                    <th scope="col">MANAGER</th>
                    <th scope="col">TYPE</th>
                    <th scope="col">STATUS</th>
                    <th scope="col">&nbsp</th>
                </tr>
            </thead>
            <tbody>
                <tr class="table-warning" v-for="item in GET_ORDERS" v-bind:item="item" v-bind:key="item.id">

                    <th class="bg-dark" scope="row">{{ item.id }}</th>
                    <td class="td-cell">{{ item.customer }}</td>
                    <td class="td-cell">{{ item.phone }}</td>
                    <td class="td-cell">{{ item.user.name }}</td>
                    <td class="td-cell">{{ item.type }}</td>
                    <td class="td-cell">{{ item.status }}</td>

                    <td style="background: #ffffff;">
                        <router-link class="card-title" :to="{ name: 'orderCard', params: { orderId: item.id } }">
                            <button type="button" class="btn btn-sm btn-outline-success">Карточка</button>
                        </router-link>
                    </td>
                </tr>

            </tbody>
        </table>

        <!-- ошибки вылетают и радуют глаз -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="GET_IS_ERROR_ORDERS">
            Ошибка загрузки данных !! {{ GET_ERRORS_ORDERS }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="IS_SHOW_ORDERS">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <spin v-if="GET_LOADING_ORDERS"></spin>
    </div>
</template>

<script>
import Spin from "../Spiner";
import { mapGetters, mapActions } from "vuex";

export default {
    name: 'TableOrders',
    components: {
        Spin,
    },
    data: () => ({}),
    computed: { ...mapGetters(['GET_ORDERS', 'GET_ERRORS_ORDERS', 'GET_IS_ERROR_ORDERS', 'GET_LOADING_ORDERS']) },
    mounted() {
        this.update();
    },
    methods: {
        ...mapActions(['ORDERS_TODO', 'IS_SHOW_ORDERS',]),
        update() {
            this.ORDERS_TODO();
        },
    }
}
</script>

<style>
/* .thead-blue {
    background: #639ffa;
}

.tbody-lite {
    background: #c1ebff;
} */
.td-cell {
    color: #303030;
}
</style>
