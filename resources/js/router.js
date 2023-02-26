import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import Products from './components/products/Products';
import ShowProduct from './components/products/ShowProduct';
import TableOrders from './components/orders/TableOrders';
import OrderCard from './components/orders/OrderCard';
import OrderCreate from './components/orders/OrderCreate';

import OrderCancelled from './components/orders/OrderCancelled';

const routes = [
    { path: '/products', name: 'products', component: Products },
    { path: '/products/:productId', name: 'showProduct', component: ShowProduct, props: true },
    { path: '/orders', name: 'tableOrders', component: TableOrders },
    { path: 'orders/edit/:orderId', name: 'orderCard', component: OrderCard, props: true },
    { path: 'orders/:orderId', name: 'orderCard', component: OrderCard, props: true },
    { path: 'orders/add', name: 'orderCreate', component: OrderCreate },

    { path: 'orders/cancelled/:orderId', name: 'orderCancelled', component: OrderCancelled, props: true },
];

export default new VueRouter({
    mode: "history",
    routes: routes,
});


