<template>
    <div class="container">
        <h2 style="text-align: center;">Карточки товаров</h2>

        <div class="row">
            <div class="col-lg-4" v-for="product in products" v-bind:item="product" v-bind:key="product.id">
                <div class="card mt-3">
                    <div class="card-body">
                        <router-link class="card-title" :to="{ name: 'showProduct', params: { id: product.id } }">
                            <h5 class="card-title">{{ product.name }}</h5>
                        </router-link>
                        <div style="size: 3px; text-align: right;">цена: {{ product.price }} usd</div>
                        <div style="size: 3px; text-align: right;">кол-во: {{ product.stock }} шт.</div>
                    </div>
                </div>
            </div>
        </div>
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
    name: "Products",
    components: {
        Spin,
    },
    data() {
        return {
            products: [],
            isError: false,
            loading: true,
        }
    },
    async mounted() {
        try {
            let res = await axios.get('api/products');
            this.products = await res.data.data;

            if(this.products.length > 0) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
            this.loading = false;
        }
    },
    methods: {
        isShow() {
            this.isError = !this.isError;
        }
    }
}
</script>

<style></style>


