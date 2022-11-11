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
      <label>Employee Search</label>
      <v-text-field
        v-model="filters.search"
        outlined
        single-line
      ></v-text-field>
    </v-flex>

    <v-flex xs12 text-left class="px-4 pt-3">
      <label>Month</label>
      <v-menu
        ref="menu"
        v-model="month"
        :close-on-content-click="false"
        :return-value.sync="filters.month"
        transition="scale-transition"
        offset-y
        max-width="290px"
        min-width="auto"
      >
        <template v-slot:activator="{ on, attrs }">
          <v-text-field
            v-model="filters.month"
            label="Picker in menu"
            readonly
            v-bind="attrs"
            outlined
            single-line
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker v-model="filters.month" type="month" no-title scrollable>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="month = false"> Cancel </v-btn>
          <v-btn text color="primary" @click="$refs.menu.save(filters.month)">
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
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
      month: false,
      filters: {
        search: "",
        month: "",
      },
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
      this.filters.search = "";
      this.filters.month = "";
      this.$emit("resetFilter", this.filters);
      this.$emit("close", true);
    },
  },
};
</script>