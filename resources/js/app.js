import VueRouter from 'vue-router';
require('./bootstrap');

window.Vue = require('vue').default;

Vue.use(VueRouter);

import App from './components/App';
import router from './router';
import store from './store';

const app = new Vue({
    el: '#app',
    components: {App},
    router,
    store,
});
