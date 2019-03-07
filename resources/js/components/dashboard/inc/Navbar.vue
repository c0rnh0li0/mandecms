<template>
    <v-toolbar app color="red darken-4" dark clipped-right dense>
        <v-toolbar-side-icon class="hidden-md-and-up" @click="drawer = !drawer"></v-toolbar-side-icon>
        <v-toolbar-title>
            <router-link tag="div" to="/dashboard">
                <span>MandeCMS</span>
            </router-link>
        </v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items v-if="isLoggedIn">
            <v-menu>
                <v-btn flat slot="activator">
                    <span>People</span>
                    <v-icon dark>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                    <router-link tag="v-list-tile" to="/dashboard/people">
                        <v-list-tile-title>People</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/people/roles">
                        <v-list-tile-title>Roles</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/people/access-policies">
                        <v-list-tile-title>Policies</v-list-tile-title>
                    </router-link>
                </v-list>
            </v-menu>

            <v-menu>
                <v-btn flat slot="activator">
                    <span>CMS</span>
                    <v-icon dark>arrow_drop_down</v-icon>
                </v-btn>
                <v-list>
                    <router-link tag="v-list-tile" to="/dashboard/cms/pages">
                        <v-list-tile-title>Pages</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/cms/categories">
                        <v-list-tile-title>Categories</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/cms/templates">
                        <v-list-tile-title>Templates</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/cms/tags">
                        <v-list-tile-title>Tags</v-list-tile-title>
                    </router-link>
                </v-list>
            </v-menu>

            <v-btn flat small @click="navigate('/dashboard/images')">Images</v-btn>
            <v-btn flat small @click="navigate('/dashboard/menus')">Menu</v-btn>

            <v-menu>
                <v-btn flat small slot="activator">
                    <v-avatar :tile="false" :size="36">
                        <img v-bind:src="'/storage/user_avatars/' + userAvatar" />
                    </v-avatar>
                    <span class="white--text">&nbsp;&nbsp;Hi {{ userName }}</span>
                </v-btn>
                <v-list>
                    <router-link tag="v-list-tile" to="/dashboard/profile">
                        <v-list-tile-title>Profile</v-list-tile-title>
                    </router-link>
                    <router-link tag="v-list-tile" to="/dashboard/profile/tokens">
                        <v-list-tile-title>Tokens</v-list-tile-title>
                    </router-link>
                    <v-list-tile @click="logout">
                        <v-list-tile-title>Logout</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
        </v-toolbar-items>
        <v-toolbar-items class="hidden-sm-and-down" v-else>
            <v-btn flat small><register /></v-btn>
            <v-btn flat small><login /></v-btn>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
    import Register from '../Auth/Register.vue';
    import Login from '../Auth/Login.vue';

    export default {
        props: [
            'loggedUser'
        ],
        data() {
            return {
                isLoggedIn: false,
                userAvatar: 'default_avatar.png',
                userName: ''
            }
        },
        components: {
            Register,
            Login
        },
        created() {},
        mounted() {
            this.isLoggedIn = this.$auth.isAuthenticated();

            if (this.isLoggedIn) {
                let that = this;
                this.$auth.getUser()
                    .then(function (response) {
                        if (response.data.message && response.data.message == 'Unauthenticated.') {
                            console.log(response.data.message);
                        }
                        else {
                            that.$auth.setUserData(response);
                            that.userAvatar = that.$auth.user.user_avatar;
                            that.userName = that.$auth.user.name.split(' ')[0];
                        }
                    })
                    .catch(function (error) {
                        console.log(error.message);
                    });
            }
        },
        methods: {
            logout(e) {
                let that = this;
                e.preventDefault();
                this.$auth.logout()
                    .then(function (response) {

                    if (response.data.success == true) {
                        that.isLoggedIn = false;
                        that.$auth.destroyData();
                        that.$router.go('');
                    }
                })
                    .catch(function (error) {
                        console.log(error);
                    });
            },
            navigate(path) {
                this.$router.push(path);
            }
        }
    }
</script>