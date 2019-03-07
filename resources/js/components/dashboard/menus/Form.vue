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
                                        v-model="editedItem.name"
                                        label="Name"
                                        color="red darken-4"
                                        :messages="errors.name"
                                        :error="typeof errors.name != 'undefined'">
                                </v-text-field>
                            </v-flex>
                            <v-flex grow pa-1>
                                <v-text-field
                                        v-model="editedItem.slug"
                                        label="URL"
                                        color="red darken-4"
                                        :messages="errors.slug"
                                        :error="typeof errors.slug != 'undefined'">
                                </v-text-field>
                            </v-flex>
                        </v-layout>
                    </v-container>

                    <v-container grid-list-md>
                        <v-layout row wrap>
                            <v-flex grow pa-1>
                                <v-combobox
                                        v-model="menuPage"
                                        :items="menupages"
                                        ref="page"
                                        :messages="errors.page_id"
                                        :error="typeof errors.page_id != 'undefined'"
                                        item-text="title"
                                        item-value="id"
                                        label="Page"
                                        color="red darken-4">
                                </v-combobox>
                            </v-flex>
                            <v-flex grow pa-1>
                                <v-combobox
                                        v-model="menuCategory"
                                        :items="menucategories"
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
                        <v-switch v-model="editedItem.visible">
                            <template v-slot:label>
                                Visible
                            </template>
                        </v-switch>
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

                menucategories: [],
                menupages: [],
                menuCategory: '',
                menuPage: '',

                errors: [],
            }
        },
        mounted() {
            //this.editorConfig = Array.from( this.editor.ui.componentFactory.names() );
            let that = this;

            this.getCategories()
                .then(function (response) {
                    that.menucategories = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message);
                });

            this.getPages()
                .then(function (response) {
                    that.menupages = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message);
                });
        },
        watch: {
            menuCategory(val) {
                if (val) {
                    this.editedItem.category_id = val.id;
                    this.editedItem.slug = val.url;
                    this.editedItem.name = val.title;

                    this.menuPage = '';
                    this.editedItem.page_id = '';
                    this.editedItem.page = {};
                }
            },
            menuPage(val) {
                if (val) {
                    this.editedItem.page_id = val.id;
                    this.editedItem.slug = val.url;
                    this.editedItem.name = val.title;

                    this.menuCategory = '';
                    this.editedItem.category_id = '';
                    this.editedItem.category = {};
                }
            },
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

            close() {
                this.$emit('close');
            },

            setData(policyData) {
                this.editedItem = policyData;
                this.errors = [];

                if (this.editedItem.page && this.editedItem.page.id) {
                    this.menuPage = this.editedItem.page;
                }

                if (this.editedItem.category && this.editedItem.category.id) {
                    this.menuCategory = this.editedItem.category;
                }
            },

            async getCategories() {
                return await axios.get(this.extras.categories_url);
            },

            async getPages() {
                return await axios.get(this.extras.pages_url);
            },
        }
    }
</script>