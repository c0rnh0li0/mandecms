<template>
    <div app>
        <h1>Landing -> {{ content.title }}</h1>
        <p><em>{{ content.intro }}</em></p>
        <p>{{ content.body }}</p>
    </div>
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