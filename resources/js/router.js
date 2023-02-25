import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import Products from './components/products/Products';
import ShowProduct from './components/products/ShowProduct';
import Orders from './components/orders/Orders';
import OrderEdit from './components/orders/OrderEdit';
import OrderCancelled from './components/orders/OrderCancelled';

const routes = [
    { path: '/products', name: 'products', component: Products },
    { path: '/products/:productId', name: 'showProduct', component: ShowProduct, props: true },
    { path: '/orders', name: 'orders', component: Orders },
    { path: 'orders/:orderId', name: 'orderEdit', component: OrderEdit, props: true },
    { path: 'orders/cancelled/:orderId', name: 'orderCancelled', component: OrderCancelled, props: true },
];

export default new VueRouter({
    mode: "history",
    routes: routes,
});


