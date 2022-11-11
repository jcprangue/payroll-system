<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Add new ATM</h4>
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
  },
  data: () => ({
    form: {
      employee_id: "",
      employee_atm: "",
      isLock: 1,
    },
  }),
  mounted() {
    const self = this;
  },
  methods: {
    close() {
      this.$emit("close", true);
    },
    submit() {
      this.$emit("submit", this.form);
      this.form.employee_id = "";
      this.form.employee_atm = "";
      this.form.isLock = "";
    },
  },
};
</script>