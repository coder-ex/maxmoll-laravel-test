<template>
    <div class="container">
        <div class="form-group row">
            <label for="staticStock" class="col-sm-3 col-form-label">ID</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticStock" v-model="product.id">
            </div>
            <label for="staticStock" class="col-sm-3 col-form-label">наименование</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticStock" v-model="product.name">
            </div>
            <label for="staticStock" class="col-sm-3 col-form-label">цена</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticStock" v-model="product.price">
            </div>
            <label for="staticStock" class="col-sm-3 col-form-label">количество</label>
            <div class="col-sm-9">
                <input type="text" readonly class="form-control-plaintext" id="staticStock" v-model="product.stock">
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
    name: "ShowProducts",
    props: [
        'productId'
    ],
    components: {
        Spin,
    },
    data() {
        return {
            product: {
                id: null,
                name: "",
                price: null,
                stock: null
            },
            loading: true,
            isError: false,
        }
    },
    async mounted() {
        try {
            let res = await axios.get('http://school.loc/api/products/' + this.productId);
            this.product = await res.data.data;

            if (this.product !== undefined) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
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
