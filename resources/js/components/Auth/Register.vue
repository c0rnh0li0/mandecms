<template>
    <v-dialog width="500" color="red darken-4" v-model="dialog">
        <span slot="activator">
            Register
        </span>

        <v-card>
            <v-toolbar color="red darken-4">
                <v-btn icon @click="dialog = false;">
                    <v-icon color="white">close</v-icon>
                </v-btn>
                <v-toolbar-title class="white--text">Create account</v-toolbar-title>
            </v-toolbar>
            <v-form method="POST" v-on:submit.prevent="register">
                <v-card-text>
                        <v-container grid-list-md>
                            <v-text-field color="red darken-4" label="Your name" name="name" v-model="fields.name" :messages="errors.name"></v-text-field>
                        </v-container>

                        <v-container grid-list-md>
                            <v-text-field color="red darken-4" label="Your email" name="email" v-model="fields.email" :messages="errors.email"></v-text-field>
                        </v-container>

                        <v-container grid-list-md>
                            <v-text-field color="red darken-4" label="Your password" type="password" name="password" v-model="fields.password" :messages="errors.password"></v-text-field>
                        </v-container>

                        <v-container grid-list-md>
                            <v-text-field color="red darken-4" label="Confirm your password" type="password" name="password_confirmation" v-model="fields.password_confirmation" :messages="errors.password_confirmation"></v-text-field>
                        </v-container>
                </v-card-text>

                <v-divider></v-divider>

                <v-card-actions>
                    <v-spacer></v-spacer>
                    <v-btn block color="red darken-4" class="white--text" @click="register">
                        Sign up
                    </v-btn>
                </v-card-actions>
            </v-form>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        name: "Register",
        data() {
            return {
                dialog: false,
                fields: {
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    '_token': window.Laravel.csrfToken
                },
                errors: {}
            }
        },
        methods: {
            register(e) {
                this.errors = {};
                let that = this;

                this.fields._token = window.Laravel.csrfToken;

                this.$auth.register(this.fields).then(function (response) {
                    if (response.data.success == true) {
                        this.dialog = false;
                        that.$router.go({ name: 'Login' });
                    }
                }).catch(function (err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                    }
                });
            }
        }
    }
</script>