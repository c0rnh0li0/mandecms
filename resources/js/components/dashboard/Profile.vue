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
                    <v-toolbar-title>Hi {{ editedItem.name }}!</v-toolbar-title>
                    <v-spacer></v-spacer>
                </v-toolbar>
                <v-container grid-list-md text-xs-center>
                    <v-layout row wrap>
                        <v-flex xs4>
                            <v-card class="elevation-1">
                                <v-card-title>
                                    <h4>Your info</h4>
                                </v-card-title>
                                <v-card-text class="px-0">
                                    <v-form method="POST" v-on:submit.prevent="saveProfile">
                                        <v-layout wrap>
                                            <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.name"
                                                        label="Name"
                                                        color="red darken-4"
                                                        :messages="errors.name"
                                                        :rules="[rules.required]"
                                                        :error="typeof errors.name != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="editedItem.email"
                                                        label="Email"
                                                        :rules="[rules.required, rules.email]"
                                                        color="red darken-4"
                                                        :messages="errors.email"
                                                        :error="typeof errors.email != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        :value="editedItem.user_role"
                                                        label="Role"
                                                        color="red darken-4"
                                                        readonly>
                                                </v-text-field>
                                            </v-container>
                                        </v-layout>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="red darken-4" flat @click="saveProfile">Save</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <v-flex xs4>
                            <v-card class="elevation-1">
                                <v-card-title>
                                    <h4>Your avatar</h4>
                                </v-card-title>
                                <v-card-text class="px-0">
                                    <span class="red--text text--darken-4"><small>(Click to change)</small></span>
                                    <v-container grid-list-md>
                                        <input type="file"
                                               style="display: none"
                                               name="user_avatar"
                                               ref="image"
                                               accept="image/*"
                                               @change="onFilePicked">

                                        <v-spacer></v-spacer>
                                        <v-avatar :tile="false" :size="150" style="cursor: pointer;">
                                            <img :src="imageUrl" height="150" v-if="imageUrl" @click.stop="pickFile"/>
                                        </v-avatar>
                                        <v-spacer></v-spacer>
                                        <span v-if="typeof errors.user_avatar != 'undefined'" color="red--text text--darken-4">{{ errors.user_avatar[0] }}</span>
                                    </v-container>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="red darken-4" flat @click="saveProfile">Save</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                        <v-flex xs4>
                            <v-card class="elevation-1">
                                <v-card-title>
                                    <h4>Change your password</h4>
                                </v-card-title>
                                <v-card-text class="px-0">
                                    <v-form method="POST" v-on:submit.prevent="changePassword">
                                        <v-layout wrap>
                                            <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="oldPassword"
                                                        label="Old password"
                                                        type="password"
                                                        color="red darken-4"
                                                        :messages="errors.oldPassword"
                                                        :error="typeof errors.oldPassword != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="newPassword"
                                                        label="New password"
                                                        type="password"
                                                        color="red darken-4"
                                                        :messages="errors.newPassword"
                                                        :error="typeof errors.newPassword != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                            <v-container grid-list-md>
                                                <v-text-field
                                                        v-model="newPasswordConfirm"
                                                        label="Confirm new password"
                                                        type="password"
                                                            :rules="[rules.required, rules.passwordMatch]"
                                                        color="red darken-4"
                                                        :error-messages="errors.newPasswordConfirm"
                                                        :error="typeof errors.newPasswordConfirm != 'undefined'">
                                                </v-text-field>
                                            </v-container>
                                        </v-layout>
                                    </v-form>
                                </v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="red darken-4" flat @click="changePassword">Save</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

        </v-card>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                rules: {
                    required: value => !!value || 'Required.',
                    passwordMatch: value => {
                        return this.newPassword == value || 'Passwords doesn\'t match';
                    },
                    email: value => {
                        const pattern = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/
                        return pattern.test(value) || 'Invalid e-mail.';
                    }
                },

                editedItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    role_id: '',
                    user_avatar: 'default_avatar.png',
                    created_at: ''
                },
                errors: [],

                // avatar section
                imageUrl: '/storage/user_avatars/default_avatar.png',
                imageFile: '',

                oldPassword: '',
                newPassword: '',
                newPasswordConfirm: '',

                snackbar: false,
                notification: '',
            }
        },
        mounted() {
            this.getUserData();
        },
        methods: {
            getUserData() {
                var that = this;
                axios.get('/api/users/info')
                    .then(function (response) {
                        that.editedItem = response.data.data;
                        that.imageUrl = '/storage/user_avatars/' + that.editedItem.user_avatar;
                    })
                    .catch(function (error) {
                        // handle error
                        console.log(error);
                    })
            },
            saveProfile (e) {
                this.editedItem.method = 'PUT';

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

                formData.append('_method', 'put');

                axios.post('/api/users/update/' + this.editedItem.id, formData, requestOptions).then(function (response) {
                    Object.assign(that.editedItem, response.data.data);
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
            },

            changePassword(e) {
                if (this.newPassword != this.newPasswordConfirm) {
                    this.errors = [
                        { newPasswordConfirm: 'Your passwords doesn\'t match' },
                    ];
                    return;
                }

                let that = this;
                let formData = new FormData();
                formData.append('_method', 'post');
                formData.append('oldPassword', this.oldPassword);
                formData.append('newPassword', this.newPassword);

                axios.post('/api/users/password/' + this.editedItem.id, formData).then(function (response) {
                    if (response.data.success == true) {
                        that.notify(response.data.message);
                    }
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
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