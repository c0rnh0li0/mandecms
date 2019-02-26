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
                        <v-text-field
                                v-model="editedItem.email"
                                label="Email"
                                color="red darken-4"
                                :messages="errors.email"
                                :error="typeof errors.email != 'undefined'">
                        </v-text-field>
                    </v-container>
                    <v-container grid-list-md>
                        <v-combobox
                                v-model="edited_role"
                                :items="user_roles"
                                :messages="errors.user_role"
                                :error="typeof errors.user_role != 'undefined'"
                                item-text="text"
                                item-value="id"
                                label="Role"
                                color="red darken-4">
                        </v-combobox>
                    </v-container>
                    <v-container grid-list-md>
                        <span>Avatar</span>
                        <span class="red--text text--darken-4"><small>(Click to change)</small></span>
                        <!-- <v-text-field label="Select Avatar"
                                      v-model='editedItem.user_avatar'
                                      prepend-icon='attach_file'
                                      color="red darken-4"
                                      :messages="errors.user_avatar"
                                      :error="typeof errors.user_avatar != 'undefined'"></v-text-field> -->
                        <input type="file"
                               style="display: none"
                               name="user_avatar"
                               ref="image"
                               accept="image/*"
                               @change="onFilePicked">
                        <v-spacer></v-spacer>
                        <v-avatar :tile="false" :size="150">
                            <img :src="imageUrl" height="150" v-if="imageUrl" @click.stop="pickFile"/>
                        </v-avatar>
                        <v-spacer></v-spacer>
                        <span v-if="typeof errors.user_avatar != 'undefined'" color="red--text text--darken-4">{{ errors.user_avatar }}</span>
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

                role_select: '',
                user_roles: [],
                roles_model: [],
                edited_role: {
                    id: 2,
                    text: 'Contributor'
                },

                // avatar section
                imageUrl: '/storage/user_avatars/default_avatar.png',
                imageFile: '',
            }
        },
        mounted() {
            this.getRoles();
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                this.editedItem.role_id = this.edited_role.id;
                this.editedItem.user_role = this.edited_role.text;

                if (this.imageFile != null)
                    this.editedItem.user_avatar = this.imageFile;
                else
                    this.editedItem.user_avatar = null;

                let formData = new FormData();

                for (var property in this.editedItem) {
                    if (this.editedItem.hasOwnProperty(property)) {
                        if (property == 'user_avatar' && this.imageFile != null)
                            formData.append(property, this.imageFile, this.imageFile.name);
                        else
                            formData.append(property, this.editedItem[property]);
                    }
                }

                if (!this.imageFile) {
                    formData.delete('user_avatar');
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
                    //this.editedItem.user_avatar = files[0].name;
                    /*if(this.editedItem.user_avatar.lastIndexOf('.') <= 0) {
                        return;
                    }*/
                    const fr = new FileReader ();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.imageUrl = fr.result;
                        this.imageFile = files[0]; // this is an image file that can be sent to server...
                    });
                } else {
                    this.editedItem.user_avatar = '';
                    this.imageFile = '';
                    this.imageUrl = '';
                }
            },

            getRoles() {
                let that = this;
                axios.get('/api/roles')
                    .then(function (response) {
                        response.data.data.map(function(value, key) {
                            that.user_roles.push({
                                text: value.name,
                                id: value.id
                            });
                        });
                    })
                    .catch(function (error) {
                        this.$emit('notified', error);
                    });
            },

            close() {
                this.$emit('close');
            },

            setData(userData) {
                this.editedItem = userData;
                this.errors = [];

                if (this.editedItem.id == '') {
                    this.imageFile = '';
                    this.imageUrl = '';
                }
                else {
                    this.edited_role = this.user_roles[this.user_roles.findIndex(f => f.id === this.editedItem.role_id)];
                    this.imageUrl = '/storage/user_avatars/' + this.editedItem.user_avatar;
                    this.imageFile = null;
                }
            }
        }
    }
</script>