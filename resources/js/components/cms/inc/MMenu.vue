<template>
    <v-toolbar-items>
        <v-btn v-for="(item, index) in mItems"
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
</template>

<script>
    //import RootMenuItem from './RootMenuItem';

    export default {
        name: "MMenu",
        components: {
            //RootMenuItem,
        },
        props: {
            mItems: { type: Array, required: false, default: function () { return []; }}
        },
        watch: {
            mItems(val) {
                this.$forceUpdate();
            }
        },
        mounted() {},
        data() {
            return {

            }
        },
        methods: {
            onClick(item) {
                this.$emit('navigated', item.slug);
                //this.$vuetify.goTo(item.slug);
            }
        }
    }
</script>

<style scoped>

</style>