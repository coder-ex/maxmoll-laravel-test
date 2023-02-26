<template>
    <div class="container">
        <h2 style="text-align: center;">Карточки товаров</h2>

        <div class="row">
            <div class="col-lg-4" v-for="item in GET_GOODS" v-bind:item="item" v-bind:key="item.id">
                <div class="card mt-3">
                    <div class="card-body">
                        <router-link class="card-title" :to="{ name: 'showProduct', params: { productId: item.id } }">
                            <h5 class="card-title">{{ item.name }}</h5>
                        </router-link>
                        <div style="size: 3px; text-align: right;">цена: {{ item.price }} usd</div>
                        <div style="size: 3px; text-align: right;">кол-во: {{ item.stock }} шт.</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ошибки вылетают и радуют глаз -->
        <div class="alert alert-danger alert-dismissible fade show" role="alert" v-if="GET_IS_ERROR">
            Ошибка загрузки данных !! {{ GET_ERRORS }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close" v-on:click="IS_SHOW_GOODS">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>

        <spin v-if="GET_LOADING"></spin>
    </div>
</template>

<script>
import Spin from "../Spiner";
import { mapGetters, mapActions } from "vuex";

export default {
    name: "Products",
    components: {
        Spin,
    },
    data: () => ({
        //data: [],
    }),
    computed: { ...mapGetters(['GET_GOODS', 'GET_ERRORS', 'GET_IS_ERROR', 'GET_LOADING']) },
    mounted() {
        this.update();
    },
    methods: {
        ...mapActions(['GOODS_TODO', 'IS_SHOW_GOODS',]),
        update() {
            this.GOODS_TODO();
        },
    }
}
</script>

<style></style>


