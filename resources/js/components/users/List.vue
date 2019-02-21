<template>
    <tr>
        <td class="text-xs-left">
            <v-avatar size="32px" class="mr-3">
                <img v-bind:src="'/storage/user_avatars/' + item.user_avatar" />
            </v-avatar>
            {{ item.name }}
        </td>
        <td class="text-xs-left">{{ item.email }}</td>
        <td class="text-xs-left">{{ item.user_role }}</td>
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
            itemSelected: {type: Boolean, requred: false, default: function() { return false; }},
        },
        data() {
            return {
                item: this.listItem,
                selected: this.itemSelected,
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