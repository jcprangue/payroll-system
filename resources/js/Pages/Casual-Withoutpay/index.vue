<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />

    <v-toolbar flat>
      <v-toolbar-title>
        <v-layout align-end justify-end class="mr-5">
            <div xs2 text-right class="mr-2" v-if="$can('sync_casual_info')">
                <v-btn
                color="secondary"
                @click="syncUndertime()"
                >Sync DTR</v-btn>
            </div>
            <div xs2 text-right v-if="$can('create_withoutpay')">
                <v-btn
                color="info"
                @click="modalAdd = true"
                >Add New Withoutpay</v-btn>
            </div>
        </v-layout>

       
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
        <template v-slot:item.department="{ item }">
          <label>{{ item.department.department_initial }}</label>
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip v-show="item.status == 1" color="green dark-3" dark small
            >Active</v-chip
          >

          <v-chip v-show="item.status == 2" color="red dark-3" dark small
            >Active</v-chip
          >
        </template>

        <template v-slot:item.signatory_role="{ item }">
          <label for="">{{ findSignatory(item.signatory_role) }}</label>
        </template>

        <template v-slot:item.quin="{ item }">
          <span v-if="item.quin == 1">1st Quincena</span>
          <span v-if="item.quin == 2">2nd Quincena</span>
          <span v-if="item.quin == 3">Whole Month</span>
        </template>

        <template v-slot:item.action="{ item }">
          <v-btn color="primary" dark small @click="editSignatory(item)" v-if="$can('update_withoutpay')">
            <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>
          </v-btn>

          <v-btn color="red" dark small @click="deleteSignatory(item)" v-if="$can('delete_withoutpay')">
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

    <!-- modal add -->
    <v-dialog width="600" right v-model="modalAdd" temporary absolute>
      <AddWithoutPay
        :employees="employeeList"
        @close="modalAdd = false"
        @submit="submitAdd"
      />
    </v-dialog>
    <!-- end modal add -->

    <!-- modal edit -->
    <v-dialog width="600" right v-model="modalEdit" temporary absolute>
      <UpdateWithoutPay
        :employees="employeeList"
        :item="selectedWithoutPay"
        @close="modalEdit = false"
        @submit="submitEdit"
      />
    </v-dialog>
    <!-- end modal edit -->

    <!-- delete dialog -->
    <v-dialog content-class="app-modal" v-model="modalDelete" max-width="300">
      <DeleteDialog
        :item="selectedWithoutPay"
        @close="modalDelete = false"
        @submit="confirmDelete"
      />
    </v-dialog>
    <!-- end of delete dialog -->

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
  </app-layout>
</template>

<script>
import AppLayout from "@/Partials/AdminLayout";
import SnackbarMessage from "@/Pages/SnackbarMessage";
import AddWithoutPay from "@/Pages/Casual-Withoutpay/addWithoutPay";
import UpdateWithoutPay from "@/Pages/Casual-Withoutpay/updateWithoutPay";
import FilterWithoutPay from "@/Pages/Casual-Withoutpay/filterWithoutPay";
import DeleteDialog from "@/Pages/DeleteModal";

export default {
  components: {
    AppLayout,
    SnackbarMessage,
    AddWithoutPay,
    DeleteDialog,
    UpdateWithoutPay,
    FilterWithoutPay,
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
        text: "Month",
        value: "month",
        align: "left",
      },
      {
        text: "Render Hours",
        value: "credit",
      },
      {
        text: "Undertime",
        value: "under",
        align: "left",
      },
      {
        text: "Quincena",
        value: "quin",
        align: "left",
      },

      {
        text: "Ulwop",
        value: "ulwop",
        align: "center",
      },
      {
        text: "Action",
        value: "action",
        align: "center",
      },
    ],
    snackbar: {
      status: "",
      message: "",
      show: false,
    },

    modalAdd: false,
    modalEdit: false,
    modalDelete: false,
    employeeList: [],
    selectedWithoutPay: "",
  }),
  mounted() {
    this.loadWithoutPay(this.page);
    this.loadEmployees();
  },
  methods: {
    findSignatory(id) {
      let signatoryRole = "";
      if (id == 1) {
        signatoryRole = "Department Head";
      } else if (id == 2) {
        signatoryRole = "HR Signatory";
      } else if (id == 3) {
        signatoryRole = "Treasurer Signatory";
      } else if (id == 4) {
        signatoryRole = "Accounting Signatory";
      } else if (id == 5) {
        signatoryRole = "Check Signed Signatory";
      }

      return signatoryRole;
    },
    loadWithoutPay(page) {
      this.$http
        .get(`/api/casual/withoutpay?page=${page}&search=${this.filter.search}`)
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

    submitAdd(value) {
      this.$http
        .post(`/api/casual/withoutpay`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          self.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };

          this.modalAdd = false;
          this.loadWithoutPay(1);
        })
        .catch((errorResponse) => {
          self.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },

    submitEdit(value) {
      this.$http
        .put(`/api/casual/withoutpay/${value.id}`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          self.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };

          this.modalEdit = false;
          this.loadWithoutPay(1);
        })
        .catch((errorResponse) => {
          self.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },

    setPagination(page) {
      this.page = page;
      this.loadWithoutPay(page);
    },
    deleteSignatory(item) {
      this.selectedWithoutPay = item;
      this.modalDelete = true;
    },
    editSignatory(item) {
      this.selectedWithoutPay = item;
      this.modalEdit = true;
    },
    confirmDelete(value) {
      this.$http
        .delete(`/api/casual/withoutpay/${value}`)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          self.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.modalDelete = false;
          this.loadWithoutPay();
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },

    submitFilter(filter) {
      let self = this;
      self.filter = filter;
      self.page = 1;
      self.loadingFilter = true;
      self.loadWithoutPay(1);

      setTimeout(function () {
        self.loadingFilter = false;
        self.filterDrawer = false;
      }, 1000);
    },

    resetFilter() {
      this.filter.search = "";
      this.page = 1;
      this.loadWithoutPay(1);
    },

    syncUndertime() {
        this.$http
        .get(`/api/casual-undertime/sync`)
        .then((response) => {
            this.snackbar = {
            status: "success",
            message: response.data.message,
            show: true,
            };
        })
        .catch((errorResponse) => {
        self.snackbar = {
            status: "warning",
            message: "Failed to sync portal list, Please contact MIS for support",
            show: true,
        };
        });
    },
  },
};
</script>
