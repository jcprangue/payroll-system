<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Update ATM</h4>
        </strong>
      </v-card-title>
      <v-card-text class="pa-3">
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Casual Employee </label>
          <v-autocomplete
            v-model="form.employee_id"
            :items="employees"
            item-text="name"
            item-value="employee_id"
            label="Select Employee"
            outlined
            single-line
            required
          ></v-autocomplete>
        </v-flex>
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> ATM Number </label>
          <v-text-field
            v-model="form.employee_atm"
            label="Employee ATM Number"
            single-line
            outlined
          ></v-text-field>
        </v-flex>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn depressed @click="submit()" color="error">Submit</v-btn>
        <v-btn depressed @click="close">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "AddSignatory",
  props: {
    employees: Array,
    item: Object,
  },
  data: () => ({
    currentATM: "",
  }),
  computed: {
    form: function () {
      this.item.employee_atm = "";
      return this.item;
    },
  },
  mounted() {
    const self = this;
  },
  methods: {
    close() {
      this.$emit("close", true);
    },
    submit() {
      this.$emit("submit", this.form);
      this.form = [];
    },
  },
};
</script>