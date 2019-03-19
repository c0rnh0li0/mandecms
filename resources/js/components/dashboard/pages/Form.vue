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
                                <span v-if="typeof errors.hero_image != 'undefined'" color="red--text text--darken-4">{{ errors.hero_image }}</span>
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
                                        clearable
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
                                        clearable
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
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                <v-combobox
                                        v-model="page_meta"
                                        :items="metatags"
                                        label="Tags"
                                        chips
                                        clearable
                                        prepend-icon="local_offer"
                                        solo
                                        multiple>
                                    <template v-slot:selection="data">
                                        <v-chip :selected="data.selected"
                                                close
                                                @input="remove(data.item)">
                                            <v-avatar class="red darken-4 white--text">
                                                {{ data.item.slice(0, 1).toUpperCase() }}
                                            </v-avatar>
                                            <strong>{{ data.item }}</strong>&nbsp;
                                        </v-chip>
                                    </template>
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

                // meta
                metatags: [],
                page_meta: [],
            }
        },
        watch: {
            page_meta(val) {
                if (val) {
                    this.page_meta = val;
                    let that = this;
                    val.forEach(function (tag) {
                        if(that.metatags.indexOf(tag) === -1)
                            that.metatags.push(tag);
                    });
                }
            },
            pageTitle(val) {
                this.editedItem.title = val;

                this.editedItem.url = this.pageCategorySlug + this.slugify(val);
            },
            pageTemplate(val) {
                if (val) {
                    this.editedItem.template_id = val.id;
                }
                else {
                    this.editedItem.template_id = '';
                }
            },
            pageCategory(val) {
                if (val) {
                    this.editedItem.category_id = val.id;
                    this.pageCategorySlug = val.url;
                }
                else {
                    this.editedItem.category_id = '';
                    this.pageCategorySlug = '';
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
                    that.$emit('notified', err.message, 'error');
                });

            this.getCategories()
                .then(function (response) {
                    that.categories = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message, 'error');
                });

            axios.get('/api/tags')
                .then(function (response) {
                    response.data.forEach(function (t) {
                        that.metatags.push(t.name);
                    });
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (property == 'hero_image' && this.heroFile != null)
                        formData.append(property, this.heroFile, this.heroFile.name);
                    else if (property == 'page_metatags')
                        formData.append(property, this.page_meta.join(','));
                    else
                        formData.append(property, this.editedItem[property]);
                }

                if (!this.heroFile) {
                    formData.delete('hero_image');
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

            setData(pageData) {
                this.editedItem = {};
                this.editedItem = pageData;
                this.pageTitle = this.editedItem.title;

                if (this.editedItem.page_metatags && this.editedItem.page_metatags != '')
                    this.page_meta = this.editedItem.page_metatags.split(',');
                else
                    this.page_meta = [];

                if (this.editedItem.template.id)
                    this.pageTemplate = this.editedItem.template;
                else
                    this.pageTemplate = '';

                if (this.editedItem.category && this.editedItem.category.id)
                    this.pageCategory = this.editedItem.category;
                else
                    this.pageCategory = '';

                if (this.editedItem.hero_image)
                    this.heroUrl = '/storage/hero_images/' + this.editedItem.hero_image;
                else
                    this.heroUrl = null;

                this.errors = [];
            },

            async getTemplates() {
                return await axios.get(this.templatesUrl);
            },

            async getCategories() {
                return await axios.get(this.categoriesUrl);
            },

            remove (item) {
                this.page_meta.splice(this.page_meta.indexOf(item), 1);
                this.page_meta = [...this.page_meta];
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