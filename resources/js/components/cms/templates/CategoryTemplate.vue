<template>
    <v-content app>
        <v-parallax v-if="content.hero_image" height="300" :src="'/storage/hero_images/' + content.hero_image">
            <v-layout align-center column justify-center>
                <h1 class="display-2 font-weight-thin mb-3" v-html="content.title"></h1>
                <h4 class="subheading" v-html="content.intro"></h4>
            </v-layout>
        </v-parallax>
        <v-layout mb-3>
            <v-flex xs12>
                <v-container grid-list-xl fluid fill-height>
                    <v-layout row wrap align-center justify-center>
                        <v-flex v-for="page in content.pages" :key="page.id" xs6 sm4 md3 lg2 xl2>
                            <v-hover>
                                <v-card tile slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" :href="page.url">
                                    <v-img :src="`https://unsplash.it/150/300?image=${page.id + 1}`" height="200px"></v-img>
                                    <v-card-title primary-title>
                                        <div>
                                            <h3 class="headline ma-0">{{ page.title }}</h3>
                                            <div v-html="page.description.substr(0, 50) + '...'"></div>
                                        </div>
                                    </v-card-title>
                                </v-card>
                            </v-hover>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-flex>
        </v-layout>
    </v-content>
</template>

<script>
    export default {
        name: "CategoryTemplate",
        watch: {},
        props: {
            content: {type: Object, required: false, default: function(){ return {}; }},
        },
        data() {
            return {}
        },
        mounted() {},
        created() {},
        methods: {}
    }
</script>

<style scoped>

</style>