<template>
    <v-toolbar app flat scrollOffScreen extended extension-height="3">
        <v-toolbar-side-icon class="hidden-md-and-up" @click="toggleDrawer"/>
        <v-container mx-auto py-0>
            <v-layout>
                <v-img :src="'/storage/img/' + $store.state.settings.site_logo"
                       contain
                       height="48"
                       width="48"
                       max-width="48"
                       @click="$vuetify.goTo(0)">
                </v-img>
                <v-toolbar-title class="py-2">{{ $store.state.settings.site_name }}</v-toolbar-title>
                <v-spacer></v-spacer>
                <v-btn v-for="(item, i) in $store.state.menuitems"
                       :key="i"
                       :to="item.slug"
                       class="ml-0 hidden-sm-and-down"
                       flat
                       @click="onClick($event, item)">
                    {{ item.name }}
                </v-btn>
            </v-layout>
        </v-container>
        <v-progress-linear v-show="progressLoading" app fixed :indeterminate="true" slot="extension" height="2" class="ma-0"></v-progress-linear>
    </v-toolbar>
</template>

<script>
    // Utilities
    import {
        mapMutations
    } from 'vuex';

    export default {
        props: {
            progressLoading: { type: Boolean, required: false, default: function () { return false; }},
        },
        data() {
            return {
                progressLoading: false,
            }
        },
        methods: {
            ...mapMutations(['toggleDrawer']),
            onClick (e, item) {
                e.stopPropagation();
                if (item.to || !item.slug) {
                    return;
                }

                this.$emit('navigated', item.slug);
                //this.$vuetify.goTo(item.slug);
            }
        }
    }
</script>