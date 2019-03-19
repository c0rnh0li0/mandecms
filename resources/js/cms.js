import './bootstrap';
import Vue from 'vue';
import Routes from './routes.js';
import Auth from './AuthFunctions.js';
import Page from './components/Page';
import Vuetify from 'vuetify';
import Vuex from 'vuex';
import store from './store';
//import Vuebar from 'vuebar';

Vue.component('passport-clients', require('./components/dashboard/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/dashboard/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/dashboard/passport/PersonalAccessTokens.vue').default);

Vue.use(Vuex);
Vue.use(Vuetify);
Vue.use(Auth);
//Vue.use(Vuebar);
// <div v-bar>

axios.defaults.headers.common['X-CSRF-TOKEN'] = Laravel.csrfToken;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();

Vue.component('Page', Page);

import 'vuetify/dist/vuetify.min.css';

const cms = new Vue({
    el: '#cms',
    store,
    router: Routes,
    render: h => h(Page),
    created(){
        this.$store.dispatch('loadSettings');
        this.$store.dispatch('loadMenus');
    }
});

export default cms;
