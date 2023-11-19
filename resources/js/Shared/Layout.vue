<template>

  <sidenav
    :custom_class="color"
    class="fixed-start"
    v-if="showSidenav"
  />
  <main class="main-content position-relative max-height-vh-100 h-100 overflow-x-hidden">
    <!-- nav -->
    <navbar
      :class="[isNavFixed ? navbarFixed : '', isAbsolute ? absolute : '']"
      :color="isAbsolute ? 'text-white opacity-8' : ''"
      :minNav="navbarMinimize"
      v-if="showNavbar"
    />
    <slot/>
    <app-footer v-show="showFooter" />
 
  </main>
 
</template>
<style>
  .navbar-nav {
    overflow: hidden;
  }
</style>
<script>
import Sidenav from "../examples/Sidenav";
import Navbar from "@/examples/Navbars/Navbar.vue";
import AppFooter from "@/examples/Footer.vue";
import { mapMutations, mapState } from "vuex";

export default {
  name: "App",
  components: {
    Sidenav,
    Navbar,
    AppFooter
  },
  methods: {
    ...mapMutations(["toggleConfigurator", "navbarMinimize"]),

    
  },
  computed: {
    ...mapState([
      "isRTL",
      "color",
      "isAbsolute",
      "isNavFixed",
      "navbarFixed",
      "absolute",
      "showSidenav",
      "showNavbar",
      "showFooter",
      "hideConfigButton"
    ])
  },

  
};
</script>

