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

    <v-flex xs12 text-left class="px-4 pt-3">
      <label>Month</label>
      <v-menu
        ref="menu"
        v-model="payrollMonth"
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
            clearable
            single-line
            v-on="on"
          ></v-text-field>
        </template>
        <v-date-picker v-model="filters.month" type="month" no-title scrollable>
          <v-spacer></v-spacer>
          <v-btn text color="primary" @click="payrollMonth = false">
            Cancel
          </v-btn>
          <v-btn text color="primary" @click="$refs.menu.save(filters.month)">
            OK
          </v-btn>
        </v-date-picker>
      </v-menu>
    </v-flex>

    <v-flex xs12 text-left class="px-4">
      <label>Quincena</label>
      <v-select
        v-model="filters.quincena"
        :items="quincena"
        outlined
        single-line
        clearable
      ></v-select>
    </v-flex>

    <v-flex xs12 text-left class="px-4">
      <label>Payroll Name</label>
      <v-text-field
        v-model="filters.search"
        outlined
        single-line
      ></v-text-field>
    </v-flex>

    <v-flex xs12 text-left class="px-4">
      <label>Control Number</label>
      <v-text-field
        v-model="filters.control_number"
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
import moment from "moment";

export default {
  name: "ElearningCatalogFilter",
  props: {
    loading: Boolean,
  },
  data() {
    return {
      payrollMonth: false,
      filters: {
        quincena: "",//moment().format('D') >= 16 ? 1 : 2,
        search: "",
        control_number: "",
        month: moment().subtract(1, 'months').format("YYYY-MM"),
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
        {
          text: "Whole Month",
          value: 3,
        },
      ],
    };
  },
  mounted() {
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
      this.filters.control_number = "";
      this.$emit("resetFilter", this.filters);
      this.$emit("close", true);
    },
  },
};
</script>