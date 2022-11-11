<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />

    <v-toolbar flat>
      <v-toolbar-title>
       
        <div v-if="$can('create_signatory')">
          <v-btn color="info" @click="modalAdd = true">Add New Signatory</v-btn>
        </div>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-layout align-end justify-end class="mr-5">
        <div xs2 text-right class="mr-2" v-if="$can('sync_account')">
            <v-btn
            color="secondary"
            @click="updateAccount()"
            >Sync Account</v-btn>
        </div>
        <div xs2 text-right class="mr-2" v-if="$can('sync_signatory')">
            <v-btn
            color="secondary"
            @click="updateSignatory()"
            >Sync Signatory</v-btn>
        </div>
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
        :items="signatoryList"
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

        <template v-slot:item.action="{ item }">
          <v-btn color="primary" dark small @click="editSignatory(item)" v-if="$can('update_signatory')">
            <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>
          </v-btn>

          <v-btn color="red" dark small @click="deleteSignatory(item)" v-if="$can('delete_signatory')">
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
      <AddSignatory
        :departments="departmentList"
        @close="modalAdd = false"
        @submit="submitAdd"
      />
    </v-dialog>
    <!-- end modal add -->

    <!-- modal edit -->
    <v-dialog width="600" right v-model="modalEdit" temporary absolute>
      <UpdateSignatory
        :departments="departmentList"
        :item="selectedSignatory"
        @close="modalEdit = false"
        @submit="submitEdit"
      />
    </v-dialog>
    <!-- end modal edit -->

    <!-- delete dialog -->
    <v-dialog content-class="app-modal" v-model="modalDelete" max-width="300">
      <DeleteDialog
        :item="selectedSignatory"
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
      <FilterSignatory
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
import AddSignatory from "@/Pages/Signatory/addSignatory";
import UpdateSignatory from "@/Pages/Signatory/updateSignatory";
import FilterSignatory from "@/Pages/Signatory/filterSignatory";
import DeleteDialog from "@/Pages/DeleteModal";

export default {
  components: {
    AppLayout,
    SnackbarMessage,
    AddSignatory,
    DeleteDialog,
    UpdateSignatory,
    FilterSignatory,
  },
  data: () => ({
    pageLength: 1,
    page: 1,
    method: "",
    signatoryList: [],
    filterDrawer: false,
    loadingFilter: false,
    filter: {
      search: "",
    },
    headers: [
      {
        text: "Department",
        align: "left",
        value: "department.department_initial",
      },
      {
        text: "Department Head",
        value: "department_head",
        align: "left",
      },
      {
        text: "Head Position",
        value: "department_head_position",
      },
      {
        text: "Signatory For",
        value: "signatory_role",
        align: "left",
      },

      {
        text: "Status",
        value: "status",
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
    departmentList: [],
    selectedSignatory: "",
  }),
  mounted() {
    this.loadSignatory(this.page);
    this.loadDepartment();
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
    loadSignatory(page) {
      this.$http
        .get(`/api/signatory?page=${page}&search=${this.filter.search}`)
        .then((response) => {
          this.signatoryList = response.data.data;
          this.pageLength = response.data.last_page;
        });
    },

    loadDepartment() {
      this.$http.get(`/api/department`).then((response) => {
        this.departmentList = response.data;
      });
    },

    submitAdd(value) {
      this.$http
        .post(`/api/signatory`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };

          this.modalAdd = false;
          this.loadSignatory(1);
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
        .put(`/api/signatory/${value.id}`, value)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          this.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };

          this.modalEdit = false;
          this.loadSignatory(1);
        })
        .catch((errorResponse) => {
          self.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },
    updateSignatory() {
        this.$http
        .get(`/api/signatory/sync`)
          .then((response) => {
            this.snackbar = {
              status: "success",
              message: response.data.message,
              show: true,
            };

            this.loadSignatory(1);
        })
        .catch((errorResponse) => {
          self.snackbar = {
            status: "warning",
            message: "Failed to sync payroll, Please contact MIS for support",
            show: true,
          };
        });
    },

    updateAccount() {
        this.$http
        .get(`/api/account/sync`)
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
            message: "Failed to sync portal account, Please contact MIS for support",
            show: true,
          };
        });
    },

    setPagination(page) {
      this.page = page;
      this.loadSignatory(page);
    },
    deleteSignatory(item) {
      this.selectedSignatory = item;
      this.modalDelete = true;
    },
    editSignatory(item) {
      this.selectedSignatory = item;
      this.modalEdit = true;
    },
    confirmDelete(value) {
      this.$http
        .delete(`/api/signatory/${value}`)
        .then((response) => {
          let method = response.data.error == null ? "success" : "warning";
          self.snackbar = {
            status: method,
            message: response.data.message,
            show: true,
          };
          this.modalDelete = false;
          this.loadSignatory();
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
      self.loadSignatory(1);

      setTimeout(function () {
        self.loadingFilter = false;
        self.filterDrawer = false;
      }, 1000);
    },

    resetFilter() {
      this.filter.search = "";
      this.page = 1;
      this.loadSignatory(1);
    },
  },
};
</script>
