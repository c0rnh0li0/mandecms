<template>
    <div>
        <crud :crud-data="pages_crud"></crud>
    </div>
</template>

<script>
    import Crud from './../crud/Crud.vue';
    import Form from './Form.vue';
    import List from './List.vue';

    export default {
        components: {
            Crud,
        },
        data() {
            return {
                pages_crud: {
                    title: 'Pages',
                    singular: 'page',
                    plural: 'pages',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Title', align: 'left', sortable: true, value: 'title' },
                        { text: 'Template', align: 'left', sortable: false, value: 'template_name' },
                        { text: 'Owner', align: 'left', sortable: false, value: 'owner_name' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/pages',

                    editedItem: {
                        id: '',
                        title: '',
                        description: '',
                        body: '',
                        template: {},
                        owner: {},
                        url: '',
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        title: '',
                        description: '',
                        body: '',
                        template: {},
                        owner: {},
                        url: '',
                        created_at: ''
                    },
                    formMaxWidth: '900px',
                    extras: {
                        templates_url: '/api/templates/all',
                    },
                },
            }
        },
        methods: {}
    }
</script>