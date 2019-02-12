import './bootstrap';
import Vue from 'vue';
import Routes from './routes.js';
import Auth from './AuthFunctions.js';
import App from './components/App';

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

Vue.use(Auth);

axios.defaults.headers.common['X-CSRF-TOKEN'] = Laravel.csrfToken;
axios.defaults.headers.common['Content-Type'] = 'application/json';
axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
axios.defaults.headers.common['Authorization'] = 'Bearer ' + Vue.auth.getToken();

Vue.component('App', App);

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
    mounted() {
        console.log('should check session here');
    }
});

export default app;
