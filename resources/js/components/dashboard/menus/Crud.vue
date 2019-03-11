<template>
    <div>
        <crud :crud-data="menus_crud"></crud>
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
                menus_crud: {
                    title: 'Menus',
                    singular: 'menu item',
                    plural: 'menu items',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: 'Name', align: 'left', sortable: true, value: 'name' },
                        { text: 'Slug', align: 'left', sortable: false, value: 'slug' },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/menus',

                    dataDisplay: 'sortable',

                    editedItem: {
                        id: '',
                        parent_id: '',
                        parent: {},
                        name: '',
                        order: '',
                        slug: '',
                        visible: true,

                        page_id: '',
                        page: {},

                        category_id: '',
                        category: {},

                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        parent_id: '',
                        name: '',
                        order: '',
                        slug: '',
                        visible: true,

                        page_id: '',
                        page: {},

                        category_id: '',
                        category: '',
                        created_at: ''
                    },
                    extras: {
                        pages_url: '/api/pages/all',
                        categories_url: '/api/categories/all',
                    },

                    onMounted() {

                    }
                },
            }
        },
        methods: {}
    }

    /*
    //https://github.com/SortableJS/Vue.Draggable
    */
</script>