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
                    <v-card>
                        <v-card-title primary-title>
                            <div>
                                <h5 class="headline mb-0">Manage access</h5>
                            </div>
                        </v-card-title>
                        <v-card-text>
                            <v-container fluid>
                                <v-layout row wrap>
                                    <v-flex xs6 v-for="policy in policies" :key="policy.id">
                                        <v-tooltip top color="red darken-4">
                                            <template #activator="data">
                                                <v-switch v-on="data.on" v-model="role_policies" :value="policy.id" :label="policy.name" color="red darken-4"></v-switch>
                                            </template>
                                            <span>{{ policy.description }}</span>
                                        </v-tooltip>
                                    </v-flex>
                                </v-layout>
                            </v-container>
                        </v-card-text>
                    </v-card>
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
                userCreateUrl: this.createUrl,
                userUpdateUrl: this.updateUrl,
                policiesUrl: this.extras.policies_url,

                editedItem: {},
                role_policies: [],
                policies: [],

                errors: [],
            }
        },
        watch: {
            role_policies: function (val) {
                this.editedItem.policies = this.attachPolicies(val);
            },
        },
        mounted() {
            let that = this;

            this.getPolicies()
                .then(function (response) {
                    that.policies = response.data.data;
                })
                .catch(function (err) {
                    that.$emit('notified', err.message);
                });
        },
        methods: {
            save (e) {
                this.editedItem.method = 'PUT';
                let formData = new FormData();

                for (var property in this.editedItem) {
                    formData.append(property, this.editedItem[property]);
                }

                formData.append('policies', this.role_policies);

                let that = this;
                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                if (this.editedItem.id != '') {
                    formData.append('_method', 'put');

                    axios.post(this.updateUrl + this.editedItem.id, formData, requestOptions).then(function (response) {
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
                        if (response.data.success == true)
                            that.$emit('saved');

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

            setData(roleData) {
                this.editedItem = roleData;
                this.role_policies = _.map(this.editedItem.policies, 'id');
                this.errors = [];
            },

            async getPolicies() {
                return await axios.get(this.policiesUrl);
            },

            attachPolicies(val) {
                if (val.length == 0)
                    return [];

                let polArr = [];
                for (let i = 0; i < val.length; i++) {
                    let p = this.policies.find(function(policy) {
                        return policy.id == val[i];
                    });

                    if (p)
                        polArr.push(p);
                }

                return polArr;
            }
        }
    }
</script>