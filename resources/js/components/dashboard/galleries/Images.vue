<template>
    <v-dialog v-model="dialogopen" fullscreen transition="dialog-bottom-transition">
        <v-card>
            <v-btn fixed
                   dark
                   fab
                   bottom
                   right
                   @click="openImageForm()"
                   color="red darken-4">
                <v-icon>add</v-icon>
            </v-btn>

            <image-upload :showForm="form" ref="imageUpload" :gallery="gallery_id" @closeForm="form = false" :image="editedImage" @updated="imageSaved" @saved="imageSaved"></image-upload>

            <v-toolbar dark color="amber accent-3">
                <v-toolbar-title>Images</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="closeImagesDialog">
                    <v-icon>close</v-icon>
                </v-btn>
            </v-toolbar>
            <v-container grid-list-sm fluid>
                <v-layout row wrap>
                    <v-flex v-if="galleryImages.length > 0" v-for="image in galleryImages" :key="image.id" xs6 sm4 md2 lg1 xl1 d-flex>
                        <v-hover>
                            <v-card tile slot-scope="{ hover }" :class="`elevation-${hover ? 12 : 2}`">
                                <v-img :src="'/storage/galleries/' + image.path"
                                       :lazy-src="'/storage/galleries/' + image.path"
                                       aspect-ratio="1"
                                       @click="editImage(image)"
                                       class="grey lighten-2">
                                    <template v-slot:placeholder>
                                        <v-layout fill-height align-center justify-center ma-0>
                                            <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                        </v-layout>
                                    </template>
                                </v-img>
                                <v-card-title primary-title>
                                    <div>
                                        <h3 class="headline ma-0">{{ image.title }}</h3>
                                    </div>
                                </v-card-title>
                            </v-card>
                        </v-hover>
                    </v-flex>
                    <v-card flat v-else>
                        <v-card-text>No images in this gallery</v-card-text>
                    </v-card>
                </v-layout>
            </v-container>
        </v-card>
    </v-dialog>
</template>
<script>
    import ImageUpload from './Image';

    export default {
        components: {
            ImageUpload
        },
        name: 'Images',
        props: {
            galleryImages: {type: Array, required: false, default: function () { return []; }},
            galleryId: {type: Number, required: false, default: function () { return ''; }},
            dialogopen: {type: Boolean, required: true, default: function () { return false; }},
        },
        watch: {
            galleryId(val) {
                this.editedImage.gallery_id = this.gallery_id = val;
                console.log('galleryId', val);
            }
        },
        data() {
            return {
                gallery_id: 0,
                gallerydialog: false,
                form: false,
                gallery: this.galleryObject,
                editedImage: {
                    id: '',
                    title: '',
                    description: '',
                    path: '',
                    gallery_id: ''
                },
                defaultImage: {
                    id: '',
                    title: '',
                    description: '',
                    path: '',
                    gallery_id: ''
                },
            }
        },
        mounted() {},
        methods: {
            openImageForm() {
                this.editedImage = this.defaultImage;
                this.editedImage.gallery_id = this.gallery_id;

                this.$refs.imageUpload.setData(this.editedImage);
                this.$refs.imageUpload.galleryObject = this.galleryObject;
                this.$refs.imageUpload.gallery_id = this.gallery_id;

                this.form = true;
            },
            editImage(img) {
                this.editedImage = img;
                this.$refs.imageUpload.setData(this.editedImage);
                this.$refs.imageUpload.galleryObject = this.galleryObject;
                this.$refs.imageUpload.gallery_id = this.gallery_id;

                this.form = true;
            },
            closeImagesDialog() {
                this.$emit('closeImageDialog');
            },
            imageSaved() {
                this.editedImage = this.defaultImage;
                this.form = false;
                this.$emit('updateImages', this.gallery_id);
            }
        }
    }
</script>