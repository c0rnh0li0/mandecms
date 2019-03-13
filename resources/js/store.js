import Vue from 'vue';
import Vuex from "vuex";

Vue.use(Vuex);

export default new Vuex.Store({
    state: {
        drawer: false,
        menuitems: [
            {
                text: 'Home',
                to: '/'
            },
        ]
    },
    getters: {
        menuitems(state) {
            return state.menuitems;
        },
    },
    mutations: {
        setDrawer: (state, payload) => (state.drawer = payload),
        toggleDrawer: state => (state.drawer = !state.drawer),
        loadMenus: (state, menus) => (state.menuitems = menus),
    },
    actions: {
        async loadMenus({ commit }) {
            let response = await axios.get('/api/menus/build');
            commit('loadMenus', response.data.data);
        },
    }
});