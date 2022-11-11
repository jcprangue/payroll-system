<template>
  <div>
    <v-toolbar flat>
      <v-toolbar-title>Filter</v-toolbar-title>
      <v-spacer></v-spacer>
      <div>
        <v-btn @click="closeDrawer" text>
          <v-icon class="black--text" :icon="['fas', 'times']"></v-icon>
        </v-btn>
      </div>
    </v-toolbar>

    <v-flex xs12 text-left class="px-4">
      <label>Signatory Search</label>
      <v-text-field
        v-model="filters.search"
        outlined
        single-line
      ></v-text-field>
    </v-flex>

    <v-flex xs12 class="text-left px-4">
      <v-btn
        :loading="loading"
        :disabled="loading"
        @click="submit"
        color="success"
        >Apply</v-btn
      >
      <v-btn @click="reset" color>Reset</v-btn>
    </v-flex>
  </div>
</template>

<script>
export default {
  name: "SignatoryFilter",
  props: {
    loading: Boolean,
  },
  data() {
    return {
      payrollMonth: false,
      filters: {
        quincena: "",
        search: "",
        month: "",
      },
      quincena: [
        {
          text: "1st Quincena",
          value: 1,
        },
        {
          text: "2nd Quincena",
          value: 2,
        },
      ],
    };
  },
  methods: {
    closeDrawer() {
      this.$emit("close", true);
    },
    submit() {
      this.$emit("filter", this.filters);
    },
    reset() {
      this.filters.quincena = "";
      this.filters.search = "";
      this.filters.month = "";
      this.$emit("resetFilter", this.filters);
      this.$emit("close", true);
    },
  },
};
</script>