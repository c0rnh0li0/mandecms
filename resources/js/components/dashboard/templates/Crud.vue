<template>
    <div>
        <crud :crud-data="templates_crud"></crud>
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
                templates_crud: {
                    title: 'Templates',
                    singular: 'template',
                    plural: 'templates',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Name', align: 'left', sortable: true, value: 'name' },
                        { text: 'Description', align: 'left', sortable: false, value: 'description' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/templates',

                    editedItem: {
                        id: '',
                        name: '',
                        description: '',
                        thumb: '',
                        file: '',
                        sections: [],
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        name: '',
                        description: '',
                        thumb: '',
                        file: '',
                        sections: [],
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