<template>
    <v-dialog v-model="showForm" persistent max-width="500px">
        <v-card>
            <v-card-title>
                <h2>Image</h2>
            </v-card-title>
            <v-card-text>
                <v-form method="POST" v-on:submit.prevent="save">
                    <v-layout wrap>
                        <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                        <v-input type="hidden" name="gallery_id" v-model="gallery"></v-input>
                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex grow pa-1 class="text-xs-center">
                                    <v-icon @click.stop="pickFile">attach_file</v-icon>
                                    <v-label @click.stop="pickFile">Select image (click)</v-label>

                                    <input type="file"
                                           style="display: none;"
                                           name="path"
                                           ref="galimage"
                                           accept="image/*"
                                           @change="onFilePicked">

                                    <v-spacer></v-spacer>
                                    <img :src="imageUrl" height="200" v-if="imageUrl" @click.stop="pickFile"/>
                                    <v-spacer></v-spacer>
                                    <span v-if="typeof errors.path != 'undefined'" color="red--text text--darken-4">{{ errors.path[0] }}</span>
                                </v-flex>
                            </v-layout>
                        </v-container>

                        <v-container grid-list-md>
                            <v-layout row wrap>
                                <v-flex grow pa-1>
                                    <v-text-field
                                            v-model="editedItem.title"
                                            label="Title"
                                            color="red darken-4"
                                            :messages="errors.title"
                                            :error="typeof errors.title != 'undefined'">
                                    </v-text-field>
                                </v-flex>
                            </v-layout>
                        </v-container>

                        <v-container grid-list-md>
                            <v-textarea
                                    solo
                                    v-model="editedItem.description"
                                    name="description"
                                    color="red darken-4"
                                    label="Description"
                                    value="editedItem.description"
                                    :messages="errors.description"
                                    :error="typeof errors.description != 'undefined'">
                            </v-textarea>
                        </v-container>
                    </v-layout>
                </v-form>
            </v-card-text>
            <v-card-actions>
                <v-btn color="red darken-4" flat @click="closeForm">Cancel</v-btn>
                <v-spacer></v-spacer>
                <v-btn color="red darken-4" flat @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        props: {
            image: {type: Object, required: false, default: function(){ return {}; }},
            gallery: {type: Number, required: false, default: function(){ return ''; }},
            showForm: {type: Boolean, required: false, default: function(){ return false; }},
        },
        watch: {
            gallery(val) {
                console.log('gallery in image form', gallery);
            }
        },
        data() {
            return {
                editedItem: {
                    id: '',
                    title: '',
                    description: '',
                    path: '',
                    gallery_id: ''
                },

                errors: [],

                // image section
                imageUrl: '',
                imageFile: '',
            }
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (property == 'path' && this.imageFile != null)
                        formData.append(property, this.imageFile, this.imageFile.name);
                    else
                        formData.append(property, this.editedItem[property]);
                }

                let that = this;
                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                if (this.editedItem.id != '') {
                    formData.append('_method', 'put');

                    axios.post('/api/images/update/' + this.editedItem.id, formData, requestOptions).then(function (response) {
                        //Object.assign(that.records[that.editedIndex], response.data.data);
                        that.$emit('updated', response.data.data);
                    }).catch(function(err) {
                        if (err && err.response && err.response.status === 422) {
                            that.errors = err.response.data.errors || {};
                            that.errors.msg = err.response.data.message;
                        }
                    });
                } else {
                    this.editedItem.method = 'POST';

                    axios.post('/api/images/store', formData, requestOptions).then(function (response) {
                        if (response.data.success == true) {
                            that.$emit('saved');
                        }

                        that.$emit('notified', response.data.message, response.data.success ? 'success' : 'error');
                    }).catch(function(err) {
                        if (err && err.response && err.response.status === 422) {
                            that.errors = err.response.data.errors || {};
                            that.errors.msg = err.response.data.message;
                        }
                        else {
                            that.$emit('notified', err.message, 'error');
                        }
                    });
                }
            },

            closeForm() {
                this.$emit('closeForm');
            },

            pickFile () {
                this.$refs.galimage.click();
            },

            onFilePicked (e) {
                const files = e.target.files;
                if(files[0] !== undefined) {
                    const fr = new FileReader ();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.imageUrl = fr.result;
                        this.imageFile = files[0]; // this is an image file that can be sent to server...
                    });
                } else {
                    this.editedItem.path = '';
                    this.imageFile = '';
                    this.imageUrl = '';
                }
            },

            setData(imageData) {
                this.editedItem = imageData;

                if (this.editedItem.path)
                    this.imageUrl = '/storage/galleries/' + this.editedItem.path;
                else
                    this.imageUrl = null;

                this.errors = [];
            },
        }
    }
</script>