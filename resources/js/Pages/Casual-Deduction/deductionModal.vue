<template>
  <div>
    <v-card>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>New Deduction</h4>
        </strong>
      </v-card-title>
      <v-card-text>
        <v-row class="my-4 mx-2">
          <v-flex xs12 class="text-left">
            <label class="font-weight-bold">
              Deduction
              <span class="red--text">*</span>
            </label>
            <v-select
              v-model="form.deduction"
              :items="deductionList"
              item-text="deduction_name"
              item-value="id"
              label="Select Payroll Period"
              outlined
              single-line
              required
            ></v-select>
          </v-flex>

          <v-flex xs12 class="text-left">
            <label class="font-weight-bold"> Amount </label>
            <v-text-field
              v-model="form.amount"
              label="Deduction Amount"
              single-line
              outlined
              autocomplete="false"
              hint="Amount of Deduction"
              required
            ></v-text-field>
          </v-flex>

          <v-flex xs12 class="text-left">
            <label class="font-weight-bold">
              Month Started
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
  name: "CreateCasualGroup",
  props: {
    deductionList: Array,
  },
  data() {
    return {
      form: {
        deduction: "",
        amount: "",
        date: "",
      },

      payrollMonth: false,
    };
  },
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