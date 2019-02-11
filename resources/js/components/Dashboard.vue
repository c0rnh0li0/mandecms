<template>
    <!-- Jumbotron -->
    <div class="container">
        <div class="card card-image" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/forest2.jpg);">
            <div class="text-white text-center rgba-stylish-strong py-5 px-4">
                <div class="py-5">
                    <!-- Content -->
                    <h5 class="h5 orange-text">Hi {{ user_name }}</h5>
                    <h2 class="card-title h2 my-4 py-2">Welcome to MandeCMS</h2>
                    <p class="mb-4 pb-2 px-md-5 mx-md-5">Create new articles, galeries, pages, bio and more</p>
                    <div class="card-body">
                        You are logged in!
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user_name: '',
                user_id: {}
            }
        },
        mounted() {
            console.log('jumbotron mounted');
        },
        created() {
            this.getUserData();
        },
        methods: {
            getUserData() {
                var that = this;
                axios.get('/api/users/info')
                    .then(function (response) {
                        that.user_name = response.data.name;
                        that.user_id = response.data.id;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
            }
        }
    }
</script>