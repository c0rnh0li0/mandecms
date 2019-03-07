<template>
    <tr>
        <td class="text-xs-left">{{ item.name }}</td>
        <td class="text-xs-left">{{ item.slug }}</td>
        <td class="text-xs-right">{{ item.created_at }}</td>
        <td class="justify-end layout px-0">
            <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
            <v-icon small class="mr-2" @click="deleteItem(item)">delete</v-icon>
        </td>
    </tr>
</template>
<script>
    export default {
        props: {
            listItem: {type: Object, required: false, default: function () { return {}; }},
        },
        data() {
            return {
                item: this.listItem,
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
        }
    }
</script>