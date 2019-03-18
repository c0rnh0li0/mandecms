<template>
  <v-navigation-drawer
    v-model="drawer"
    class="amber accent-3"
    app
    temporary>
    <v-list>
      <v-list-tile
              v-for="(link, i) in menuitems"
              :key="i"
              :to="link.slug"
              :href="link.slug"
              @click="onClick($event, link)"
      >
        <v-list-tile-title class="white--text" v-text="link.name" />
      </v-list-tile>
      <!-- <router-link tag="v-list-tile" class="white--text"
                   v-for="(item, i) in $store.state.menuitems"
                   :key="i"
                   :href="item.slug"
                   @click="onClick($event, item)">
        {{ item.name }}
      </router-link> -->
    </v-list>
  </v-navigation-drawer>
</template>

<script>
  // Utilities
  import {
    mapGetters,
    mapMutations
  } from 'vuex'

  export default {
    name: 'CoreDrawer',

    computed: {
      ...mapGetters(['menuitems']),
      drawer: {
        get () {
          return this.$store.state.drawer
        },
        set (val) {
          this.setDrawer(val)
        }
      }
    },

    methods: {
      ...mapMutations(['setDrawer']),
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
