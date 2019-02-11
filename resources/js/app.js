import './bootstrap';
import Vue from 'vue';
import Routes from './routes.js';
import Auth from './AuthFunctions';
import App from './components/App';

Vue.component('passport-clients', require('./components/passport/Clients.vue').default);
Vue.component('passport-authorized-clients', require('./components/passport/AuthorizedClients.vue').default);
Vue.component('passport-personal-access-tokens', require('./components/passport/PersonalAccessTokens.vue').default);

Vue.use(Auth);
Vue.component('App', App);

const app = new Vue({
    el: '#app',
    router: Routes,
    render: h => h(App),
        mounted() {
        //OAuth.getUser();
        console.log('should check session here');
    }
});

export default app;
