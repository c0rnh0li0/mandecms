<template>
    <v-app>
        <!-- <v-navigation-drawer app></v-navigation-drawer> -->
        <navbar></navbar>
        <v-content>
            <v-container fluid>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer app></v-footer>
    </v-app>
    <!-- <div id="app">
        <navbar></navbar>
        <main class="container">

            <router-view></router-view>
        </main>
    </div> -->
</template>

<script>
    import Navbar from './inc/Navbar';

    export default {
        name: "App",
        components: {
            Navbar,
        },
        mounted() {
            console.log("main page app mounted");
        }
    }
</script>

<style scoped>

</style>