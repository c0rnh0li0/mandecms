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
                <span :class="snackClass">{{ notification }}</span>
                <v-btn color="white"
                       flat
                       @click="snackbar = false">
                    <v-icon small class="mr-0">close</v-icon>
                </v-btn>
            </v-snackbar>

            <v-card-text>
                <v-toolbar flat color="white" class="mb-3">
                    <v-toolbar-title>{{ crudData.title }}</v-toolbar-title>
                    <v-spacer></v-spacer>
                    <v-text-field v-if="dataDisplay == 'list'"
                                  color="red darken-4"
                                  prepend-inner-icon="search"
                                  label="Search"
                                  v-model="search"
                                  clearable
                                  single-line
                                  hide-details></v-text-field>
                    <v-spacer></v-spacer>
                    <v-dialog v-model="form_dialog" :max-width="formMaxWidth">
                        <v-btn slot="activator" flat color="red darken-4" @click="setDefaultItemData">New {{ crudData.singular }}</v-btn>
                        <v-card>
                            <v-card-title>
                                <span class="headline">{{ formTitle }}</span>
                                <v-label v-bind:text="errors.msg"></v-label>
                            </v-card-title>


                            <component :is="form"
                                       ref="form"
                                       :updateUrl="updateUrl"
                                       :createUrl="createUrl"
                                       :edited-item="editedItem"
                                       :errors="errors"
                                       :extras="crudData.extras"
                                       @close="close"
                                       @saved="itemCreated"
                                       @updated="itemUpdated"
                                       @notified="notify">
                            </component>
                        </v-card>
                    </v-dialog>
                </v-toolbar>

                <v-data-table v-if="dataDisplay == 'list'"
                        :headers="headers"
                        :items="records"
                        :rows-per-page-items="[-1]"
                        :pagination.sync="pagination"
                        :total-items="totalRecords"
                        :no-data-text="noDataText"
                        :loading="loading"
                        class="elevation-1">
                    <template slot="items" slot-scope="props">
                        <component :is="list"
                                   :key="props.item.id"
                                   :list-item="props.item"
                                   @showDeleteDialog="deleteItemDialog"
                                   @showEditForm="editedItemDialog">
                        </component>
                    </template>
                </v-data-table>
                <v-card v-else>
                    <component :is="list"
                               :records="records"
                               :sort-url="crudData.crud_sort_url"
                               @notified="notify"
                               @showDeleteDialog="deleteItemDialog"
                               @showEditForm="editedItemDialog">
                    </component>
                </v-card>


            </v-card-text>

            <v-dialog v-model="delete_dialog" persistent max-width="290">
                <v-card>
                    <v-card-title class="headline">Delete {{ crudData.singular }}?</v-card-title>
                    <v-card-text>Are you sure you want to delete '{{ itemToDelete.name }}'?</v-card-text>
                    <v-card-actions>
                        <v-spacer></v-spacer>
                        <v-btn color="green darken-1" flat @click="delete_dialog = false">No</v-btn>
                        <v-btn color="green darken-1" flat @click="deleteItem(itemToDelete);">Yes</v-btn>
                    </v-card-actions>
                </v-card>
            </v-dialog>
        </v-card>
    </div>
</template>

<script>
    export default {
        props: {
            crudData: { type: Object, required: false, default: {}},
        },
        components: {

        },
        data() {
            return {
                createUrl: this.crudData.crud_url + '/store',
                updateUrl: this.crudData.crud_url + '/update/',
                deleteUrl: this.crudData.crud_url + '/delete/',
                baseUrl: this.crudData.crud_url,

                form: this.crudData.form,
                list: this.crudData.list,
                noDataText: 'No ' + this.crudData.plural + ' found',

                form_dialog: false,
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

                dataDisplay: this.crudData.dataDisplay,

                headers: this.crudData.dt_headers,

                itemToDelete: {},
                // add/edit section
                editedIndex: -1,
                editedItem: this.crudData.editedItem,
                defaultItem: this.crudData.defaultItem,
                errors: [],

                // search section
                search: null,
                searchTerm: '',

                selected: [],

                formTitle: '',
                formMaxWidth: '500px',

                snackClass: '',
            }
        },
        created() {
            if (this.dataDisplay == 'sortable') {
                let pageUrl = this.crudData.crud_builder_url;
                let that = this;
                this.getData(pageUrl)
                    .then(data => {
                        that.records = data.data.data;
                    })
                    .catch(error => {
                        that.notify(error, 'error');
                    });
            }
        },
        mounted() {
            this.dataDisplay = this.crudData.dataDisplay;

            if (typeof this.crudData.formMaxWidth != 'undefined') {
                this.formMaxWidth = this.crudData.formMaxWidth;
            }

            if (this.crudData.onMounted != null && typeof this.crudData.onMounted == 'function') {
                this.crudData.onMounted();
            }
        },
        watch: {
            pagination: {
                handler () {
                    let pageUrl = this.buildPagingUrl();
                    let that = this;
                    this.getData(pageUrl)
                        .then(data => {
                            that.updateData(data.data);
                            that.loading = false;
                        })
                        .catch(error => {
                            that.notify(error, 'error');
                        });
                },
                deep: true
            },
            /*dialog (val) {
                val || this.close();
            },*/
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
                        that.notify(error, 'error');
                    });
            },
        },
        computed: {
            /*formTitle () {
                return this.editedIndex === -1 ? 'New ' + this.crudData.singular : 'Edit ' + this.crudData.singular + ' \'' + this.editedItem.name + '\'';
            }*/
        },
        methods: {
            deleteItemDialog(item) {
                this.itemToDelete = item;
                this.delete_dialog = true;
            },
            editedItemDialog(item) {
                this.editedItem = item;
                this.editedIndex = this.findWithAttr(this.records, 'id', item.id);

                this.formTitle = 'Edit ' + this.crudData.singular + ' \'' + this.editedItem.name + '\'';

                this.$refs.form.setData(this.editedItem);
                this.form_dialog = true;
            },

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
                page_url = page_url || this.baseUrl;

                return await axios.get(page_url);
            },

            setDefaultItemData() {
                this.editedIndex = -1;
                this.editedItem = this.defaultItem;
                this.formTitle = 'New ' + this.crudData.singular;
                this.$refs.form.setData(this.editedItem);
            },

            deleteItem (item) {
                let that = this;

                let formData = new FormData();
                formData.append('_method', 'delete');

                axios.post(this.deleteUrl + item.id, formData).then(function (response) {
                    if (response.data.success == true) {
                        that.notify(response.data.message, 'success');

                        let pageUrl = that.buildPagingUrl();

                        if (that.dataDisplay == 'sortable')
                            pageUrl = that.crudData.crud_builder_url;

                        that.getData(pageUrl)
                            .then(function (data) {
                                if (that.dataDisplay == 'sortable')
                                    that.records = data.data.data;
                                else
                                    that.updateData(data.data);
                            })
                            .catch(function (error) {
                                that.notify(error, 'error');
                            });

                        that.delete_dialog = false;
                    }
                }).catch(function(err) {
                    that.notify(err, 'error');
                });
            },

            close () {
                this.form_dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem);
                    this.editedIndex = -1;
                }, 300);
            },

            doSearch() {
                if (this.search != '') {
                    let pageUrl = this.pagination.
                    if (pageUrl.indexOf('?') < 0)
                    pageUrl += '?a=b';

                    pageUrl += '&q=' + this.search;
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

            itemCreated() {
                let pageUrl = this.buildPagingUrl();

                if (this.dataDisplay == 'sortable')
                    pageUrl = this.crudData.crud_builder_url;

                let that = this;
                this.getData(pageUrl)
                    .then(function (data) {
                        if (that.dataDisplay == 'sortable')
                            that.records = data.data.data;
                        else
                            that.updateData(data.data);
                    })
                    .catch(function (error) {
                        that.notify(error, 'error');
                    });

                this.notify('Sucessfully saved!', 'success');
                this.form_dialog = false;
            },

            itemUpdated(data) {
                Object.assign(this.records[this.editedIndex], data);
                this.notify('\'' + data.name + '\' sucessfully saved!', 'success');
                this.form_dialog = false;
            },

            deleteDialogClose() {
                this.delete_dialog = false;
            },

            close() {
                this.form_dialog = false;
            },

            findWithAttr(array, attr, value) {
                for(var i = 0; i < array.length; i += 1) {
                    if(array[i][attr] === value) {
                        return i;
                    }
                }
                return -1;
            },
        }
    }
</script>