<template>
    <div>
        <crud :crud-data="policies_crud"></crud>
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
                policies_crud: {
                    title: 'Policies',
                    singular: 'policy',
                    plural: 'policies',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Name', align: 'left', sortable: true, value: 'name' },
                        { text: 'Description', align: 'left', sortable: false, value: 'description' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/policies',

                    editedItem: {
                        id: '',
                        name: '',
                        description: '',
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        name: '',
                        description: '',
                        created_at: ''
                    },
                    extras: {

                    },
                },
            }
        },
        methods: {}
    }
</script>