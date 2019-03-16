<template>
    <v-content app>
        <template>
            <v-parallax height="300" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                <v-layout align-center column justify-center>
                    <h1 class="display-2 font-weight-thin mb-3" v-html="'Category template -> ' + content.title"></h1>
                    <h4 class="subheading" v-html="content.intro"></h4>
                </v-layout>
            </v-parallax>
        </template>
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