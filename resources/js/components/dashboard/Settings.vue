<template>
    <div>
        <v-snackbar
                v-model="snackbar"
                :bottom="false"
                :left="false"
                :multi-line="false"
                :right="false"
                :timeout="3000"
                :top="true"
                :vertical="false">
            <span :class="snackClass">{{ notification }}</span>
            <v-btn color="white"
                   flat
                   @click="snackbar = false">
                <v-icon small class="mr-0">close</v-icon>
            </v-btn>
        </v-snackbar>

        <v-card>
            <v-card-text>
                <v-toolbar flat color="white" class="mb-3">
                    <v-toolbar-title>Site settings</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-container grid-list-md text-xs-center>
                    <v-form method="POST" v-on:submit.prevent="saveSettings">
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-card class="elevation-1">
                                    <v-card-title>
                                        <h4>Site settings</h4>
                                    </v-card-title>
                                    <v-card-text class="px-0">
                                        <v-layout wrap>
                                            <v-container grid-list-md>
                                                <span class="red--text text--darken-4">Site logo <small>(Click to change)</small></span>
                                                <v-container grid-list-md>
                                                    <input type="file"
                                                           style="display: none"
                                                           name="site_logo"
                                                           ref="image"
                                                           accept="image/*"
                                                           @change="onFilePicked">

                                                    <v-spacer></v-spacer>
                                                    <img :src="imageUrl" height="150" v-if="imageUrl" @click.stop="pickFile"/>
                                                    <v-spacer></v-spacer>
                                                    <span v-if="typeof errors.site_logo != 'undefined'" color="red--text text--darken-4">{{ errors.site_logo[0] }}</span>
                                                </v-container>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.site_name"
                                                        label="Site name"
                                                        color="red darken-4"
                                                        :messages="errors.site_name"
                                                        :error="typeof errors.site_name != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-textarea
                                                        :value="editedItem.site_description"
                                                        label="Site description"
                                                        color="red darken-4"
                                                        :messages="errors.site_description"
                                                        :error="typeof errors.site_description != 'undefined'">
                                                </v-textarea>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        :value="editedItem.site_metatags"
                                                        label="Meta tags"
                                                        color="red darken-4"
                                                        :messages="errors.site_metatags"
                                                        :error="typeof errors.site_metatags != 'undefined'">
                                                </v-text-field>


                                                <!-- @keyup.native="processMetaTags" -->
                                                <v-combobox v-model="site_meta"
                                                            :filter="filter"
                                                            :hide-no-data="!search"
                                                            :items="metatags"
                                                            :search-input.sync="search"
                                                            hide-selected
                                                            label="Search for a tag"
                                                            multiple
                                                            small-chips
                                                            solo>
                                                    <template v-slot:no-data>
                                                        <v-list-tile>
                                                            <span class="subheading">Create</span>
                                                            <v-chip label
                                                                    small>
                                                                {{ search }}
                                                            </v-chip>
                                                        </v-list-tile>
                                                    </template>
                                                    <template v-slot:selection="{ item, parent, selected }">
                                                        <v-chip v-if="item === Object(item)"
                                                                :selected="selected"
                                                                label
                                                                small>
                                                            <span class="pr-2">
                                                              {{ item.name }}
                                                            </span>
                                                            <v-icon small @click="parent.selectItem(item)">close</v-icon>
                                                        </v-chip>
                                                    </template>
                                                    <template v-slot:item="{ index, item }">
                                                        <v-list-tile-content>
                                                            <v-text-field
                                                                    v-if="editing === item"
                                                                    v-model="editing.name"
                                                                    autofocus
                                                                    flat
                                                                    background-color="transparent"
                                                                    hide-details
                                                                    solo
                                                                    @keyup.enter="edit(index, item)">
                                                            </v-text-field>
                                                            <v-chip
                                                                    v-else
                                                                    dark
                                                                    label
                                                                    small>
                                                                {{ item.name }}
                                                            </v-chip>
                                                        </v-list-tile-content>
                                                        <v-spacer></v-spacer>
                                                        <v-list-tile-action @click.stop>
                                                            <v-btn icon @click.stop.prevent="edit(index, item)">
                                                                <v-icon>{{ editing !== item ? 'edit' : 'check' }}</v-icon>
                                                            </v-btn>
                                                        </v-list-tile-action>
                                                    </template>
                                                </v-combobox>
                                            </v-container>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                            <v-flex xs6>
                                <v-card class="elevation-1">
                                    <v-card-title>
                                        <h4>Contact &amp; Social media</h4>
                                    </v-card-title>
                                    <v-card-text class="px-0">
                                        <v-layout wrap>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.contact_email"
                                                        label="Contact e-mail"
                                                        color="red darken-4"
                                                        :messages="errors.contact_email"
                                                        :error="typeof errors.contact_email != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.facebook_url"
                                                        label="Facebook page/profile URL"
                                                        color="red darken-4"
                                                        :messages="errors.facebook_url"
                                                        :error="typeof errors.facebook_url != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.instagram_url"
                                                        label="Instagram profile/page URL"
                                                        color="red darken-4"
                                                        :messages="errors.instagram_url"
                                                        :error="typeof errors.instagram_url != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.twitter_url"
                                                        label="Twitter profile URL"
                                                        color="red darken-4"
                                                        :messages="errors.twitter_url"
                                                        :error="typeof errors.twitter_url != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                        </v-layout>
                                    </v-card-text>
                                </v-card>
                            </v-flex>
                        </v-layout>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-4" flat @click="saveSettings">Save settings</v-btn>
                        </v-card-actions>
                    </v-form>
                </v-container>
            </v-card-text>
        </v-card>
    </div>
</template>

<script>
    export default {
        name: "Settings",
        components: {},
        watch: {
            site_meta(val, prev) {
                if (val.length === prev.length) return;

                this.model = val.map(v => {
                    if (typeof v === 'string') {
                        v = {
                            name: v,
                        }

                        this.site_meta = this.addItemUnique(this.site_meta, v);
                        this.metatags = this.addItemUnique(this.metatags, v);

                        this.nonce++;
                    }

                    return v;
                })
            },
        },
        props: {
            settings: {type: Object, required: false, default: function(){ return {}; }},
        },
        data() {
            return {
                editedItem: {
                    id: '',
                    site_logo: '',
                    site_name: '',
                    site_metatags: '',
                    site_description: '',
                    facebook_url: '',
                    instagram_url: '',
                    twitter_url: '',
                    contact_email: '',
                },
                errors: [],

                // site logo section
                imageUrl: '/storage/img/logo.png',
                imageFile: '',

                snackClass: '',
                snackbar: false,
                notification: '',

                metatags: [
                    { header: 'Select a tag or create one' },
                ],
                site_meta: [],

                // tags chips section
                activator: null,
                attach: null,
                editing: null,
                index: -1,
                nonce: 1,
                menu: false,
                x: 0,
                search: null,
                y: 0
            }
        },
        mounted() {
            var that = this;
            axios.get('/api/settings')
                .then(function (response) {
                    that.settings = response.data.data;
                    that.imageUrl = '/storage/img/' + that.editedItem.site_logo;

                    that.editedItem = that.settings;
                    that.site_meta = that.settings.site_metatags;
                })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get('/api/tags')
                .then(function (response) {
                    that.metatags = response.data.data;
                })
                .catch(function (error) {
                    console.log(error);
                })
        },
        created() {},
        methods: {
            saveSettings() {
                this.editedItem.method = 'PUT';

                if (this.imageFile != null)
                    this.editedItem.site_logo = this.imageFile;
                else
                    this.editedItem.site_logo = null;

                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (this.editedItem.hasOwnProperty(property)) {
                        if (property == 'site_logo' && this.imageFile != null)
                            formData.append(property, this.imageFile, this.imageFile.name);
                        else
                            formData.append(property, this.editedItem[property]);
                    }
                }

                if (!this.imageFile) {
                    formData.delete('site_logo');
                }

                let that = this;
                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                formData.append('_method', 'put');

                axios.post(this.editedItem.id != '' ? '/api/settings/update/1' : '/api/settings/store', formData, requestOptions).then(function (response) {
                    Object.assign(that.editedItem, response.data.data);
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
            },
            remove_meta(item) {
                console.log('to remove', item);
            },

            edit (index, item) {
                if (!this.editing) {
                    this.editing = item
                    this.index = index
                } else {
                    this.editing = null
                    this.index = -1
                }
            },
            filter (item, queryText, itemText) {
                if (item.header) return false

                const hasValue = val => val != null ? val : ''

                const text = hasValue(itemText)
                const query = hasValue(queryText)

                return text.toString()
                    .toLowerCase()
                    .indexOf(query.toString().toLowerCase()) > -1
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
                        this.imageUrl = fr.result;
                        this.imageFile = files[0]; // this is an image file that can be sent to server...
                    });
                } else {
                    this.editedItem.site_logo = '';
                    this.imageFile = '';
                    this.imageUrl = '';
                }
            },
            notify(text, stype = 'success') {
                let sColor = 'red';
                if (stype == 'success')
                    sColor = 'green';
                if (stype == 'error')
                    sColor = 'red';
                if (stype == 'warning')
                    sColor = 'orange';

                this.snackClass = sColor + '--text lighten-4';

                this.notification = text;
                this.snackbar = true;
            },
            addItemUnique(arr, item) {
                arr.push(item);
                return [...new Set(arr)];
            }
        }
    }
</script>

<style scoped>

</style>