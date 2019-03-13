<template>
    <v-app>
        <navbar ref="nav" @navigated="openPage"></navbar>
        <v-content>
            <v-container fluid>
                <h1>{{ title }}</h1>
                <p><em>{{ intro }}</em></p>
                <p>{{ body }}</p>
                <router-view></router-view>
            </v-container>
        </v-content>
        <v-footer>
            <v-spacer></v-spacer>
            <span class="caption white--text"><strong>Cornholio</strong> &copy; {{ new Date().getFullYear() }}</span>
            <v-spacer></v-spacer>
        </v-footer>
    </v-app>
</template>

<script>
    import Navbar from './cms/inc/Navbar.vue';

    export default {
        name: "Page",
        components: {
            Navbar
        },
        data() {
            return {
                title: '',
                intro: '',
                body: '',
            }
        },
        mounted() {},
        created() {
            this.openPage(this.$router.history.current.path);
        },
        methods: {
            async openPage(slug) {
                let response = await axios.get('/api/cms/slug' + slug);

                if (typeof response.data.success != 'undefined' && response.data.success == false) {
                    if (response.data.code == 404) {
                        this.$router.push('/404');
                    }
                }
                else {
                    this.title = response.data.data.title;
                    this.intro = response.data.data.description;
                    this.body = response.data.data.body;
                }
            }
        }
    }
</script>

<style scoped>

</style>