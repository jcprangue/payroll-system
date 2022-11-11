<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />

    <v-toolbar flat>
      <v-toolbar-title>
        <div >
          Casual Employee ATM
          <v-btn v-if="$can('create_atm')" color="info" @click="modalAdd = true">Add New ATM</v-btn>
        </div>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-layout align-end justify-end class="mr-5">
        <div xs2 text-right>
          <v-btn color="info" @click.stop="filterDrawer = !filterDrawer"
            >Filters</v-btn
          >
        </div>
      </v-layout>
    </v-toolbar>

    <div class="pa-5">
      <v-data-table
        :headers="headers"
        :items="withoutPayList"
        :hide-default-footer="true"
        item-key="id"
      >
        <template v-slot:item.isLock="{ item }">
          <v-btn
            v-if="$can('lock_atm')"
            color="red"
            dark
            small
            v-show="item.isLock"
            @click="lockNotice(item, false)"
          >
            <v-icon class="white--text" :icon="['fas', 'lock']"></v-icon>
          </v-btn>
          <v-btn
            v-if="$can('unlock_atm')"
            color="primary"
            dark
            small
            v-show="!item.isLock"
            @click="lockNotice(item, true)"
          >
            <v-icon class="white--text" :icon="['fas', 'unlock']"></v-icon>
          </v-btn>

          <v-btn
            v-if="$can('update_atm')"
            color="green"
            dark
            small
            v-show="!item.isLock"
            @click="editATM(item)"
          >
            <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>
          </v-btn>

          <v-btn
            v-if="$can('delete_atm')"
            color="red"
            dark
            small
            v-show="!item.isLock"
            @click="deleteATM(item)"
          >
            <v-icon class="white--text" :icon="['fas', 'trash']"></v-icon>
          </v-btn>
        </template>
      </v-data-table>
      <div class="text-xs-center py-3">
        <v-pagination
          v-model="page"
          :length="pageLength"
          @input="setPagination"
          :total-visible="5"
        ></v-pagination>
      </div>
    </div>

    <!-- filter drawer -->
    <v-navigation-drawer
      width="350"
      right
      v-model="filterDrawer"
      hide-overlay
      temporary
      absolute
    >
      <FilterWithoutPay
        @filter="submitFilter"
        @resetFilter="resetFilter"
        @close="filterDrawer = false"
        :loading="loadingFilter"
      />
    </v-navigation-drawer>
    <!-- end filter drawer -->

    <!-- modal edit -->
    <v-dialog width="600" right v-model="modalUpdate" temporary absolute>
      <UpdateATM
        :employees="employeeList"
        :item="selectedEmployee"
        @close="modalUpdate = false"
        @submit="submitUpdate"
      />
    </v-dialog>
    <!-- end modal edit -->

    <!-- modal add -->
    <v-dialog width="600" right v-model="modalAdd" temporary absolute>
      <AddATM
        :employees="employeeList"
        @close="modalAdd = false"
        @submit="submitAdd"
      />
    </v-dialog>
    <!-- end modal add -->

    <!-- delete dialog -->
    <v-dialog content-class="app-modal" v-model="modalDelete" max-width="300">
      <DeleteDialog
        :item="selectedEmployee"
        @close="modalDelete = false"
        @submit="confirmDelete"
      />
    </v-dialog>
    <!-- end of delete dialog -->
  </app-layout>
</template>

<script>
import AppLayout from "@/Partials/AdminLayout";
import SnackbarMessage from "@/Pages/SnackbarMessage";
import FilterWithoutPay from "@/Pages/Casual-ATM/filterATM";
import UpdateATM from "@/Pages/Casual-ATM/updateATM";
import AddATM from "@/Pages/Casual-ATM/addATM";
import DeleteDialog from "@/Pages/DeleteModal";

export default {
  components: {
    AppLayout,
    SnackbarMessage,
    FilterWithoutPay,
    DeleteDialog,
    UpdateATM,
    AddATM,
  },
  data: () => ({
    pageLength: 1,
    page: 1,
    method: "",
    withoutPayList: [],
    filterDrawer: false,
    loadingFilter: false,
    filter: {
      search: "",
      month: "",
    },
    headers: [
      {
        text: "Employee Name",
        align: "left",
        value: "employees.name",
      },
      {
        text: "Account Number",
        value: "employee_atm",
        align: "left",
      },
      {
        text: "Locked",
        value: "isLock",
      },
    ],
    snackbar: {
      status: "",
      message: "",
      show: false,
    },

    modalUpdate: false,
    modalAdd: false,
    modalDelete: false,
    employeeList: [],
    selectedEmployee: "",
  }),
  mounted() {
    this.loadATM(this.page);
    this.loadEmployees();
  },
  methods: {
    loadATM(page) {
      this.$http
        .get(`/api/casual/atm?page=${page}&search=${this.filter.search}`)
        .then((response) => {
          this.withoutPayList = response.data.data;
          this.pageLength = response.data.last_page;
        });
    },
    loadEmployees() {
      this.$http.get(`/api/employees/list`).then((response) => {
        this.employeeList = response.data;
      });
    },

    setPagination(page) {
      this.page = page;
      this.loadATM(page);
    },

    submitFilter(filter) {
      let self = this;
      self.filter = filter;
      self.page = 1;
      self.loadingFilter = true;
      self.loadATM(1);

      setTimeout(function () {
        self.loadingFilter = false;
        self.filterDrawer = false;
      }, 1000);
    },

    resetFilter() {
      this.filter.search = "";
      this.page = 1;
      this.loadATM(1);
    },

    lockNotice(item, lockStatus) {
      this.$http
        .put(`/api/casual/atm/unlock/${item.id}`, { lock: lockStatus })
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.loadATM();
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to unlock ATM, Please contact MIS for support",
            show: true,
          };
        });
    },

    editATM(item) {
      this.selectedEmployee = item;
      this.modalUpdate = true;
    },
    deleteATM(item) {
      this.selectedEmployee = item;
      this.modalDelete = true;
    },
    submitUpdate(value) {
      this.$http
        .put(`/api/casual/atm/${value.id}`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.loadATM();
          this.modalUpdate = false;
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to unlock ATM, Please contact MIS for support",
            show: true,
          };
        });
    },
    submitAdd(value) {
      this.$http
        .post(`/api/casual/atm`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.loadATM();
          this.modalAdd = false;
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to unlock ATM, Please contact MIS for support",
            show: true,
          };
        });
    },
    confirmDelete(value) {
      this.$http
        .delete(`/api/casual/atm/${value}`)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.modalDelete = false;
          this.loadATM();
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },
  },
};
</script>
