import './bootstrap';
import Vue from 'vue';
import Routes from './routes.js';
import Auth from './AuthFunctions.js';
import Page from './components/Page';
import Vuetify from 'vuetify';
import Vuex from 'vuex';

Vue.component('passport-clients', require('./components/dashboard/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/dashboard/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/dashboard/passport/PersonalAccessTokens.vue').default);

Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(Auth);

axios.defaults.headers.common['X-CSRF-TOKEN'] = Laravel.csrfToken;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();

Vue.component('Page', Page);

import 'vuetify/dist/vuetify.min.css';

const store = new Vuex.Store({
    state: {
        policies: []
    },
    getters: {
        hasAccess: (state) => (id) => {
            // const MANAGE_USERS = 2;
            // store.getters.hasAccess(MANAGE_USERS);
            return state.policies.find(policy => policy.id === id);
        }
    },
    mutations: {
        // store.commit('addPolicy', { /* policyobject */ })
        // in components: this.$store.commit('addPolicy')
        addPolicy (state, policy) {
            state.policies.push(policy);
        },
        addPolicies (state, policies) {
            state.policies = policies;
        },
    }
});

const cms = new Vue({
    el: '#cms',
    store,
    router: Routes,
    render: h => h(Page),
    mounted() {

    }
});

export default cms;
