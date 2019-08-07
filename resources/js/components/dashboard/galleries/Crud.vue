<template>
    <div>
        <crud :crud-data="galleries_crud" @extra="showImages"></crud>
        <gallery :gallerydialog="gallerydialog" :gallery-images="gallery.images" :gallery-id="gallery.id" class="mr-2"></gallery>
    </div>
</template>

<script>
    import Crud from '../crud/Crud.vue';
    import Form from './Form.vue';
    import List from './List.vue';
    import Gallery from './Images';

    export default {
        components: {
            Crud,
            Gallery
        },
        data() {
            return {
                galleries_crud: {
                    title: 'Galleries',
                    singular: 'gallery',
                    plural: 'galleries',
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
                    crud_url: '/api/galleries',
                    dataDisplay: 'list',
                    editedItem: {
                        id: 0,
                        title: '',
                        description: '',
                        hero_image: '',
                        url: '',
                        owner: {},
                        created_at: '',
                        images: []
                    },
                    defaultItem: {
                        id: 0,
                        title: '',
                        description: '',
                        hero_image: '',
                        url: '',
                        owner: {},
                        created_at: '',
                        images: []
                    },
                    extras: {
                        showImages(item) {
                            console.log('will show images in galleries crud');
                        }
                    },
                },
                gallery: null,
                gallerydialog: false
            }
        },
        created() {
            this.gallery = this.galleries_crud.defaultItem;
        },
        methods: {
            onItemImages(item) {
                console.log('emitted to galleries crud');
                console.log(item);
            },
            showImages(item) {
                this.gallery = item;
                this.gallerydialog = true;
                // show images dialog here
            }
        }
    }
</script>