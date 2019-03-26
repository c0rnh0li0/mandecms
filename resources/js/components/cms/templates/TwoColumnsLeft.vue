<template>
    <v-content app>
        <v-parallax v-if="content.hero_image" height="300" :src="'/storage/hero_images/' + content.hero_image"></v-parallax>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                <v-flex d-flex glow xs12 sm8 md9 lg10>
                    <v-card>
                        <v-card-title primary class="title" v-html="content.title"></v-card-title>
                        <v-card-text>
                            <p><em v-html="content.intro"></em></p>
                            <p v-html="content.body"></p>
                        </v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex shrink xs12 sm4 md3 lg2>
                    <v-card flat>
                        <v-card-title primary class="title">Related</v-card-title>

                        <v-container grid-list-xl fluid>
                            <v-layout row wrap>
                                <v-flex v-for="page in content.related" :key="page.id" xs12 class="p-0 mb-2">
                                    <v-hover>
                                        <v-card tile slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`" :href="page.url">
                                            <v-img :src="`/storage/hero_images/${page.hero_image ? page.hero_image : 'hero_not_available.jpg'}`" height="200px"></v-img>
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
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</template>

<script>
    export default {
        name: "TwoColumnsLeft",
        watch: {},
        props: {
            content: {type: Object, required: false, default: function(){ return {}; }},
        },
        data() {
            return {
                lorem: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec sapien lorem, rhoncus eget gravida sed, tempus in odio. Mauris venenatis ex ac enim molestie, dapibus rutrum dui suscipit. Etiam commodo ornare dictum. Vivamus suscipit nunc nec lacus dignissim, id euismod tellus pretium. Aliquam nisi lectus, lacinia ut dignissim sit amet, iaculis vitae augue. Aliquam varius dolor vitae tortor pharetra, sit amet finibus orci hendrerit. Aenean sollicitudin, velit sed molestie efficitur, nunc justo volutpat leo, ut sodales sem erat eu magna. Donec id feugiat eros. Ut eget mattis sapien, sit amet euismod urna. Ut vehicula molestie ex ut tristique. Donec consequat malesuada purus, nec tempor justo facilisis non. In dapibus egestas convallis. Nulla vitae ipsum quis ligula sagittis sodales.'
            }
        },
        mounted() {},
        created() {},
        methods: {}
    }
</script>

<style scoped>

</style>