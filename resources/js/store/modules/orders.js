import Msg from "../../helpers/message";

export default {
    actions: {
        ORDERS_TODO: async (context) => {
            let data = null;
            try {
                const res = await axios('/api/orders');
                data = res.data.products;
            } catch (error) {
                context.commit('SET_ERRORS', error.message);
            }

            Msg.out_console('ORDERS_TODO', data);
            context.commit('CLEAR_ORDERS');
            context.commit('UP_ORDERS', data);
        },
        ORDER_TODO_ID: async ({ context, state }, id) => {
            for (let i = 0; i < state.orders.length; i++) {
                if (state.orders[i].id == id) {
                    state.loading = false;
                    let data = state.orders[i];

                    //--- сериализуем
                    let order = {};
                    order.id = data.id;
                    order.type = data.type;
                    order.status = data.status;
                    order.manager = data.user.name;
                    order.customer = data.customer;
                    order.phone = data.phone;
                    order.products = [];
                    for (let y = 0; y < data.order_items.length; y++) {
                        let product = {
                            name: data.order_items[y].product.name,
                            count: data.order_items[y].count,
                            discount: data.order_items[y].discount,
                        }
                        order.products.push(product);
                    }
                    //---
                    return order;
                }
            }
        },
        ORDER_SAVE: async ({ context, state }, order) => {
            try {
                let res = await axios.put('/api/orders/edit', order);
                const data = res.data;

                Msg.out_console('ORDER_SAVE', data);
            } catch (error) {
                context.commit('SET_ERRORS_ORDERS', error.message);
            }
        },
        ORDER_ADD: async ({ context, state }, order) => {
            let data = null;
            try {
                let res = await axios.post('/api/orders/add', order, {headers: {'Content-type':'application/json'}});
                data = res.data;

            } catch (error) {
                context.commit('SET_ERRORS_ORDERS', error.message);
            }

            Msg.out_console('ORDER_SAVE', data);
        }
    },
    mutations: {
        UP_ORDERS: (state, obj) => {
            for (let i in obj)
                state.orders.push(obj[i]);

            if (state.orders.length > 0)
                state.loading = false;

            //Msg.out_console('UP_NEWS', obj);
        },
        CLEAR_ORDERS: state => {
            state.loading = false;
            //state.goods = state.goods.splice(0, state.goods.length);       // очистка массива
            state.orders.splice(0, state.orders.length);       // очистка массива
        },
        SET_ERRORS_ORDERS: (state, message) => {
            state.errors = message;
            state.isError = true;
        },
        IS_SHOW_ORDERS: state => {
            state.isError = !state.isError;
        },
    },
    state: {
        loading: true,
        orders: [],
        errors: {},
        isError: false,
    },
    getters: {
        GET_ORDERS: state => {
            //console.log(state.orders);
            return state.orders;
        },
        GET_LOADING_ORDERS: state => {
            return state.loading;
        },
        GET_ERRORS_ORDERS: state => {
            return state.errors;
        },
        GET_IS_ERROR_ORDERS: state => {
            return state.isError;
        },
    }
}
