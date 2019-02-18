<template>
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
                <v-text-field label="Select Avatar"
                              @click.stop="pickFile"
                              v-model='editedItem.user_avatar'
                              prepend-icon='attach_file'
                              color="red darken-4"
                              :messages="errors.user_avatar"
                              :error="typeof errors.user_avatar != 'undefined'"></v-text-field>
                <input
                        type="file"
                        style="display: none"
                        name="user_avatar"
                        ref="image"
                        accept="image/*"
                        @change="onFilePicked">
                <v-spacer></v-spacer>
                <img :src="imageUrl" height="150" v-if="imageUrl"/>
                <v-spacer></v-spacer>
            </v-container>
        </v-layout>
    </v-form>
</template>

<script>
    export default {
        props: {
            extras: {type: Object, required: false, default: {}},
            //errors: {type: Array, required: false, default: {}},
            //userRoles: {type: Array, required: false, default: {}},
        },
        data() {
            return {
                editedItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    role_id: '',
                    user_avatar: 'default_avatar.png',
                    created_at: ''
                },

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

                if (typeof this.imageFile == 'Object')
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

                if (this.editedIndex > -1) {
                    formData.append('_method', 'put');

                    axios.post(this.updateUrl + this.editedItem.id, formData, requestOptions).then(function (response) {
                        Object.assign(that.records[that.editedIndex], response.data.data);
                        that.close();
                    }).catch(function(err) {
                        if (err && err.response && err.response.status === 422) {
                            that.errors = err.response.data.errors || {};
                            that.errors.msg = err.response.data.message;
                        }
                    });
                } else {
                    this.editedItem.method = 'POST';
                    //formData.append('_method', 'post');

                    axios.post(this.createUrl, formData, requestOptions).then(function (response) {
                        if (response.data.success == true) {
                            that.getData()
                                .then(function (data) {
                                    that.updateData(data.data);
                                })
                                .catch(function (error) {
                                    that.notify(error);
                                });

                            that.close();
                        }

                        that.notify(response.data.message);
                    }).catch(function(err) {
                        if (err && err.response && err.response.status === 422) {
                            that.errors = err.response.data.errors || {};
                            that.errors.msg = err.response.data.message;
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
                    this.editedItem.user_avatar = files[0].name;
                    if(this.editedItem.user_avatar.lastIndexOf('.') <= 0) {
                        return;
                    }
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
                        that.notify(error);
                    });
            },
        }
    }
</script>