<template>
    <div>
        <!-- Styles -->
        <!-- <link href="{{ asset('css/mdb/addons/datatables.min.css') }}" rel="stylesheet"> -->

        <table id="users_table" class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="user in users" :key="user.id">
                    <td>{{ user.user_avatar }}</td>
                    <td>{{ user.name }}</td>
                    <td>{{ user.email }}</td>
                    <td>{{ user.role }}</td>
                    <td>{{ user.created_at }}</td>
                </tr>
            </tbody>
            <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
            </tfoot>
        </table>

        <!-- <script src="{{ asset('js/mdb/addons/datatables.min.js') }}" defer></script> -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                users: [],
            }
        },
        mounted() {
            this.getUsers();
        },
        created() {
            //this.getUsers();
        },
        methods: {
            getUsers(page_url) {
                let that = this;
                page_url = page_url || 'api/users';

                axios.get('api/users')
                    .then(function (response) {
                        that.users = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>