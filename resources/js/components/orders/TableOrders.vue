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
                <tr class="table-warning" v-for="item in data" v-bind:item="item" v-bind:key="item.id"
                    v-on:click="onClick(item)">

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
import Spin from "../Spiner";

export default {
    name: 'TableOrders',
    components: {
        Spin,
    },
    data() {
        return {
            data: [],
            isError: false,
            loading: true,
        }
    },
    async mounted() {
        try {
            let res = await axios.get('http://school.loc/api/orders');
            this.data = await res.data.products;

            if (this.data.length > 0) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
            this.loading = false;
        }
    },
    methods: {
        onClick(item) {
            console.log(item.id);
        },
        isShow() {
            this.isError = !this.isError;
        }
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
