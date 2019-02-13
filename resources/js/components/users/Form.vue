<template>
    <v-dialog v-model="dialog" max-width="500px">
        <v-btn slot="activator" color="red darken-4">New Item</v-btn>
        <v-card>
            <v-card-title>
                <span class="headline">User</span>
            </v-card-title>

            <v-card-text>
                <v-container grid-list-md>
                    <v-layout wrap>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.name" label="Name"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.email" label="Email"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.user_role" label="Role"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.user_avatar" label="Avatar"></v-text-field>
                        </v-flex>
                        <v-flex xs12 sm6 md4>
                            <v-text-field v-model="editedItem.created_at" label="Created at"></v-text-field>
                        </v-flex>
                    </v-layout>
                </v-container>
            </v-card-text>

            <v-card-actions>
                <v-spacer></v-spacer>
                <v-btn color="blue darken-1" flat @click="close">Cancel</v-btn>
                <v-btn color="blue darken-1" flat @click="save">Save</v-btn>
            </v-card-actions>
        </v-card>
    </v-dialog>
</template>

<script>
    export default {
        data() {
            return {
                editedIndex: -1,
                editedItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    user_avatar: '',
                    created_at: ''
                },
                defaultItem: {
                    id: '',
                    name: '',
                    user_role: '',
                    user_avatar: '',
                    created_at: ''
                }
            }
        },
        mounted() {
            console.log('user form mounted');
        },
        methods: {
            close () {
                this.dialog = false
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300)
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.records[this.editedIndex], this.editedItem)
                } else {
                    this.records.push(this.editedItem)
                }
                this.close()
            }
        }
    }
</script>