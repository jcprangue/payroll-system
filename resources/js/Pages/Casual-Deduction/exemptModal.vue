<template>
  <div>
    <v-card>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Add Employee Exempt</h4>
        </strong>
      </v-card-title>
      <v-card-text>
        <v-row class="my-4 mx-2">
          <v-flex xs12 class="text-left">
            <label class="font-weight-bold">
              Deduction
              <span class="red--text">*</span>
            </label>
            <v-autocomplete
              v-model="form.deduction"
              :items="deductions"
              item-text="deductions.deduction_nick"
              item-value="id"
              label="Select Deduction"
              outlined
              single-line
              required
            ></v-autocomplete>
          </v-flex>

          <v-flex xs12 class="text-left">
            <label class="font-weight-bold">
              Employee
              <span class="red--text">*</span>
            </label>
            <v-autocomplete
              v-model="form.employee"
              :items="employees"
              item-text="name"
              item-value="employee_id"
              label="Select Deduction"
              outlined
              single-line
              required
            >
            </v-autocomplete>
          </v-flex>

          <v-flex xs12 class="text-left">
            <label class="font-weight-bold">
              Month Exempt
              <span class="red--text">*</span>
            </label>
            <v-menu
              ref="menu"
              v-model="payrollMonth"
              :close-on-content-click="false"
              :return-value.sync="form.date"
              transition="scale-transition"
              offset-y
              max-width="290px"
              min-width="auto"
            >
              <template v-slot:activator="{ on, attrs }">
                <v-text-field
                  v-model="form.date"
                  label="Picker in menu"
                  readonly
                  v-bind="attrs"
                  outlined
                  single-line
                  v-on="on"
                ></v-text-field>
              </template>
              <v-date-picker
                v-model="form.date"
                type="month"
                no-title
                scrollable
              >
                <v-spacer></v-spacer>
                <v-btn text color="primary" @click="payrollMonth = false">
                  Cancel
                </v-btn>
                <v-btn text color="primary" @click="$refs.menu.save(form.date)">
                  OK
                </v-btn>
              </v-date-picker>
            </v-menu>
          </v-flex>
        </v-row>
      </v-card-text>
      <v-card-actions>
        <v-spacer></v-spacer>
        <v-btn depressed @click="submit()" color="error">Save</v-btn>
        <v-btn depressed @click="close">Cancel</v-btn>
      </v-card-actions>
    </v-card>
  </div>
</template>

<script>
export default {
  name: "EmployeeExempt",
  props: {
    deductions: Array,
    employees: Array,
  },
  data() {
    return {
      form: {
        deduction: "",
        employee: "",
        date: "",
      },
      payrollMonth: false,
    };
  },
  mounted() {},
  methods: {
    close() {
      this.$emit("close", true);
    },
    submit() {
      this.$emit("submit", this.form);
    },
  },
};
</script>