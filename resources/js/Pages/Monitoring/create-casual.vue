<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />
    <v-toolbar flat>
      <v-toolbar-title>
        <div>
          <v-btn color="info" text href="/admin/monitoring/casual">
            <v-icon
              class="black--text"
              :icon="['fas', 'chevron-left']"
            ></v-icon>
          </v-btn>
          Return to Casual Monitoring
        </div>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
    </v-toolbar>
    <div class="pa-5">
      <v-layout row wrap>
        <v-flex xs12 sm8 md8>
          <v-form ref="form" lazy-validation>
            <v-flex xs12>
              <v-card class="text-left pa-5">
                <v-flex xs12 class="text-left">
                  <v-toolbar-title class="app-title">
                    <v-icon
                      class="black--text"
                      :icon="['fas', 'info-circle']"
                    ></v-icon>
                    General Information
                  </v-toolbar-title>
                  <v-flex xs12>
                    <v-divider class="mb-3" />
                  </v-flex>
                </v-flex>

                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold">
                    Control Number
                    <span class="red--text">*</span>
                  </label>
                  <v-autocomplete
                    v-model="form.data"
                    :items="payrollGroupsControlNumber"
                    item-text="name"
                    return-object
                    :rules="rules"
                    label="Select Control Number"
                    outlined
                    single-line
                    required
                  ></v-autocomplete>
                </v-flex>

                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold">
                    Payroll Group
                    <span class="red--text">*</span>
                  </label>
                  <v-autocomplete
                    v-model="form.payroll_group"
                    :items="payrollGroups"
                    item-text="group_name"
                    item-value="id"
                    :rules="rules"
                    label="Select Payroll Group"
                    outlined
                    single-line
                    required

                  ></v-autocomplete>
                </v-flex>

              


                

                

                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold">
                    Payroll Period
                    <span class="red--text">*</span>
                  </label>
                  <v-select
                    v-model="form.period"
                    :items="period"
                    item-text="name"
                    item-value="value"
                    :rules="rules"
                    label="Select Payroll Period"
                    outlined
                    single-line
                    required

                  ></v-select>
                </v-flex>

                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold"> Amount </label>
                  <v-text-field
                    v-model="form.amount"
                    label="Amount"
                    single-line
                    outlined
                  ></v-text-field>
                </v-flex>

               
                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold">
                    Month
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
                        v-bind="attrs"
                        outlined
                        :rules="rules"
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
                      <v-btn
                        text
                        color="primary"
                        @click="$refs.menu.save(form.date)"
                      >
                        OK
                      </v-btn>
                    </v-date-picker>
                  </v-menu>
                </v-flex>

                <v-flex xs8 class="text-left">
                  <label class="font-weight-bold">
                    Status
                    <span class="red--text">*</span>
                  </label>
                  <v-select
                    v-model="form.status"
                    :items="payrollStatus"
                    :rules="rules"
                    item-text="status"
                    item-value="id"
                    label="Select Payroll Status"
                    outlined
                    single-line
                    required
                  ></v-select>
                </v-flex>

                <v-flex xs12 class="text-left mt-5">
                  <label class="font-weight-bold">
                    Remarks
                    <span class="red--text">*</span>
                  </label>
                  <ckeditor v-model="form.remarks"></ckeditor>
                </v-flex>
              </v-card>
            </v-flex>

            <div class="my-4">
              <v-btn
                :loading="loading"
                :disabled="loading"
                @click="createPayroll"
                color="green"
                dark
                >Create</v-btn
              >
              <v-btn href="/admin/monitoring/casual" color="red" dark
                >Cancel</v-btn
              >
            </div>
          </v-form>
        </v-flex>
      </v-layout>
    </div>
  </app-layout>
</template>

<script>
import AppLayout from "@/Partials/AdminLayout";
import SnackbarMessage from "@/Pages/SnackbarMessage";
export default {
  components: {
    AppLayout,
    SnackbarMessage,
  },
  data: () => ({
    loading: false,
    payrollMonth: false,
    rules: [(v) => !!v || "This field is required"],
    period: [
      {
        name: "1st Quincena",
        value: 1,
      },
      {
        name: "2nd Quincena",
        value: 2,
      },
      {
        name: "Whole Month",
        value: 3,
      },
    ],

    form: {
      payroll_group: "",
      status: 1,
      period: "",
      amount: "",
      control_number: "",
      data:{},
      date: "",
      remarks: "",
    },
    snackbar: {
      status: "",
      message: "",
      show: false,
    },
    payrollStatus: [],
    payrollGroups: [],
    payrollGroupsControlNumber: [],
  }),
  watch: {
    'form.data':function(val){
      this.form.payroll_group = val.payroll_group_id
      this.form.period = val.payroll_period
      this.form.amount = val.amount
      this.form.control_number = val.control_number
      this.form.date = val.month
    }
  },
  mounted() {
    this.loadStatus();
    this.loadPayrollGroups();
    this.loadPayrollGroupsControlNumber();
  },
  methods: {
    loadStatus() {
      const self = this;
      this.$http.get(`/api/monitoring/status`).then((response) => {
        self.payrollStatus = response.data;
        // response.data.map(function(value, key) {
        //     self.payrollStatus.push({
        //         name:value,
        //         id: key
        //     });
        // });
      });
    },
    loadPayrollGroupsControlNumber() {
      this.$http.get(`/api/monitoring/controls`).then((response) => {
        this.payrollGroupsControlNumber = response.data;
      });
    },
    loadPayrollGroups() {
      this.$http.get(`/api/monitoring/groups`).then((response) => {
        this.payrollGroups = response.data;
      });
    },

    createPayroll() {
      const self = this;
      self.loading = true;

      let formValidation = this.$refs.form.validate();

      if (formValidation) {
        self.$http
          .post(`/api/monitoring/casual/store`, self.form)
          .then((response) => {
            // this.payrollGroupsControlNumber = response.data
            if (response.data.error == null) {
              self.snackbar = {
                status: "success",
                message: response.data.message,
                show: true,
              };

              setTimeout(function () {
                window.location.href = `/admin/monitoring/casual`;
              }, 1500);
            } else {
              self.snackbar = {
                status: "warning",
                message: response.data.message,
                show: true,
              };
            }
          })
          .catch((errorResponse) => {
            self.snackbar = {
              status: "warning",
              message:
                "Failed to create payroll, Please contact MIS for support",
              show: true,
            };
          });
      }

      self.loading = false;
    },
  },
};
</script>
