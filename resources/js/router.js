import VueRouter from 'vue-router';
import Vue from 'vue';

Vue.use(VueRouter);

import Products from './components/products/Products';
import ShowProduct from './components/products/ShowProduct';
import TableOrders from './components/orders/TableOrders';
import OrderEdit from './components/orders/OrderEdit';
import OrderRead from './components/orders/OrderRead';
import OrderCreate from './components/orders/OrderCreate';

import OrderCancelled from './components/orders/OrderCancelled';

const routes = [
    { path: '/products', name: 'products', component: Products },
    { path: '/products/:productId', name: 'showProduct', component: ShowProduct, props: true },
    { path: '/orders', name: 'tableOrders', component: TableOrders },
    { path: 'orders/edit/:orderId', name: 'orderEdit', component: OrderEdit, props: true },
    { path: 'orders/:orderId', name: 'orderRead', component: OrderRead, props: true },
    { path: 'orders/add', name: 'orderCreate', component: OrderCreate, props: true },

    { path: 'orders/cancelled/:orderId', name: 'orderCancelled', component: OrderCancelled, props: true },
];

export default new VueRouter({
    mode: "history",
    routes: routes,
});


