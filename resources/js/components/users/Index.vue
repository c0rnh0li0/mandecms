<template>
    <div>
        <v-card>
            <v-toolbar flat color="white">
                <v-toolbar-title>Users</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-text-field color="red darken-4"
                              prepend-inner-icon="search"
                              label="Search"
                              v-model="search"
                              single-line
                              hide-details></v-text-field>
                <v-spacer></v-spacer>
                <v-dialog v-model="dialog" max-width="500px">
                    <v-btn slot="activator" flat color="red darken-4">New user</v-btn>
                    <v-card>
                        <v-card-title>
                            <span class="headline">{{ formTitle }}</span>
                        </v-card-title>

                        <v-card-text>
                            <v-form method="POST" v-on:submit.prevent="save">
                                <v-container grid-list-md>
                                    <v-layout wrap>
                                        <v-input type="hidden" name="id" v-model="editedItem.id"></v-input>
                                        <v-container grid-list-md>
                                            <v-text-field v-model="editedItem.name" label="Name" color="red darken-4"></v-text-field>
                                        </v-container>
                                        <v-container grid-list-md>
                                            <v-text-field v-model="editedItem.email" label="Email" color="red darken-4"></v-text-field>
                                        </v-container>
                                        <v-container grid-list-md>
                                            <v-combobox
                                                    v-model="roles_model"
                                                    :items="user_roles"
                                                    label="Role"
                                                    color="red darken-4"
                                            ></v-combobox>
                                        </v-container>
                                        <v-container grid-list-md>
                                            <v-text-field label="Select Avatar" @click.stop="pickFile" v-model='imageName' prepend-icon='attach_file' color="red darken-4"></v-text-field>
                                            <input
                                                    type="file"
                                                    style="display: none"
                                                    ref="image"
                                                    accept="image/*"
                                                    @change="onFilePicked">
                                            <img :src="imageUrl" height="150" v-if="imageUrl"/>
                                        </v-container>
                                    </v-layout>
                                </v-container>
                            </v-form>
                        </v-card-text>

                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="red darken-4" flat @click="close">Cancel</v-btn>
                            <v-btn color="red darken-4" flat @click="save">Save</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </v-toolbar>

            <v-data-table
                    :headers="headers"
                    :items="records"
                    :pagination.sync="pagination"
                    :total-items="totalRecords"
                    :loading="loading"
                    class="elevation-1">
                <template slot="items" slot-scope="props">
                    <td class="text-xs-center">
                        <v-avatar size="32px">
                            <img v-bind:src="props.item.user_avatar" />
                        </v-avatar>
                    </td>
                    <td class="text-xs-left">{{ props.item.name }}</td>
                    <td class="text-xs-left">{{ props.item.email }}</td>
                    <td class="text-xs-left">{{ props.item.user_role }}</td>
                    <td class="text-xs-right">{{ props.item.created_at }}</td>
                    <td class="justify-center layout px-0">
                        <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
                        <v-icon small @click="deleteItem(props.item)">delete</v-icon>
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
                records: [],
                metapaging: {},
                metalinks: {},
                totalRecords: 0,
                loading: true,
                pagination: {
                    sortBy: 'created_at'
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

                // add/edit section
                editedIndex: -1,
                editedItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    user_avatar: '',
                    created_at: ''
                },
                defaultItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    user_avatar: '',
                    created_at: ''
                },

                // avatar section
                imageName: '',
                imageUrl: '',
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
                this.searchTerm = val;
                this.getData(dataUrl + '&q=' + val)
                    .then(function (data) {
                        that.updateData(data.data);
                    })
                    .catch(function (error) {
                        console.log(error);
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

            editItem (item) {
                this.editedIndex = this.records.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true;
            },

            deleteItem (item) {
                if (!confirm('Are you sure you want to delete ' + item.name + '?'))
                    return;

                let that = this;

                axios.delete('api/users/delete/' + item.id)
                    .then(function (response) {
                        if (response.data.success) {
                            that.getData()
                                .then(function (response) {
                                    that.updateData(response.data.data);
                                })
                                .catch(function (error) {
                                    console.log(error);
                                });
                        }
                    })
                    .catch(function (error) {
                        console.log(error);
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
                if (this.editedIndex > -1) {
                    this.editedItem.method = 'PUT';

                    axios.put('api/users/update/' + this.editedItem.id,
                        this.editedItem, {
                            headers: {
                                'Content-Type': 'multipart/form-data'
                            }
                        }).then(function (response) {
                            console.log(response);
                        });
                    Object.assign(this.records[this.editedIndex], this.editedItem);
                } else {
                    //axios.post
                    this.records.push(this.editedItem);
                }
                this.close();
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
                        console.log(error);
                    });
            },

            pickFile () {
                this.$refs.image.click();
            },

            onFilePicked (e) {
                const files = e.target.files;
                if(files[0] !== undefined) {
                    this.imageName = files[0].name;
                    if(this.imageName.lastIndexOf('.') <= 0) {
                        return;
                    }
                    const fr = new FileReader ();
                    fr.readAsDataURL(files[0]);
                    fr.addEventListener('load', () => {
                        this.imageUrl = fr.result;
                        this.imageFile = files[0]; // this is an image file that can be sent to server...
                    });
                } else {
                    this.imageName = '';
                    this.imageFile = '';
                    this.imageUrl = '';
                }
            },

            doSearch() {
                //console.log('searching', this.search);
                console.log('searching val', this.search);
                if (this.search != '') {
                    let pageUrl = this.pagination.
                    if (pageUrl.indexOf('?') < 0)
                    pageUrl += '?a=b';

                    pageUrl += '&q=' + this.search;
                }

                console.log(pageUrl);
            }
        }
    }
</script>