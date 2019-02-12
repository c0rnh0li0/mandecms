<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Login</div>

                    <div class="card-body">
                        <form method="POST" v-on:submit.prevent="login">
                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">Email</label>

                                <div class="col-md-6">
                                    <input id="email" type="email" class="form-control" name="email" v-model="fields.email" value="" required autofocus>
                                    <div v-if="errors && errors.email" class="text-danger">{{ errors.email[0] }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">Password</label>

                                <div class="col-md-6">
                                    <input id="password" type="password" class="form-control" v-model="fields.password" name="password" required>
                                    <div v-if="errors && errors.password" class="text-danger">{{ errors.password[0] }}</div>
                                </div>
                            </div>

                            <div class="form-group row">
                                <div class="col-md-6 offset-md-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" v-model="fields.remember_me">

                                        <label class="form-check-label" for="remember">Remember me?</label>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-8 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Login
                                    </button>

                                    <a class="btn btn-link" href="/forgotpassword">
                                        Forgot your password?
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                fields: {
                    email: '',
                    password: '',
                    remember_me: false,
                    '_token': window.Laravel.csrfToken
                },
                errors: {}
            }
        },
        methods: {
            login(e) {
                this.errors = {};
                let that = this;

                this.fields._token = window.Laravel.csrfToken;

                this.$auth.login(this.fields).then(function (response) {
                    console.log('after login', response);
                    that.$auth.setData(response.data);
                    that.$router.go({ name: 'Home' });

                }).catch(function (err) {
                    console.log('after login error', err);
                    if (err && err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors || {};
                    }
                });
            }
        }
    }
</script>