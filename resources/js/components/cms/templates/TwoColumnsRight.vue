<template>
    <v-content app>
        <v-container fluid grid-list-md>
            <v-layout row wrap>
                <v-flex d-flex shrink xs12 sm4 md3 lg2>
                    <v-card flat>
                        <v-card-text>4 {{ lorem.slice(0, 100) }}</v-card-text>
                    </v-card>
                </v-flex>
                <v-flex d-flex glow xs12 sm8 md9 lg10>
                    <v-card>
                        <v-card-title primary class="title" v-html="'Two columns (left content) -> ' + content.title"></v-card-title>
                        <v-card-text>
                            <p><em v-html="content.intro"></em></p>
                            <p v-html="content.body"></p>
                        </v-card-text>
                    </v-card>
                </v-flex>
            </v-layout>
        </v-container>
    </v-content>
</template>

<script>
    export default {
        name: "TwoColumnsRight",
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