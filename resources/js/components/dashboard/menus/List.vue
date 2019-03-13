<template>
    <div>
        <v-card-title>
            <v-spacer></v-spacer>
            <v-btn :disabled="!menuOrderChanged" @click="saveList" flat color="red darken-4">Save menu order</v-btn>
            <v-spacer></v-spacer>
        </v-card-title>
        <v-card-text>
            <nested-draggable ref="menusortable"
                              flat
                              :menus="records"
                              @sort="sort"
                              @menuchanged="menuChanged"
                              @editItem="editItem"
                              @deleteItem="deleteItem">
            </nested-draggable>
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
                menuOrderChanged: false,
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
            menuChanged(bChanged) {
                if (!this.menuOrderChanged && bChanged)
                    this.menuOrderChanged = true;
            },
            saveList() {
                this.$refs.menusortable.serialize();
                this.menuOrderChanged = false;
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
                    that.$emit('notified', response.data.message, 'success');
                }).catch(function(err) {
                    if (err && err.response && err.response.status === 422) {
                        that.errors = err.response.data.errors || {};
                        that.errors.msg = err.response.data.message;
                    }
                });
            },
        }
    }
</script>