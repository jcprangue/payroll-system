<template>
  <div>
    <v-snackbar v-model="snackbar.show" :color="snackbar.status" top>
      {{ snackbar.message }}

      <v-btn dark text @click="snackbar.show = false" small>Close</v-btn>
    </v-snackbar>
  </div>
</template>

<script>
export default {
  name: "SnackbarMessage",
  props: {
    snackbar: Object
  }
};
</script>