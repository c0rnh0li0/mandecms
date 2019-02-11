<template>
    <div id="app">
        <navbar></navbar>
        <main class="container">
            <router-view></router-view>
        </main>
    </div>
</template>

<script>
    import Navbar from './inc/Navbar';

    export default {
        name: "App",
        components: {
            Navbar,
        },
        mounted() {
            console.log("main page app mounted");
        }
    }
</script>

<style scoped>

</style>