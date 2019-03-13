<template>
    <div app>
        <component :is="template" :content="content"></component>
    </div>
</template>

<script>
    import Vue from 'vue';
    import Navbar from './cms/inc/Navbar.vue';
    import * as templates from '../templates'

    //import templates from '../templates.js';

    export default {
        name: "Index",
        watch: {
            template(val) {
                console.log('template', val);
            },
        },
        props: {
            content: {type: Object, required: false, default: function(){ return {}; }},
            template: {type: String, required: false, default: function(){ return 'landing'; }},
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