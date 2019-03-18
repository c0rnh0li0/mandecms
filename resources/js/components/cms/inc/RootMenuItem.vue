<template>
    <span>
        <v-btn
                :key="item.name"
                :to="item.slug"
                class="ml-0 hidden-sm-and-down white--text"
                flat
                slot="activator"
                @click="onClick($event, item)">
            {{ item.name }}
        </v-btn>

        <v-list v-if="typeof item.children != 'undefined' && item.children.length > 0">
            <v-list-tile v-for="sitem in item.children" :key="sitem.id" @click="">
                <router-link tag="v-list-tile-title"
                             :to="sitem.slug"
                             @click="onClick($event, sitem)">
                    {{ sitem.name }}
                </router-link>
            </v-list-tile>
        </v-list>
    </span>

</template>

<script>
    export default {
        name: "RootMenuItem",
        props: {
            item: { type: Object, required: false, default: function () { return {}; }}
        },
        mounted () {
            console.log(this.item)
        },
        methods: {
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

<style scoped>

</style>