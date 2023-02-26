import Msg from "../../helpers/message";

export default {
    actions: {
        GOODS_TODO: async (context) => {
            let data = null;
            try {
                const res = await axios('/api/products');
                data = res.data.data;
            } catch (error) {
                context.commit('SET_ERRORS', error.message);
            }

            Msg.out_console('GOODS_TODO', data);
            context.commit('CLEAR_GOODS');
            context.commit('UP_GOODS', data);
        },
    },
    mutations: {
        UP_GOODS: (state, obj) => {
            for (let i in obj)
                state.goods.push(obj[i]);

            if (state.goods.length > 0)
                state.loading = false;

            //Msg.out_console('UP_NEWS', obj);
        },
        CLEAR_GOODS: state => {
            state.loading = false;
            //state.goods = state.goods.splice(0, state.goods.length);       // очистка массива
            state.goods.splice(0, state.goods.length);       // очистка массива
        },
        SET_ERRORS: (state, message) => {
            state.errors = message;
            state.isError = true;
        },
        IS_SHOW_GOODS: state=>{
            state.isError = !state.isError;
        }
    },
    state: {
        loading: true,
        goods: [],
        errors: {},
        isError: false,
    },
    getters: {
        GET_GOODS: state => {
            //console.log(state.goods);
            return state.goods;
        },
        GET_LOADING: state => {
            return state.loading;
        },
        GET_ERRORS: state => {
            return state.errors;
        },
        GET_IS_ERROR: state => {
            return state.isError;
        },
    }
}
