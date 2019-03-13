<template>
    <div>
        <h1>Two columns (left content) -> {{ content.title }}</h1>
        <p><em>{{ content.intro }}</em></p>
        <p>{{ content.body }}</p>
    </div>
</template>

<script>
    export default {
        name: "TwoColumnsLeft",
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