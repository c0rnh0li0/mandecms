<template>
    <div>
        <crud :crud-data="users_crud"></crud>
    </div>
</template>

<script>
    import Crud from './../crud/Crud.vue';
    import Form from './Form.vue';
    import List from './List.vue';

    export default {
        components: {
            Crud,
        },
        data() {
            return {
                users_crud: {
                    title: 'Users',
                    singular: 'user',
                    plural: 'users',
                    form: Form,
                    list: List,
                    dt_headers: [
                        { text: '', align: 'center', value: 'user_avatar', sortable: false },
                        { text: 'Name', align: 'left', sortable: true, value: 'name' },
                        { text: 'Email', align: 'left', value: 'email', sortable: true },
                        { text: 'Role', align: 'left', value: 'user_role', sortable: false },
                        { text: 'Created at', align: 'right', value: 'created_at', sortable: true },
                        { text: '', align: 'center', value: 'name', sortable: false }
                    ],
                    crud_url: '/api/users',

                    editedItem: {
                        id: '',
                        name: '',
                        user_role: '',
                        role_id: '',
                        user_avatar: 'default_avatar.png',
                        created_at: ''
                    },
                    defaultItem: {
                        id: '',
                        name: '',
                        user_role: '',
                        role_id: '',
                        user_avatar: 'default_avatar.png',
                        created_at: ''
                    },
                    extras: {
                        role_select: '',
                        user_roles: [],
                        roles_model: [],
                        edited_role: {
                            id: 2,
                            text: 'Contributor'
                        },

                        // avatar section
                        imageUrl: '/storage/user_avatars/default_avatar.png',
                        imageFile: '',
                    },
                },
            }
        },
        methods: {
            setDefaultUserData() {
                this.editedItem = this.defaultItem;
                this.imageUrl = '/storage/user_avatars/default_avatar.png';
                this.imageFile = null;
                this.edited_role = {
                    id: 2,
                    text: 'Contributor'
                };
            },

            editItem (item) {
                this.editedIndex = this.records.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.edited_role = this.user_roles[this.user_roles.findIndex(f => f.id === this.editedItem.role_id)];
                this.imageUrl = '/storage/user_avatars/' + this.editedItem.user_avatar;
                this.imageFile = null;
                this.errors = [];

                this.dialog = true;
            },
        }
    }
</script>