<template>
    <div>
        <v-card-title>
            <v-btn @click="saveList" flat color="red darken-4">Save</v-btn>
        </v-card-title>
        <v-card-text>
            <nested-draggable ref="menusortable" flat :menus="records" @sort="sort" />
        </v-card-text>
    </div>
</template>
<script>
    import nestedDraggable from "./Nested.vue";

    export default {
        components: {
            nestedDraggable
        },
        props: {
            records: {type: Array, required: false, default: function () { return []; }},
            sortUrl: {type: String, required: false, default: function () { return ''; }}
        },
        data() {
            return {

                //items: this.records,
            }
        },
        mounted() {},
        methods: {
            editItem(item) {
                this.$emit('showEditForm', item);
            },
            deleteItem(item) {
                this.$emit('showDeleteDialog', item);
            },
            saveList() {
                this.$refs.menusortable.serialize();
            },
            sort(sortedArr) {
                let formData = new FormData();

                let that = this;
                let requestOptions = {
                    headers: {
                        'content-type': 'multipart/form-data'
                    }
                };

                formData.append('sorted', sortedArr);
                formData.append('_method', 'post');

                axios.post(this.sortUrl, formData, requestOptions).then(function (response) {
                    //Object.assign(that.records[that.editedIndex], response.data.data);
                    that.$emit('updated', response.data.data);
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
            }
        }
    }
</script>