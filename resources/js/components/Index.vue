<template>
    <div app>
        <component :is="template" :content="content"></component>
    </div>
</template>

<script>
    import CategoryTemplate from './cms/templates/CategoryTemplate';
    import Landing from './cms/templates/Landing';
    import TwoColumnsLeft from './cms/templates/TwoColumnsLeft';
    import TwoColumnsRight from './cms/templates/TwoColumnsRight';

    export default {
        name: "Index",
        components: {
            CategoryTemplate,
            Landing,
            TwoColumnsLeft,
            TwoColumnsRight,
        },
        watch: {},
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