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
                <tr></tr>
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
            console.log('users mounted');
        },
        created() {
            this.getUsers();
        },
        methods: {
            getUsers(page_url) {
                let vm = this;
                page_url = page_url || 'api/users';

            }
        }
    }
</script>