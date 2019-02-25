export default function (Vue) {
    Vue.auth = {
        settings() {
            return {
                xheaders: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                loginUrl: '/api/auth/login',
                signupUrl: '/api/auth/signup',
                logoutUrl: '/api/auth/logout',
                userUrl: '/api/auth/user',
            }
        },
        user: {},
        login(loginData) {
            return axios.post(this.settings().loginUrl, loginData);
        },

        logout() {
            let authData = this.getData();
            if (!authData) {
                console.log('no auth data preserved');
                return;
            }

            return axios({
                method: 'get',
                url: this.settings().logoutUrl,
                /*headers: {
                    'Authorization': this.createBearer(authData)
                }*/
            });
        },

        register(signupData) {
            console.log('registration data', signupData)
            return axios.post(this.settings().signupUrl, signupData);
        },

        async getUser() {
            let authData = this.getData();
            if (!authData) {
                console.log('no auth data preserved');
                this.isAuthed = false;
                return;
            }

            let that = this;

            return await axios({
                method: 'get',
                url: this.settings().userUrl
            });
        },

        setData(authData) {
            //authData.access_token
            //authData.expires_at
            //authData.token_type

            localStorage.setItem('user_data', JSON.stringify(authData));
        },

        getData() {
            return JSON.parse(localStorage.getItem('user_data'));
        },

        destroyData() {
            localStorage.removeItem('user_data');
            this.user = [];
        },

        createBearer(authData) {
            return authData.token_type + ' ' + authData.access_token;
        },

        setUserData(userData) {
            this.user.id = userData.data.id;
            this.user.name = userData.data.name;
            this.user.email = userData.data.email;
            this.user.created_at = userData.data.created_at;
            this.user.user_avatar = userData.data.user_avatar;
            this.user.role = userData.data.role;

            //this.$store.state.user = this.user;
        },

        getToken() {
            var userData = this.getData();

            if (!userData) {
                return null;
            }

            var token = userData.access_token;
            var expiration = new Date(userData.expires_at);

            if (!token || !expiration) {
                return null
            }
            if (Date.now() > parseInt(expiration)) {
                this.destroyData();
                return null;
            } else {
                return token;
            }
        },

        isAuthenticated(){
            if (this.getToken()) {
                return true
            } else {
                return false;
            }
        },
    }

    Object.defineProperties(Vue.prototype, {
        $auth: {
            get(){
                return Vue.auth
            }
        },
    })
}