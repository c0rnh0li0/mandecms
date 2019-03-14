<template>
    <v-app>
        <navbar ref="nav" @navigated="openPage"></navbar>
        <v-content>
            <v-container fluid>
                <index v-if="!is404" :content="content" :template="template"></index>
                <router-view v-if="is404"></router-view>
            </v-container>
        </v-content>
        <v-footer>
            <v-spacer></v-spacer>
            <span class="caption red--text"><strong>Cornholio</strong> &copy; {{ new Date().getFullYear() }}</span>
            <v-spacer></v-spacer>
        </v-footer>
    </v-app>
</template>

<script>
    import Navbar from './cms/inc/Navbar.vue';
    import Index from './Index.vue';

    export default {
        name: "Page",
        components: {
            Navbar,
            Index
        },
        data() {
            return {
                content: {
                    title: '',
                    intro: '',
                    body: '',
                },
                template: '',
                is_page: true,
                is_category: false,
                is404: false,
            }
        },
        mounted() {
            console.log('page mounted');
        },
        created() {
            this.openPage(this.$router.history.current.path);
        },
        methods: {
            async openPage(slug) {
                let response = await axios.get('/api/cms/slug' + slug);

                if (typeof response.data.success != 'undefined' && response.data.success == false) {
                    if (response.data.code == 404) {
                        this.is404 = true;
                        this.$router.push('/404');
                    }
                }
                else {
                    this.content.title = response.data.data.title;
                    this.content.intro = response.data.data.description;
                    this.content.body = response.data.data.body;
                    this.is_page = response.data.data.is_page;
                    this.is_category = response.data.data.is_category;

                    this.template = this.is_page ? response.data.data.template.file : 'category-template';
                    this.is404 = false;
                }
            }
        }
    }
</script>

<style scoped>

</style>