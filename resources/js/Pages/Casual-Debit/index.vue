<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />

    <v-toolbar flat>
      <v-toolbar-title> Casual Debit Salary </v-toolbar-title>
      <v-layout align-end justify-end class="mr-20">
        <div xs2 text-right v-if="$can('create_debit')">
          <v-btn color="info" @click="addSalary = true">Add Debit Salary</v-btn>
        </div>
      </v-layout>
    </v-toolbar>

    <div class="pa-5">
      <v-layout row wrap>
        <v-flex xs6 sm6 md6 class="mt-5" style="border-right: 3px solid grey">
          <v-flex xs6 sm6 md12>
            <v-row no-gutters>
              <v-col>
                <label>Search for Batch Number</label>
                <v-autocomplete
                  v-model="form.batch"
                  :items="batchList"
                  item-text="batch"
                  label="Select Batch"
                  outlined
                  single-line
                  required
                ></v-autocomplete>
              </v-col>
              <v-col class="text-right px-5">
                <label>Total Amount</label>
                <h1>{{ total.toLocaleString() }}</h1>
              </v-col>
            </v-row>
          </v-flex>
          <v-data-table
            class="mr-5"
            :headers="headers"
            :items="salaryList"
            :hide-default-footer="true"
            item-key="id"
          >
            <template v-slot:item.action="{ item }">
              <v-btn color="red" dark small @click="deletePayroll(item)">
                <v-icon class="white--text" :icon="['fas', 'trash']"></v-icon>
              </v-btn>
            </template>
          </v-data-table>

          <div class="text-xs-center py-3">
            <v-pagination
              v-model="page"
              :length="pageLength"
              @input="setPagination"
              :total-visible="8"
            ></v-pagination>
          </div>
        </v-flex>
      </v-layout>
    </div>

    <!-- delete dialog -->
    <v-dialog content-class="app-modal" v-model="deleteModal" max-width="300">
      <DeleteDialog
        :item="selectedDebit"
        @close="deleteModal = false"
        @submit="confirmDelete"
      />
    </v-dialog>
    <!-- end of delete dialog -->

    <!-- set dialog -->
    <v-dialog content-class="app-modal" v-model="addSalary" max-width="450">
      <SalaryDialog
        :employeeList="employees"
        @close="addSalary = false"
        @submit="submitAddSalary"
      />
    </v-dialog>
    <!-- end modal -->
  </app-layout>
</template>

<script>
import AppLayout from "@/Partials/AdminLayout";
import SnackbarMessage from "@/Pages/SnackbarMessage";
import DeleteDialog from "@/Pages/DeleteModal";
import SalaryDialog from "@/Pages/Casual-Debit/manualAddSalary";

export default {
  components: {
    AppLayout,
    SnackbarMessage,
    DeleteDialog,
    SalaryDialog,
  },
  data: () => ({
    addSalary: false,
    deleteModal: false,
    selectedDebit: [],
    batchList: [],
    salaryList: [],
    employees: [],
    snackbar: {
      status: "",
      message: "",
      show: false,
    },
    form: {
      batch: "",
    },
    headers: [
      {
        text: "Employee Name",
        align: "left",
        value: "casual_employee.name",
      },
      {
        text: "Amount",
        align: "left",
        value: "amount",
      },
      {
        text: "Month",
        align: "left",
        value: "month",
      },
      {
        text: "Action",
        align: "left",
        value: "action",
      },
    ],
    search: "",
    pageLength: 1,
    page: 1,
    total: 0,
  }),
  mounted() {
    this.loadBatch();
    this.loadEmployeees();
  },
  watch: {
    "form.batch": function (val) {
      this.loadSalary(val, 1);
    },
  },

  methods: {
    capitalizeFirstLetter(string) {
      return string.charAt(0).toUpperCase() + string.slice(1);
    },
    loadSalary(batch, page) {
      this.$http
        .get(`/api/casual/debit/generateBatch/${batch}?page=${page}`)
        .then((response) => {
          this.salaryList = response.data.data;
          this.salaryList.forEach((e) => {
            this.total = parseFloat(this.total) + parseFloat(e.amount);
            console.log(this.total);
          });
          this.pageLength = response.data.last_page;
        });
    },

    loadBatch() {
      this.$http.get(`/api/casual/debit/batch`).then((response) => {
        this.batchList = response.data;
      });
    },
    loadEmployeees() {
      this.$http.get(`/api/employees/list`).then((response) => {
        this.employees = response.data;
      });
    },

    setPagination(page) {
      this.page = page;
      this.loadBatch(page);
    },

    deletePayroll(item) {
      this.selectedDebit = item;
      this.deleteModal = true;
    },
    confirmDelete(value) {
      this.$http
        .delete(`/api/casual/debit/${value}`)
        .then((response) => {
          let mode = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: mode,
            message: response.data.message,
            show: true,
          };

          this.deleteModal = false;
          this.loadSalary(this.form.batch);
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message:
              "Failed to delete debit value, Please contact MIS for support",
            show: true,
          };
        });
    },

    submitAddSalary(value) {
      this.$http
        .post(`/api/casual/debit`, value)
        .then((response) => {
          let mode = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: mode,
            message: response.data.message,
            show: true,
          };

          this.addSalary = false;
          if (this.form.batch != null) {
            this.loadSalary(this.form.batch, this.page);
          }
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message:
              "Failed to delete debit value, Please contact MIS for support",
            show: true,
          };
        });
    },
  },
};
</script>
