<template>
    <v-app>
        <!-- <v-navigation-drawer app></v-navigation-drawer> -->
        <navbar></navbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer color="red darken-4" app>
            <v-spacer></v-spacer>
            <span class="caption white--text"><strong>Cornholio</strong> &copy; {{ new Date().getFullYear() }}</span>
            <v-spacer></v-spacer>
        </v-footer>
    </v-app>
</template>

<script>
    import Navbar from './inc/Navbar';

    export default {
        name: "App",
        components: {
            Navbar,
        },
    }
</script>

<style scoped>

</style>