<template>
    <div id="home">
        <div class="card card-image mb-3" style="background-image: url(https://mdbootstrap.com/img/Photos/Others/forest2.jpg);">
            <div class="text-white text-center rgba-stylish-strong py-5 px-4">
                <div class="py-5">

                    <!-- Content -->
                    <h5 class="h5 orange-text">Hi {{ user.name }}</h5>
                    <h2 class="card-title h2 my-4 py-2">Welcome to MandeCMS</h2>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                user: {},
            }
        },
        mounted() {
            this.isLoggedIn = this.$auth.isAuthenticated();

            if (this.isLoggedIn) {
                let that = this;
                this.$auth.getUser()
                    .then(function (response) {
                        if (response.data.message && response.data.message == 'Unauthenticated.') {
                            console.log(response.data.message);
                        }
                        else {
                            that.$auth.setUserData(response);
                            that.user = that.$auth.user;
                        }
                    })
                    .catch(function (error) {
                        console.log(error.message);
                    });
            }
        }
    }
</script>