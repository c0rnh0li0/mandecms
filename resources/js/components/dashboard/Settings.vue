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
                <v-toolbar flat color="white">
                    <v-toolbar-title>Site settings</v-toolbar-title>
                </v-toolbar>
                <v-container grid-list-md text-xs-center>
                    <v-form method="POST" v-on:submit.prevent="saveSettings">
                        <v-layout row wrap>
                            <v-flex xs6>
                                <v-card class="elevation-1">
                                    <v-card-title>
                                        <h4>Global settings</h4>
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
                                                        v-model="editedItem.site_description"
                                                        label="Site description"
                                                        color="red darken-4"
                                                        :messages="errors.site_description"
                                                        :error="typeof errors.site_description != 'undefined'">
                                                </v-textarea>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-combobox
                                                        v-model="site_meta"
                                                        :items="metatags"
                                                        label="Site metatags"
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
            site_meta(val) {
                if (val) {
                    this.site_meta = val;
                    let that = this;
                    val.forEach(function (tag) {
                        if(that.metatags.indexOf(tag) === -1)
                            that.metatags.push(tag);
                    });
                }
            }
        },
        props: {},
        data() {
            return {
                settings: {
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
                imageUrl: '/storage/img/default_logo.png',
                imageFile: '',

                snackClass: '',
                snackbar: false,
                notification: '',

                metatags: [],
                site_meta: [],
            }
        },
        mounted() {
            var that = this;
            axios.get('/api/settings')
                .then(function (response) {
                    if (!_.isEmpty(response.data)) {
                        that.settings = response.data.data;
                        that.editedItem = that.settings;

                        that.site_meta = that.settings.site_metatags.split(',');
                        that.imageUrl = '/storage/img/' + that.editedItem.site_logo;
                    }
                })
                .catch(function (error) {
                    console.log(error);
                });

            axios.get('/api/tags')
                .then(function (response) {
                    response.data.forEach(function (t) {
                        that.metatags.push(t.name);
                    });
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
                        else if (property == 'site_metatags')
                            formData.append(property, this.site_meta.join(','));
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

                formData.append('metatags', this.metatags.join(','));
                formData.append('_method', this.editedItem.id != '' ? 'put' : 'post');

                axios.post(this.editedItem.id != '' ? '/api/settings/update/1' : '/api/settings/store', formData, requestOptions).then(function (response) {
                    Object.assign(that.editedItem, response.data.data);
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
            },
            remove (item) {
                this.site_meta.splice(this.site_meta.indexOf(item), 1);
                this.site_meta = [...this.site_meta];
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
        }
    }
</script>

<style scoped>

</style>