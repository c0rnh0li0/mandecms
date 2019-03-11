<template>
    <div>
        <crud :crud-data="roles_crud"></crud>
    </div>
</template>

<script>
    import Crud from '../crud/Crud.vue';
    import Form from './Form.vue';
    import List from './List.vue';

    export default {
        components: {
            Crud,
        },
        data() {
            return {
                roles_crud: {
                    title: 'Roles',
                    singular: 'role',
                    plural: 'roles',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Name', align: 'left', sortable: true, value: 'name' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/roles',
                    dataDisplay: 'list',
                    editedItem: {
                        id: '',
                        name: '',
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        name: '',
                        created_at: ''
                    },
                    extras: {
                        policies_url: '/api/policies/all'
                    },
                },
            }
        },
        methods: {}
    }
</script>