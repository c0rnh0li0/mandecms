<template>
    <v-toolbar color="amber accent-3" app flat scrollOffScreen extended extension-height="3">
        <v-toolbar-side-icon class="hidden-md-and-up" @click="toggleDrawer"></v-toolbar-side-icon>
        <v-img :src="'/storage/img/' + $store.state.settings.site_logo"
               contain
               height="48"
               width="48"
               max-width="48"
               @click="$vuetify.goTo(0)">
        </v-img>
        <v-toolbar-title class="py-2 white--text">{{ $store.state.settings.site_name }}</v-toolbar-title>
        <v-spacer></v-spacer>
        <v-toolbar-items>
            <v-btn v-for="(item, index) in $store.state.menuitems"
                   :key="item.id"
                   :to="item.slug"
                   class="ml-0 hidden-sm-and-down white--text"
                   flat
                   slot="activator"
                   @click="onClick($event, item)">
                {{ item.name }}
            </v-btn>

            <!-- <v-menu open-on-hover>
                <root-menu-item v-for="(item, index) in mItems" :key="item.id" :item="item" @navigated="navigated"></root-menu-item>
            </v-menu> -->
        </v-toolbar-items>
        <!-- <m-menu :m-items="$store.state.menuitems" @navigated="navigated"></m-menu> -->
        <v-progress-linear v-show="progressLoading" app fixed :indeterminate="true" slot="extension" height="2" class="ma-0" color="red darken-4"></v-progress-linear>
    </v-toolbar>
</template>

<script>
    import MMenu from './MMenu';

    // Utilities
    import {
        mapMutations
    } from 'vuex';

    export default {
        components: {
            MMenu
        },
        props: {
            progressLoading: { type: Boolean, required: false, default: function () { return false; }},
        },
        mounted() {},
        data() {
            return {
                //progressLoading: false,
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
            }
        }
    }
</script>