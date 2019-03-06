<template>
    <div>
        <v-card-text>
            <v-form method="POST" v-on:submit.prevent="save">
                <v-layout wrap>
                    <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                <v-text-field
                                        label="Select Hero image"
                                        v-model='heroUrl'
                                        prepend-icon='attach_file'
                                        color="red darken-4"
                                        :messages="errors.hero_image"
                                        :error="typeof errors.hero_image != 'undefined'"
                                        @click.stop="pickFile"></v-text-field>

                                <input type="file"
                                       style="display: none"
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
                                            v-model="pageTitle"
                                            label="Title"
                                            color="red darken-4"
                                            :messages="errors.title"
                                            :error="typeof errors.title != 'undefined'">
                                    </v-text-field>
                            </v-flex>
                            <v-flex grow pa-1>
                                <v-combobox
                                        v-model="pageCategory"
                                        :items="categories"
                                        ref="category"
                                        :messages="errors.category_id"
                                        :error="typeof errors.category_id != 'undefined'"
                                        item-text="title"
                                        item-value="id"
                                        label="Category"
                                        color="red darken-4">
                                </v-combobox>

                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                    <v-text-field
                                            v-model="editedItem.url"
                                            label="URL"
                                            color="red darken-4"
                                            :messages="errors.url"
                                            :error="typeof errors.url != 'undefined'">
                                    </v-text-field>
                            </v-flex>
                            <v-flex grow pa-1>
                                <v-combobox
                                        v-model="pageTemplate"
                                        :items="templates"
                                        ref="template"
                                        :messages="errors.template"
                                        :error="typeof errors.template != 'undefined'"
                                        item-text="name"
                                        item-value="id"
                                        label="Template"
                                        color="red darken-4">
                                </v-combobox>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container grid-list-md>
                        <v-flex grow pa-1>
                            <v-label>Description</v-label>
                            <ckeditor :editor="editor"
                                      v-model="editedItem.description"
                                      color="red darken-4">
                            </ckeditor>
                        </v-flex>
                    </v-container>

                    <v-container grid-list-md>
                        <v-flex grow pa-1>
                            <v-label>Body</v-label>
                            <ckeditor :editor="editor"
                                      v-model="editedItem.body"
                                      color="red darken-4">
                            </ckeditor>
                        </v-flex>
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
    import CKEditor from '@ckeditor/ckeditor5-vue';
    import ClassicEditor from '@ckeditor/ckeditor5-build-classic';

    export default {
        components: {
            ckeditor: CKEditor.component
        },
        props: {
            itemToEdit: {type: Object, required: false, default: function(){ return {}; }},
            extras: {type: Object, required: false, default: {}},
            createUrl: {type: String, required: false, default: function() { return ''; }},
            updateUrl: {type: String, required: false, default: function() { return ''; }},
        },
        data() {
            return {
                editor: ClassicEditor,
                editorConfig: {
                    toolbar: {}
                },

                editedItem: {},
                pageTitle: '',

                pageTemplate: '',
                pageCategory: '',
                pageCategorySlug: '',

                templatesUrl: this.extras.templates_url,
                templates: [],

                categoriesUrl: this.extras.categories_url,
                categories: [],

                // hero images section
                heroUrl: '',
                heroFile: '',

                errors: [],
            }
        },
        watch: {
            pageTitle(val) {
                this.editedItem.title = val;

                this.editedItem.url = this.pageCategorySlug + this.slugify(val);
            },
            pageTemplate(val) {
                if (val) {
                    this.editedItem.template_id = val.id;
                }
            },
            pageCategory(val) {
                if (val) {
                    this.editedItem.category_id = val.id;
                    this.pageCategorySlug = val.url;
                }

                this.editedItem.url = this.pageCategorySlug + this.slugify(this.pageTitle);
            },

        },
        mounted() {
            //this.editorConfig = Array.from( this.editor.ui.componentFactory.names() );
            let that = this;

            this.getTemplates()
                .then(function (response) {
                    that.templates = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message);
                });

            this.getCategories()
                .then(function (response) {
                    that.categories = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message);
                });
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
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

            setData(pageData) {
                this.editedItem = pageData;
                this.pageTitle = this.editedItem.title;

                if (this.editedItem.template.id) {
                    this.pageTemplate = this.editedItem.template;
                }

                if (this.editedItem.category && this.editedItem.category.id) {
                    this.pageCategory = this.editedItem.category;
                }

                if (this.editedItem.hero_image) {
                    this.heroUrl = '/storage/hero_images/' + this.editedItem.hero_image;
                }

                this.errors = [];
            },

            async getTemplates() {
                return await axios.get(this.templatesUrl);
            },

            async getCategories() {
                return await axios.get(this.categoriesUrl);
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