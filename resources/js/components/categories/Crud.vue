<template>
    <div>
        <crud :crud-data="categories_crud"></crud>
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
                categories_crud: {
                    title: 'Categories',
                    singular: 'category',
                    plural: 'categories',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Title', align: 'left', sortable: true, value: 'title' },
                        { text: 'Description', align: 'left', sortable: false, value: 'description' },
                        { text: 'Slug', align: 'left', sortable: false, value: 'url' },
                        { text: 'Owner', align: 'left', sortable: false, value: 'owner_name' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/categories',

                    editedItem: {
                        id: '',
                        title: '',
                        description: '',
                        url: '',
                        owner: {},
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        title: '',
                        description: '',
                        url: '',
                        owner: {},
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