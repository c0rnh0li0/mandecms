<template>
    <div>
        This is the page
    </div>
</template>

<script>
    export default {
        name: "Page",
        data() {
            return {

            }
        },
        mounted() {
            console.log('page data should be loaded here [mounted]');
        },
        created() {
            console.log('page data should be loaded here [created]');
        },
        methods: {}
    }
</script>

<style scoped>

</style>