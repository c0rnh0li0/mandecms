<template>
    <div>
        <h1 v-html="'Two columns (left content) -> ' + content.title"></h1>
        <p><em v-html="content.intro"></em></p>
        <p v-html="content.body"></p>
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