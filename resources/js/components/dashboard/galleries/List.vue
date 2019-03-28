<template>
    <tr>
        <td class="text-xs-left">{{ item.title }}</td>
        <td class="text-xs-left">{{ item.description }}</td>
        <td class="text-xs-left">{{ item.url }}</td>
        <td class="text-xs-left">{{ item.owner_name }}</td>
        <td class="text-xs-right">{{ item.created_at }}</td>
        <td class="justify-end layout px-0">
            <gallery :gallery-images="item.images" :gallery-id="item.id" :gallery-object="item" class="mr-2"></gallery>
            <v-icon small class="mr-2" @click="editItem(item)">edit</v-icon>
            <v-icon small class="mr-2" @click="deleteItem(item)">delete</v-icon>
        </td>
    </tr>
</template>
<script>
    import Gallery from './Images';

    export default {
        components: {
            Gallery
        },
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
            addImages(item) {
                console.log('will upload images');
            }
        }
    }
</script>