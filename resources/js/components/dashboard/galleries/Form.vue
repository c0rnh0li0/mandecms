<template>
    <div>
        <v-card-text>
            <v-form method="POST" v-on:submit.prevent="save">
                <v-layout wrap>
                    <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                <v-icon @click.stop="pickFile">attach_file</v-icon>
                                <v-label @click.stop="pickFile">Select Hero image (click)</v-label>

                                <input type="file"
                                       style="display: none;"
                                       name="hero_image"
                                       ref="image"
                                       accept="image/*"
                                       @change="onFilePicked">

                                <v-spacer></v-spacer>
                                <img :src="heroUrl" height="200" v-if="heroUrl" @click.stop="pickFile"/>
                                <v-spacer></v-spacer>
                                <span v-if="typeof errors.hero_image != 'undefined'" color="red--text text--darken-4">{{ errors.hero_image[0] }}</span>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                <v-text-field
                                        v-model="galleryTitle"
                                        label="Title"
                                        color="red darken-4"
                                        :messages="errors.title"
                                        :error="typeof errors.title != 'undefined'">
                                </v-text-field>
                            </v-flex>
                            <v-flex grow pa-1>
                                <v-text-field
                                        v-model="editedItem.url"
                                        label="URL"
                                        color="red darken-4"
                                        :messages="errors.url"
                                        :error="typeof errors.url != 'undefined'">
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
            <v-btn color="red darken-4" flat @click="close">Cancel</v-btn>
            <v-spacer></v-spacer>
            <v-btn color="red darken-4" flat @click="save">Save</v-btn>
        </v-card-actions>
    </div>
</template>

<script>
    export default {
        props: {
            itemToEdit: {type: Object, required: false, default: function(){ return {}; }},
            extras: {type: Object, required: false, default: {}},
            createUrl: {type: String, required: false, default: function() { return ''; }},
            updateUrl: {type: String, required: false, default: function() { return ''; }},
        },
        data() {
            return {
                editedItem: {},

                galleryTitle: '',

                errors: [],

                // hero images section
                heroUrl: '',
                heroFile: '',
            }
        },
        watch: {
            galleryTitle(val) {
                this.editedItem.title = val;

                this.editedItem.url = this.slugify(val);
            },
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (property == 'hero_image' && this.heroFile != null)
                        formData.append(property, this.heroFile, this.heroFile.name);
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

                    axios.post(this.updateUrl + this.editedItem.id, formData, requestOptions).then(function (response) {
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

                    axios.post(this.createUrl, formData, requestOptions).then(function (response) {
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

            pickFile () {
                this.$refs.image.click();
            },

            onFilePicked (e) {
                const files = e.target.files;
                if(files[0] !== undefined) {
                    const fr = new FileReader ();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.heroUrl = fr.result;
                        this.heroFile = files[0]; // this is an image file that can be sent to server...
                    });
                } else {
                    this.editedItem.hero_image = '';
                    this.heroFile = '';
                    this.heroUrl = '';
                }
            },

            close() {
                this.$emit('close');
            },

            setData(policyData) {
                this.editedItem = policyData;
                this.galleryTitle = this.editedItem.title;

                if (this.editedItem.hero_image)
                    this.heroUrl = '/storage/hero_images/' + this.editedItem.hero_image;
                else
                    this.heroUrl = null;

                this.errors = [];
            },

            slugify(string) {
                const a = 'àáäâãåăæçèéëêǵḧìíïîḿńǹñòóöôœṕŕßśșțùúüûǘẃẍÿź·/_,:;';
                const b = 'aaaaaaaaceeeeghiiiimnnnoooooprssstuuuuuwxyz------';
                const p = new RegExp(a.split('').join('|'), 'g');

                return '/' + string.toString().toLowerCase()
                    .replace(/\s+/g, '-') // Replace spaces with -
                    .replace(p, c => b.charAt(a.indexOf(c))) // Replace special characters
                    .replace(/&/g, '-and-') // Replace & with ‘and’
                    .replace(/[^\w\-]+/g, '') // Remove all non-word characters
                    .replace(/\-\-+/g, '-') // Replace multiple - with single -
                    .replace(/^-+/, '') // Trim - from start of text
                    .replace(/-+$/, ''); // Trim - from end of text
            },
        }
    }
</script>