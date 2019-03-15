<template>
    <v-content>
        <template app>
            <v-parallax height="300" src="https://cdn.vuetifyjs.com/images/parallax/material.jpg">
                <v-layout align-center column justify-center>
                    <h1 class="display-2 font-weight-thin mb-3" v-html="'Landing -> ' + content.title"></h1>
                    <h4 class="subheading" v-html="content.intro"></h4>
                </v-layout>
            </v-parallax>
        </template>
        <v-container fluid>
            <!-- <h1 v-html="'Landing -> ' + content.title"></h1>
            <p><em v-html="content.intro"></em></p> -->
            <p v-html="content.body"></p>
        </v-container>
    </v-content>
</template>

<script>
    export default {
        name: "Landing",
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