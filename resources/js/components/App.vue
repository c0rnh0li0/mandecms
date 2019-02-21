<template>
    <v-app>
        <!-- <v-navigation-drawer app></v-navigation-drawer> -->
        <navbar></navbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer color="red darken-4" class="pa-3" app>
            <v-spacer></v-spacer>
            <span class="caption white--text"><strong>Cornholio</strong> &copy; {{ new Date().getFullYear() }}</span>
        </v-footer>
    </v-app>
</template>

<script>
    import Navbar from './inc/Navbar';

    export default {
        name: "App",
        components: {
            Navbar
        },
        data() {
            return {}
        },
        mounted() {
            this.getUser();
        },
        methods: {
            getUser() {
                if (this.$auth.isAuthenticated()) {
                    let that = this;
                    this.$auth.getUser()
                        .then(function (response) {
                            if (response.data.message && response.data.message == 'Unauthenticated.') {
                                console.log(response.data.message);
                            }
                            else {
                                that.$auth.setUserData(response);
                            }
                        })
                        .catch(function (error) {
                            console.log(error.message);
                        });
                }
            }
        }
    }
</script>

<style scoped>

</style>