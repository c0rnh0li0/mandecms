<template>
    <div>
        <v-card-text>
            <v-form method="POST" v-on:submit.prevent="save">
                <v-layout wrap>
                    <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                    <v-container grid-list-md>
                        <v-text-field
                                v-model="editedItem.name"
                                label="Name"
                                color="red darken-4"
                                :messages="errors.name"
                                :error="typeof errors.name != 'undefined'">
                        </v-text-field>
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

                    <v-container grid-list-md>

                            <v-layout row wrap>
                                <v-flex grow pa-1>
                                    <v-card-text class="px-0">
                                        <v-text-field
                                                readonly
                                                v-model="thumbName"
                                                label="Thumb"
                                                ref="thumbref"
                                                prepend-icon='attach_file'
                                                color="red darken-4"
                                                :messages="errors.thumb"
                                                @click.stop="pickThumb"
                                                :error="typeof errors.thumb != 'undefined'">
                                        </v-text-field>
                                        <input type="file"
                                               style="display: none"
                                               name="thumb"
                                               ref="thumb"
                                               accept="image/*"
                                               @change="onThumbPicked">
                                    </v-card-text>
                                </v-flex>
                                <v-flex shrink pa-1>
                                    <v-card-text class="px-0">
                                        <img :src="thumbUrl" height="50" v-if="thumbUrl" />
                                    </v-card-text>
                                </v-flex>
                            </v-layout>
                    </v-container>

                    <v-container grid-list-md>
                        <v-text-field
                                v-model="editedItem.file"
                                label="Template file"
                                prepend-icon='attach_file'
                                color="red darken-4"
                                :messages="errors.file"
                                :error="typeof errors.file != 'undefined'">
                        </v-text-field>
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

                errors: [],

                // avatar section
                thumbUrl: '/storage/template_thumbs/default_thumb.png',
                thumbFile: '',
                thumbName: ''
            }
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (this.editedItem.hasOwnProperty(property)) {
                        if (property == 'thumb' && this.thumbFile != null)
                            formData.append(property, this.thumbFile, this.thumbFile.name);
                        else
                            formData.append(property, this.editedItem[property]);
                    }
                }

                if (!this.thumbFile) {
                    formData.delete('thumb');
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

                        that.$emit('notified', response.data.message);
                    }).catch(function(err) {
                        if (err && err.response && err.response.status === 422) {
                            that.errors = err.response.data.errors || {};
                            that.errors.msg = err.response.data.message;
                        }
                        else {
                            that.$emit('notified', err.message);
                        }
                    });
                }
            },

            pickThumb () {
                this.$refs.thumb.click();
            },

            onThumbPicked (e) {
                const files = e.target.files;
                if(files[0] !== undefined) {
                    const fr = new FileReader ();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.thumbUrl = fr.result;
                        this.thumbFile = files[0]; // this is an image file that can be sent to server...
                        this.thumbName = this.thumbFile.name;
                    });
                } else {
                    this.thumbFile = '';
                    this.thumbUrl = '';
                }
            },

            close() {
                this.$emit('close');
            },

            setData(templateData) {
                this.editedItem = templateData;
                this.errors = [];
            }
        }
    }
</script>