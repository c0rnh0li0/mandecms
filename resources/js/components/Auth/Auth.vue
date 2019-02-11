<template>
    <div>
        <passport-clients></passport-clients>
        <passport-authorized-clients></passport-authorized-clients>
        <passport-personal-access-tokens></passport-personal-access-tokens>
    </div>
</template>

<script>
    export default {
        name: 'Auth',
        data() {
            return {
                xheaders: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                loginUrl: '/api/auth/login',
                signupUrl: '/api/auth/signup',
                logoutUrl: '/api/auth/logout',
                userUrl: '/api/auth/user',
                isAuthed: false,
                user: {
                    id: '',
                    name: '',
                    email: '',
                    created_at: ''
                }
            }
        },
        mounted() {
            /*console.log('oauth lib mounted');

            let authData = this.getData();

            if (!authData) {
                this.isAuthed = false;
            }
            else {
                this.getUser();
                this.isAuthed = true;
            }*/
        },
        created() {
            console.log('oauth lib created');

            let authData = this.getData();

            if (!authData) {
                this.isAuthed = false;
            }
            else {
                this.getUser();
                this.isAuthed = true;
            }

            console.log(this.isAuthed, this.user);
        },
        methods: {
            login(loginData) {
                axios({
                    method: 'post',
                    url: this.loginUrl,
                    data: loginData,
                    headers: this.xheaders
                })
                    .then(function (response) {
                        this.setData(response);
                        this.isAuthed = true;
                        //this.$router.go('/');

                        console.log(response.access_token);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            logout() {
                let authData = this.getData();
                if (!authData) {
                    console.log('no auth data preserved');
                    return;
                }

                axios({
                    method: 'get',
                    url: this.logoutUrl,
                    headers: {
                        'Authorization': this.createBearer(authData)
                    }
                })
                    .then(function (response) {
                        //this.setData(response);
                        //this.$router.go('/login');

                        console.log(response.access_token);
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            signup(signupData) {
                axios({
                    method: 'post',
                    url: this.signupUrl,
                    data: signupData,
                    headers: this.xheaders
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
                    url: this.userUrl,
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
                if (localStorage.getItem('user_data')) {
                    return JSON.parse(localStorage.getItem('user_data'));
                }

                return null;
            },
            createBearer(authData) {
                return authData.token_type + ' ' + authData.access_token;
            },
            setUserData(userData) {
                user.id = userData.id;
                user.name = userData.name;
                user.email = userData.email;
                user.created_at = userData.created_at;
            }
        }
    }
</script>