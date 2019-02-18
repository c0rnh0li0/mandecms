<template>
    <div>
        <template slot="items" slot-scope="props">
            <td class="text-xs-center">
                <v-avatar size="32px">
                    <img v-bind:src="'/storage/user_avatars/' + props.item.user_avatar" />
                </v-avatar>
            </td>
            <td class="text-xs-left">{{ props.item.name }}</td>
            <td class="text-xs-left">{{ props.item.email }}</td>
            <td class="text-xs-left">{{ props.item.user_role }}</td>
            <td class="text-xs-right">{{ props.item.created_at }}</td>
            <td class="justify-center layout px-0">
                <v-icon small class="mr-2" @click="editItem(props.item)">edit</v-icon>
                <v-icon small class="mr-2" @click="deleteItemDialog()">delete</v-icon>

                <v-dialog v-model="delete_dialog" persistent max-width="290">
                    <v-card>
                        <v-card-title class="headline">Delete user?</v-card-title>
                        <v-card-text>Are you sure you want to delete {{ props.item.name }}?</v-card-text>
                        <v-card-actions>
                            <v-spacer></v-spacer>
                            <v-btn color="green darken-1" flat @click="delete_dialog = false">No</v-btn>
                            <v-btn color="green darken-1" flat @click="deleteItem(props.item);">Yes</v-btn>
                        </v-card-actions>
                    </v-card>
                </v-dialog>
            </td>
        </template>
    </div>
</template>