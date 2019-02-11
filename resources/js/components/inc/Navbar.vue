<template>
    <!--Navbar -->
    <nav class="mb-3 navbar navbar-expand-lg navbar-dark orange lighten-1">
        <router-link to="/" tag="a" class="navbar-brand" active-class="">MandeCMS</router-link>

        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent-555"
                aria-controls="navbarSupportedContent-555" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <router-link class="nav-link" to="/login" tag="a" active-class="active">Login</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/users" tag="a" active-class="active">Users</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/pages" tag="a" active-class="active">Pages</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/images" tag="a" active-class="active">Images</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/menu" tag="a" active-class="active">Menu</router-link>
                </li>
                <li class="nav-item">
                    <router-link class="nav-link" to="/templates" tag="a" active-class="active">Templates</router-link>
                </li>

                <!-- <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-555" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">Dropdown
                    </a>
                    <div class="dropdown-menu dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-555">
                        <a class="dropdown-item" href="#">Action</a>
                        <a class="dropdown-item" href="#">Another action</a>
                        <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                </li> -->
            </ul>

            <ul class="navbar-nav ml-auto nav-flex-icons">
                <!-- <li class="nav-item">
                    <a class="nav-link" href="/about">About</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link waves-effect waves-light">1
                        <i class="fas fa-envelope"></i>
                    </a>
                </li>

                <li class="nav-item avatar dropdown">
                    <a class="nav-link dropdown-toggle" id="navbarDropdownMenuLink-55" data-toggle="dropdown" aria-haspopup="true"
                       aria-expanded="false">
                        <img src="https://mdbootstrap.com/img/Photos/Avatars/avatar-2.jpg" class="rounded-circle z-depth-0" alt="avatar image">
                    </a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-secondary" aria-labelledby="navbarDropdownMenuLink-55">
                        <router-link to="/profile" tag="a" class="navbar-brand" active-class="">Profile</router-link>
                        <router-link to="/logout" tag="a" class="navbar-brand" active-class="" @click="logout">Logout</router-link>
                    </div>
                </li>
            </ul>
        </div>
    </nav>
    <!--/.Navbar -->
</template>

<script>
    import authService from '../../AuthFunctions';

    export default {
        mounted() {
            console.log('navbar mounted');
        },
        methods: {
            logout(e) {
                e.preventDefault();
                //document.getElementById('logout-form').submit();
                // make axios logout based with csrf string
            }
        }
    }
</script>