<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header" style="text-align: center;">
                        <router-link :to="{ name: 'products' }">главная</router-link>
                        <span> | </span>
                        <router-link :to="{ name: 'orders' }">заказы</router-link>
                        <span> | </span>
                        <router-link :to="{ name: 'orderCancelled' }">отмена заказа</router-link>
                    </div>

                    <div class="card-body">
                        <router-view></router-view>
                    </div>
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
