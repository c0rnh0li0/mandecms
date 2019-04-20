<template>
    <v-dialog v-model="dialog" fullscreen transition="dialog-bottom-transition">
        <template v-slot:activator="{ on }">
            <v-icon small v-on="on">collections</v-icon>
        </template>
        <v-card>
            <v-btn fixed
                   dark
                   fab
                   bottom
                   right
                   @click="openImageForm(defaultImage)"
                   color="red darken-4">
                <v-icon>add</v-icon>
            </v-btn>
            <image-upload :showForm="form" ref="imageUpload" :gallery="galleryId" @closeForm="form = false" :image="editedImage"></image-upload>

            <v-toolbar dark color="amber accent-3">
                <v-toolbar-title>Images</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn icon dark @click="dialog = false">
                    <v-icon>close</v-icon>
                </v-btn>
                <!-- <v-toolbar-items>
                    <v-btn dark flat @click="dialog = false">Save</v-btn>
                </v-toolbar-items> -->
            </v-toolbar>
            <v-container grid-list-sm fluid>
                <v-layout row wrap>
                    <v-flex v-if="galleryImages.length > 0" v-for="image in galleryImages" :key="image.id" xs6 sm4 md2 lg1 xl1 d-flex>
                        <v-card flat tile class="d-flex">
                            <v-img :src="`https://picsum.photos/500/300?image=${n * 5 + 10}`"
                                   :lazy-src="`https://picsum.photos/10/6?image=${n * 5 + 10}`"
                                   aspect-ratio="1"
                                   class="grey lighten-2">
                                <template v-slot:placeholder>
                                    <v-layout fill-height align-center justify-center ma-0>
                                        <v-progress-circular indeterminate color="grey lighten-5"></v-progress-circular>
                                    </v-layout>
                                </template>
                            </v-img>
                        </v-card>
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
        },
        watch: {
            galleryId(val) {
                console.log('galleryId', val)
            },
        },
        data() {
            return {
                dialog: false,
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
            openImageForm(imageData) {
                this.editedImage = imageData;
                this.$refs.imageUpload.setData(this.editedImage);
                this.$refs.imageUpload.galleryObject = this.galleryObject;

                this.form = true;
            }
        }
    }
</script>