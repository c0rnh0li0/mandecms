<template>
    <v-dialog width="500" color="red darken-4" v-model="dialog">
        <span slot="activator">
            Login
        </span>

        <v-card>
            <v-toolbar color="red darken-4">
                <v-btn icon @click="dialog = false">
                    <v-icon color="white">close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text">Log in</v-toolbar-title>
            </v-toolbar>
            <v-form method="POST" v-on:submit.prevent="login">
                <v-card-text>
                    <v-container grid-list-md>
                        <v-text-field color="red darken-4" label="Your email" name="email" v-model="fields.email" :messages="errors.email"></v-text-field>
                    </v-container>

                    <v-container grid-list-md>
                        <v-text-field color="red darken-4" label="Your password" type="password" name="password" v-model="fields.password" :messages="errors.password"></v-text-field>
                    </v-container>
                </v-card-text>
                <v-divider></v-divider>

                <v-card-actions>
                    <v-btn block flat color="red darken-4" @click="forgotpassword">
                        Forgot password?
                    </v-btn>
                    <v-spacer></v-spacer>
                    <v-btn block color="red darken-4" class="white--text" @click="login">
                        Sign in
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Login",
        data() {
            return {
                dialog: false,
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
                    that.dialog = false;
                    that.$auth.setData(response.data);
                    that.$router.go('/dashboard');

                }).catch(function (err) {
                    if (err && err.response && err.response.status === 422) {
                        this.errors = err.response.data.errors || {};
                    }
                });
            },
            forgotpassword() {

            }
        }
    }
</script>