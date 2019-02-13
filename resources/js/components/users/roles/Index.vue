<template>
    <div>
        <!-- Styles -->
        <!-- <link href="{{ asset('css/mdb/addons/datatables.min.css') }}" rel="stylesheet"> -->

        <table id="roles_table" class="datatable table table-striped table-bordered" cellspacing="0" width="100%">
            <thead>
            <tr>
                <th>Role name</th>
                <th>Created on</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="role in roles" :key="role.id">
                    <td>{{ role.name }}</td>
                    <td>{{ role.created_at }}</td>
                </tr>
            </tbody>
            <!-- <tfoot>
            <tr>
                <th>Name</th>
                <th>Position</th>
                <th>Office</th>
                <th>Extn.</th>
                <th>Start date</th>
                <th>Salary</th>
            </tr>
            </tfoot> -->
        </table>

        <!-- <script src="{{ asset('js/mdb/addons/datatables.min.js') }}" defer></script> -->
    </div>
</template>

<script>
    export default {
        data() {
            return {
                roles: [],
            }
        },
        mounted() {
            this.getRoles();
        },
        created() {
            //this.getUsers();
        },
        methods: {
            getRoles(page_url) {
                let that = this;
                page_url = page_url || 'api/roles';

                axios.get('api/roles')
                    .then(function (response) {
                        that.roles = response.data.data;
                    })
                    .catch(function (error) {
                        console.log(error);
                    });
            }
        }
    }
</script>