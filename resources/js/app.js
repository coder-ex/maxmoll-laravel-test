require('./bootstrap');

import Vue from 'vue';
import VueRouter from 'vue-router';

Vue.use(VueRouter);

import App from './components/App';
import Products from './components/products/Products';
import ShowProduct from './components/products/ShowProduct';
import Orders from './components/orders/Orders';
import OrderEdit from './components/orders/OrderEdit';

const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/products',
            name: 'products',
            component: Products
        },
        {
            path: '/products/:productId',
            name: 'showProduct',
            component: ShowProduct,
            props: true
        },
        {
            path: '/orders',
            name: 'orders',
            component: Orders
        },
        {
            path: 'orders/:orderId',
            name: 'orderEdit',
            component: OrderEdit,
            props: true
        }
    ]
});

const app = new Vue({
    el: '#app',
    components: {App},
    router,
});
