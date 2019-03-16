<template>
    <v-app>
        <navbar :progress-loading="loading" ref="nav" @navigated="openPage" app></navbar>
        <index app v-if="!is404" :content="content" :template="template"></index>
        <router-view v-if="is404"></router-view>
        <v-footer dark height="auto">
            <v-card flat tile class="indigo lighten-1 white--text text-xs-center">
                <v-card-text>
                    <v-btn v-if="$store.getters.settings.contact_email"
                           class="mx-3 white--text" icon>
                        <v-icon class="footer-icon" size="24px">fas fa-envelope</v-icon>
                    </v-btn>
                    <v-btn v-if="$store.getters.settings.facebook_url"
                           class="mx-3 white--text" icon>
                        <v-icon class="footer-icon" size="24px">fab fa-facebook</v-icon>
                    </v-btn>
                    <v-btn v-if="$store.getters.settings.instagram_url"
                           class="mx-3 white--text" icon>
                        <v-icon class="footer-icon" size="24px">fab fa-instagram</v-icon>
                    </v-btn>
                    <v-btn v-if="$store.getters.settings.twitter_url"
                           class="mx-3 white--text" icon>
                        <v-icon class="footer-icon" size="24px">fab fa-twitter</v-icon>
                    </v-btn>
                </v-card-text>

                <v-card-text class="white--text pt-0">
                    Phasellus feugiat arcu sapien, et iaculis ipsum elementum sit amet. Mauris cursus commodo interdum. Praesent ut risus eget metus luctus accumsan id ultrices nunc. Sed at orci sed massa consectetur dignissim a sit amet dui. Duis commodo vitae velit et faucibus. Morbi vehicula lacinia malesuada. Nulla placerat augue vel ipsum ultrices, cursus iaculis dui sollicitudin. Vestibulum eu ipsum vel diam elementum tempor vel ut orci. Orci varius natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus.
                </v-card-text>

                <v-divider></v-divider>

                <v-card-text class="white--text">
                    &copy;2018 — <strong>Cornholio</strong>
                </v-card-text>
            </v-card>
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
            Index,
        },
        data() {
            return {
                loading: false,
                content: {
                    title: '',
                    intro: '',
                    body: '',
                },
                template: '',
                is_page: true,
                is_category: false,
                is404: false,

                icons: [
                    'fab fa-facebook',
                    'fab fa-twitter',
                    'fab fa-google-plus',
                    'fab fa-linkedin',
                    'fab fa-instagram'
                ],
            }
        },
        mounted() {},
        created() {
            this.openPage(this.$router.history.current.path);
        },
        methods: {
            async openPage(slug) {
                this.loading = true;
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

                this.loading = false;
            }
        }
    }
</script>

<style scoped>

</style>