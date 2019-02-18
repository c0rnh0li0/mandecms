<template>
    <div>
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
                <v-text-field
                        v-model="editedItem.email"
                        label="Email"
                        color="red darken-4"
                        :messages="errors.email"
                        :error="typeof errors.email != 'undefined'">
                </v-text-field>
            </v-container>
            <v-container grid-list-md>
                <v-combobox
                        v-model="edited_role"
                        :items="user_roles"
                        :messages="errors.user_role"
                        :error="typeof errors.user_role != 'undefined'"
                        item-text="text"
                        item-value="id"
                        label="Role"
                        color="red darken-4">
                </v-combobox>
            </v-container>
            <v-container grid-list-md>
                <v-text-field label="Select Avatar"
                              @click.stop="pickFile"
                              v-model='editedItem.user_avatar'
                              prepend-icon='attach_file'
                              color="red darken-4"
                              :messages="errors.user_avatar"
                              :error="typeof errors.user_avatar != 'undefined'"></v-text-field>
                <input
                        type="file"
                        style="display: none"
                        name="user_avatar"
                        ref="image"
                        accept="image/*"
                        @change="onFilePicked">
                <v-spacer></v-spacer>
                <img :src="imageUrl" height="150" v-if="imageUrl"/>
                <v-spacer></v-spacer>
            </v-container>
        </v-layout>
    </div>
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