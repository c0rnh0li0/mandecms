<template>
    <div>
        <crud :crud-data="galleries_crud" @extra="showImages"></crud>
        <gallery :dialogopen="gallerydialog" :gallery-images="gallery.images" :gallery-id="gallery.id" class="mr-2" @closeImageDialog="closeImageDialog" @updateImages="updateImages"></gallery>
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
                        showImages(item) {}
                    },
                },
                gallery: null,
                gallerydialog: false,
                gallery_update_url: '/api/images/all/'
            }
        },
        created() {
            this.gallery = this.galleries_crud.defaultItem;
        },
        methods: {
            showImages(item) {
                this.gallery = item;
                this.gallerydialog = true;
                // show images dialog here
            },
            closeImageDialog() {
                this.gallerydialog = false;
            },
            async updateImages(gallery_id) {
                let imgs = await axios.get(this.gallery_update_url + gallery_id);
                this.gallery.images = imgs.data.data;
            }
        }
    }
</script>