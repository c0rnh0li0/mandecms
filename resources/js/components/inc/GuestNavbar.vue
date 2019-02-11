<template>
    <!-- Guest Navbar -->
    <nav class="mb-3 navbar navbar-expand-lg navbar-dark orange lighten-1">
        <a class="navbar-brand" href="/dashboard">{{ app_name }}</a>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
                aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav ml-auto nav-flex-icons">
                <!-- Authentication Links -->
                <li class="nav-item">
                    <a class="nav-link" :href="url_login">{{ txt_login }}</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" :href="url_register">{{ txt_register }}</a>
                </li>
            </ul>
        </div>
    </nav>
    <!--/.Guest Navbar -->
</template>

<script>
    export default {
        props: [
            'app_name',
            'txt_register',
            'txt_login',
            'url_register',
            'url_login',
        ],
        mounted() {
            console.log('guest navbar mounted');
        },
    }
</script>