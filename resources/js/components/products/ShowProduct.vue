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
    name: "ShowProducts",
    props: [
        'productId'
    ],
    components: {
        Spin,
    },
    data: () => ({
        product: {
            id: null,
            name: "",
            price: null,
            stock: null
        },
    }),
    computed: { ...mapGetters(['GET_GOODS', 'GET_ERRORS', 'GET_IS_ERROR', 'GET_LOADING']) },
    mounted() {
        this.update();
    },
    methods: {
        ...mapActions(['GOODS_TODO', 'IS_SHOW_GOODS',]),
        update() {
            let data = this.GET_GOODS;
            //this.product = data.find((item)=> item.name==this.productId);
            for(let i=0;i< data.length;i++){
                if(data[i].id ==this.productId) {
                    this.product = data[i];
                    break;
                }
            }
        },
    }
}
</script>

<style></style>
