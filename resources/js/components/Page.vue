<template>
    <v-app>
        <navbar ref="nav"></navbar>
        <v-content>
            This is the page
        </v-content>
        <v-footer></v-footer>
    </v-app>

</template>

<script>
    import Navbar from './cms/inc/Navbar.vue';
    import VContent from "vuetify/src/components/VGrid/VContent";

    export default {
        name: "Page",
        components: {
            VContent,
            Navbar
        },
        data() {
            return {

            }
        },
        mounted() {
            console.log('page data should be loaded here [mounted]');
        },
        created() {
            console.log('page data should be loaded here [created]');
        },
        methods: {}
    }
</script>

<style scoped>

</style>