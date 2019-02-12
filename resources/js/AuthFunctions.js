export default function (Vue) {
    let user = {};
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
                user: {
                    id: '',
                    name: '',
                    email: '',
                    created_at: ''
                }
            }
        },

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

        signup({signupData}) {
            axios({
                method: 'post',
                url: this.settings().signupUrl,
                data: signupData,
                headers: this.settings().xheaders
            })
                .then(function (response) {
                    if (response.success == true) {
                        //this.$router.go('/login');
                        console.log(response.message);
                    }
                    else {
                        console.log(response.message);
                    }
                    //this.setData(response);

                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },

        getUser() {
            let authData = this.getData();
            if (!authData) {
                console.log('no auth data preserved');
                this.isAuthed = false;
                return;
            }

            axios({
                method: 'get',
                url: this.settings().userUrl,
                headers: {
                    'Authorization': this.createBearer(authData)
                }
            })
                .then(function (response) {
                    this.setUserData(response);

                    console.log(response.access_token);
                })
                .catch(function (error) {
                    console.log(error);
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
        },

        createBearer(authData) {
            return authData.token_type + ' ' + authData.access_token;
        },

        setUserData({userData}) {
            user.id = userData.id;
            user.name = userData.name;
            user.email = userData.email;
            user.created_at = userData.created_at;
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
                return token
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
        }
    })
}