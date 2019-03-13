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
                        <v-textarea
                                solo
                                v-model="editedItem.description"
                                name="description"
                                color="red darken-4"
                                label="Description"
                                value="editedItem.description"
                                :messages="errors.description"
                                :error="typeof errors.description != 'undefined'">
                        </v-textarea>
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
            }
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

            close() {
                this.$emit('close');
            },

            setData(policyData) {
                this.editedItem = policyData;
                this.errors = [];
            }
        }
    }
</script>