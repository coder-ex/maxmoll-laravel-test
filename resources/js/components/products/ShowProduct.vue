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

    </div>
</template>

<script>
export default {
    name: "ShowProducts",
    props: [
        'id'
    ],
    data() {
        return {
            //product: null,
            product: {
                id: 1,
                name: "xiaomi",
                price: 285,
                stock: 123
            },
            isError: false,
        }
    },
    async created() {
        try {
            let res = await axios.get('api/products/' + this.id);
            this.test = await res;

            console.log(this.test);

            //if (this.products.length > 0) this.loading = false;
        } catch (error) {
            console.log(error);
            this.isError = true;
        }
    }
}
</script>

<style></style>
