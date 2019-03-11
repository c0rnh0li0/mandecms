<template>
    <div>
        <nested-draggable flat :menus="records" />
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
        },
        data() {
            return {
                //items: this.records,
            }
        },
        mounted() {
            console.log('records in list', this.records);
        },
        methods: {
            editItem(item) {
                this.$emit('showEditForm', item);
            },
            deleteItem(item) {
                this.$emit('showDeleteDialog', item);
            },
        }
    }
</script>