<template>
  <v-app style="position: relative">
    <v-img
        :src="logo"
        max-height="150"
        max-width="150"
        style="margin: 0 auto;margin-top:40px;"
    ></v-img>
    <v-main class="grey lighten-5 py-5">
      <slot />
    </v-main>
  </v-app>
</template>

<script>
import PublicFooter from "./PublicFooter";
import logo from "@/assets/images/logo.png";


export default {
  components: {
    PublicFooter,
  },
  data: () => ({
    items: ["Foo", "Bar", "Fizz", "Buzz"],
    logo
  }),
  methods: {
    logout() {
      this.$inertia.post(route("logout"));
    },
  },
};
</script>

<style>
</style>