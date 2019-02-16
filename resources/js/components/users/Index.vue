<template>
    <div>
        <v-card>
            <v-snackbar
                    v-model="snackbar"
                    :bottom="false"
                    :left="false"
                    :multi-line="false"
                    :right="false"
                    :timeout="3000"
                    :top="true"
                    :vertical="false">
                {{ notification }}
                <v-btn color="pink"
                       flat
                       @click="snackbar = false">
                    <v-icon small class="mr-2">close</v-icon>
                </v-btn>
            </v-snackbar>

            <v-toolbar flat color="white">
                <v-toolbar-title>Users</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-text-field color="red darken-4"
                              prepend-inner-icon="search"
                              label="Search"
                              v-model="search"
                              clearable
                              single-line
                              hide-details></v-text-field>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <v-btn slot="activator" flat color="red darken-4" @click="setDefaultUserData">New user</v-btn>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                            <v-label v-bind:text="errors.msg"></v-label>
                        </v-card-title>

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
                        </v-card-text>
                        <v-card-actions>
                            <v-btn color="red darken-4" flat @click="close">Cancel</v-btn>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-4" flat @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-data-table
                    :headers="headers"
                    :items="records"
                    :rows-per-page-items="[]"
                    :pagination.sync="pagination"
                    :total-items="totalRecords"
                    :loading="loading"
                    class="elevation-1">
                <template slot="items" slot-scope="props">
                    <td class="text-xs-center">
                        <v-avatar size="32px">
                            <img v-bind:src="'/storage/user_avatars/' + props.item.user_avatar" />
                        </v-avatar>
                    </td>
                    <td class="text-xs-left">{{ props.item.name }}</td>
                    <td class="text-xs-left">{{ props.item.email }}</td>
                    <td class="text-xs-left">{{ props.item.user_role }}</td>
                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                    <td class="justify-center layout px-0">
                        <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
                        <v-icon small class="mr-2" @click="deleteItemDialog()">delete</v-icon>

                        <v-dialog v-model="delete_dialog" persistent max-width="290">
                            <v-card>
                                <v-card-title class="headline">Delete user?</v-card-title>
                                <v-card-text>Are you sure you want to delete {{ props.item.name }}?</v-card-text>
                                <v-card-actions>
                                    <v-spacer></v-spacer>
                                    <v-btn color="green darken-1" flat @click="delete_dialog = false">No</v-btn>
                                    <v-btn color="green darken-1" flat @click="deleteItem(props.item);">Yes</v-btn>
                                </v-card-actions>
                            </v-card>
                        </v-dialog>
                    </td>
                </template>
            </v-data-table>
        </v-card>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dialog: false,
                delete_dialog: false,
                snackbar: false,
                notification: '',
                records: [],
                metapaging: {},
                metalinks: {},
                totalRecords: 0,
                loading: true,
                pagination: {
                    sortBy: 'created_at',
                    value: 0
                },
                headers: [
                    { text: '', align: 'center', value: 'user_avatar', sortable: false },
                    { text: 'Name', align: 'left', sortable: true, value: 'name' },
                    { text: 'Email', align: 'left', value: 'email', sortable: true },
                    { text: 'Role', align: 'left', value: 'user_role', sortable: false },
                    { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                    { text: '', align: 'center', value: 'name', sortable: false }
                ],
                // roles section
                role_select: '',
                user_roles: [],
                roles_model: [],
                edited_role: {
                    id: 2,
                    text: 'Contributor'
                },

                // add/edit section
                editedIndex: -1,
                editedItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    role_id: '',
                    user_avatar: 'default_avatar.png',
                    created_at: ''
                },
                defaultItem: {
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

                // search section
                search: null,
                searchTerm: '',
            }
        },
        mounted() {
            this.getRoles();
        },
        watch: {
            pagination: {
                handler () {
                    let pageUrl = this.buildPagingUrl();

                    this.getData(pageUrl)
                        .then(data => {
                            this.updateData(data.data);
                            this.loading = false;
                        })
                },
                deep: true
            },
            dialog (val) {
                val || this.close();
            },
            search (val) {
                // Items have already been requested
                if (this.loading && val != '') return;

                if (!this.metapaging.path) return;

                let dataUrl = this.metapaging.path;
                if (dataUrl.indexOf('?') < 0)
                    dataUrl += '?a=b';

                let that = this;
                this.searchTerm = val != null ? val : '';
                let searchQ = val != null ? '&q=' + val : '';

                this.getData(dataUrl + searchQ)
                    .then(function (data) {
                        that.updateData(data.data);
                    })
                    .catch(function (error) {
                        that.notify(error);
                    });
            },
        },
        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New user' : 'Edit ' + this.editedItem.name + ' data';
            }
        },
        methods: {
            updateData(data) {
                this.loading = false;

                this.records = data.data;
                this.totalRecords = data.meta.total;
                this.metapaging = data.meta;
                this.metalinks = data.links;

                this.pagination.rowsPerPage = this.metapaging.per_page;
                this.pagination.totalItems = this.metapaging.total;
                this.pagination.page = this.metapaging.current_page;
            },
            buildPagingUrl() {
                let pageUrl = this.metapaging.path;

                if (this.pagination.page > this.metapaging.last_page)
                    return null;

                if (this.pagination.page > this.metapaging.current_page &&
                    this.pagination.page <= this.metapaging.last_page &&
                    this.metalinks.next)
                    pageUrl = this.metalinks.next;
                else if (this.pagination.page < this.metapaging.current_page &&
                    this.pagination.page >= 1 &&
                    this.metalinks.prev)
                    pageUrl = this.metalinks.prev;

                if (this.pagination.sortBy && pageUrl) {
                    if (pageUrl.indexOf('?') < 0)
                        pageUrl += '?a=b';

                    pageUrl += '&sort=' + this.pagination.sortBy;
                    pageUrl += '&direction=' + (this.pagination.descending ? 'desc' : 'asc');
                }

                if (this.searchTerm != '' && pageUrl) {
                    if (pageUrl.indexOf('?') < 0)
                        pageUrl += '?a=b';

                    pageUrl += '&q=' + this.searchTerm;
                }

                return pageUrl;
            },
            async getData(page_url) {
                this.loading = true;
                page_url = page_url || 'api/users';
                return await axios.get(page_url);
            },

            setDefaultUserData() {
                this.editedItem = this.defaultItem;
                this.imageUrl = '/storage/user_avatars/default_avatar.png';
                this.imageFile = null;
                this.edited_role = {
                    id: 2,
                    text: 'Contributor'
                };
            },

            editItem (item) {
                this.editedIndex = this.records.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.edited_role = this.user_roles[this.user_roles.findIndex(f => f.id === this.editedItem.role_id)];
                this.imageUrl = '/storage/user_avatars/' + this.editedItem.user_avatar;
                this.imageFile = null;
                this.errors = [];

                this.dialog = true;
            },

            deleteItemDialog() {
                this.delete_dialog = true;
            },

            deleteItem (item) {
                let that = this;

                let formData = new FormData();
                formData.append('_method', 'delete');

                axios.post('api/users/delete/' + item.id, formData).then(function (response) {
                    if (response.data.success == true) {
                        that.getData()
                            .then(function (data) {
                                that.updateData(data.data);
                            })
                            .catch(function (error) {
                                that.notify(error);
                            });

                        that.delete_dialog = false;
                    }
                }).catch(function(err) {
                    that.notify(error);
                });
            },

            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300);
            },

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
                        //'Content-Type': "multipart/form-data; charset=utf-8; boundary=" + Math.random().toString().substr(2)
                        'content-type': 'multipart/form-data'
                    }
                };

                if (this.editedIndex > -1) {
                    formData.append('_method', 'put');

                    axios.post('api/users/update/' + this.editedItem.id, formData, requestOptions).then(function (response) {
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

                    axios.post('api/users/store', formData, requestOptions).then(function (response) {
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
                //this.close();
            },

            getRoles() {
                let that = this;
                axios.get('api/roles')
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

            doSearch() {
                if (this.search != '') {
                    let pageUrl = this.pagination.
                    if (pageUrl.indexOf('?') < 0)
                    pageUrl += '?a=b';

                    pageUrl += '&q=' + this.search;
                }
            },

            notify(text) {
                this.notification = text;
                this.snackbar = true;
            }
        }
    }
</script>