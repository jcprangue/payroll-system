<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Add new withoutpay</h4>
        </strong>
      </v-card-title>
      <v-card-text class="pa-3">
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Status </label>
          <v-autocomplete
            v-model="form.casual_employee_id"
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
          <label class="font-weight-bold"> Credit </label>
          <v-text-field
            v-model="form.credit"
            label="Employee Credit Time"
            single-line
            outlined
            v-mask="'##:##:##'"
          ></v-text-field>
        </v-flex>
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Under </label>
          <v-text-field
            v-model="form.under"
            label="Employee Undertime"
            single-line
            v-mask="'##:##:##'"
            outlined
            required
          ></v-text-field>
        </v-flex>
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Ulwop </label>
          <v-text-field
            v-model="form.ulwop"
            label="Employee Ulwop"
            single-line
            type="number"
            outlined
            required
          ></v-text-field>
        </v-flex>

        <v-flex xs12 text-left class="text-left">
          <label>Month</label>
          <v-menu
            ref="menu"
            v-model="month"
            :close-on-content-click="false"
            :return-value.sync="form.month"
            transition="scale-transition"
            offset-y
            max-width="290px"
            min-width="auto"
          >
            <template v-slot:activator="{ on, attrs }">
              <v-text-field
                v-model="form.month"
                label="Picker in menu"
                readonly
                v-bind="attrs"
                outlined
                single-line
                v-on="on"
              ></v-text-field>
            </template>
            <v-date-picker
              v-model="form.month"
              type="month"
              no-title
              scrollable
            >
              <v-spacer></v-spacer>
              <v-btn text color="primary" @click="month = false">
                Cancel
              </v-btn>
              <v-btn text color="primary" @click="$refs.menu.save(form.month)">
                OK
              </v-btn>
            </v-date-picker>
          </v-menu>
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
      casual_employee_id: "",
      month: "",
      credit: "",
      under: "",
      ulwop: "",
    },
    month: false,
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
      this.form.department_id = "";
      this.form.department_head = "";
      this.form.department_head_position = "";
      this.form.signatory_role = "";
      this.form.status = "";
    },
  },
};
</script>