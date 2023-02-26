<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header" style="text-align: center;">
                        <span> | </span>
                        <router-link :to="{ name: 'products' }">главная</router-link>
                        <span> | </span>
                        <router-link :to="{ name: 'tableOrders' }">таблица заказов</router-link>
                        <span> | </span>
                        <router-link :to="{ name: '' }">статистика</router-link>
                        <span> | </span>
                    </div>
                </div>
            </div>

            <div class="col-md-2">
                <div style="margin-top: 5pt;">
                    <router-link :to="{ name: 'orderCreate' }">
                        <button type="button" class="btn btn-sm btn-outline-secondary">Создать заказ</button>
                    </router-link>
                </div>
            </div>
        </div>

        <div class="row justify-content-center">
            <div class="col-md-9">
                <div class="card-body">
                    <router-view></router-view>
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

<style scoped>

</style>
