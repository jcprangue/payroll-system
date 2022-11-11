<template>
  <app-layout>
    <SnackbarMessage :snackbar="snackbar" />

    <v-toolbar flat>
      <v-toolbar-title>
        <div v-if="$can('create_payroll')">
          <v-btn color="info" href="/admin/monitoring/casual/create"
            >Add New Payroll</v-btn
          >
        </div>
      </v-toolbar-title>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-spacer></v-spacer>
      <v-layout align-end justify-end class="mr-5">
        <v-flex xs6 text-right>
          <v-btn color="green" class="white--text" @click="genDebit()" v-if="$can('create_debit')">Generate Debit</v-btn>
          <v-btn color="info" @click.stop="filterDrawer = !filterDrawer">Filters</v-btn>
        </v-flex>
      </v-layout>
    </v-toolbar>

    <div class="pa-5">
      <v-data-table
        :headers="headers"
        :items="payrolls"
        :single-expand="singleExpand"
        :expanded.sync="expanded"
        :hide-default-footer="true"
        show-expand
        item-key="id"
      >
        <template v-slot:item.checkbox="{ item }">
          <v-checkbox
            :key="item.id"
            :value="item.id"
            v-model="checkPayroll"
          ></v-checkbox>
        </template>
        <template v-slot:item.status="{ item }">
          <v-chip :color="`${findColor(item.status)} darken-3`" dark small>
            {{ findStatus(item.status) }}
          </v-chip>
        </template>
        <template v-slot:item.remarks="{ item }">
          <!-- <read-more class="mt-2" more-str="Read More" :text="item.remarks" link="#" less-str="Read Less" :max-chars="75"></read-more> -->
          <v-btn color="primary" text small @click="viewRemarks(item)">
            View Remarks
          </v-btn>
        </template>
        <template v-slot:item.quincena="{ item }">
          <span v-if="item.quincena == 1">1st Quincena</span>
          <span v-if="item.quincena == 2">2nd Quincena</span>
          <span v-if="item.quincena == 3">Whole Month</span>
        </template>

        <template v-slot:item.action="{ item }">
          <v-btn
            color="primary"
            dark
            small
            :href="`/admin/monitoring/casual/${item.id}/edit`"
            v-if="$can('update_payroll')"
          >
            <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>
          </v-btn>

          <v-btn color="red" dark small @click="deletePayroll(item)" v-if="$can('delete_payroll')">
            <v-icon class="white--text" :icon="['fas', 'trash']"></v-icon>
          </v-btn>

          <v-btn color="green" dark small @click="showApproveDialog(item, 1)" v-if="$can('approve_payroll')">
            <v-icon class="white--text" :icon="['fas', 'check']"></v-icon>
          </v-btn>

          <v-btn
            color="red darken-4"
            dark
            small
            @click="showApproveDialog(item, 2)"
            v-if="$can('decline_payroll')"
          >
            <v-icon class="white--text" :icon="['fas', 'times']"></v-icon>
          </v-btn>
        </template>

        <template v-slot:expanded-item="{ headers, item }">
          <td :colspan="headers.length" align="center">
            <v-btn
              class="ma-2"
              color="blue"
              dark
              small
              :href="`/api/monitoring/generatePayroll/${item.id}`"
              target="_Blank"
            >
              View Payroll Sheet&nbsp;<v-icon
                class="white--text"
                :icon="['fas', 'file']"
              ></v-icon>
            </v-btn>
            <v-btn
              class="ma-2"
              color="green"
              dark
              small
              :href="`/api/monitoring/generateOBR/${item.id}`"
              target="_Blank"
            >
              View OBR&nbsp;<v-icon
                class="white--text"
                :icon="['fas', 'file']"
              ></v-icon>
            </v-btn>
            <v-btn
              class="ma-2"
              color="indigo darken-1"
              dark
              small
              @click="loadHistory(item)"
            >
              History &nbsp;<v-icon
                class="white--text"
                :icon="['fas', 'history']"
              ></v-icon>
            </v-btn>
          </td>
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
      <FilterMonitoring
        @filter="submitFilter"
        @resetFilter="resetFilter"
        @close="filterDrawer = false"
        :loading="loadingFilter"
      />
    </v-navigation-drawer>
    <!-- end filter drawer -->

    <!-- modal history -->
    <v-dialog
      v-model="historyModal"
      fullscreen
      hide-overlay
      transition="dialog-bottom-transition"
    >
      <historyMonitoring
        :item="selectedPayroll"
        :payrollStatus="allStatus"
        @close="historyModal = false"
      />
    </v-dialog>
    <!-- end modal history -->

    <!-- view remarks -->
    <v-dialog
      v-model="reviewModal"
      transition="dialog-bottom-transition"
      max-width="600"
    >
      <reviewRemarks
        :item="selectedPayroll"
        :payrollStatus="payrollStatus"
        @close="reviewModal = false"
      />
    </v-dialog>
    <!-- end of view remarks -->

    <!-- delete dialog -->
    <v-dialog content-class="app-modal" v-model="deleteModal" max-width="300">
      <DeleteDialog
        :item="selectedPayroll"
        @close="deleteModal = false"
        @submit="confirmDelete"
      />
    </v-dialog>
    <!-- end of delete dialog -->

    <!-- modal generate debit -->
    <v-dialog
      content-class="app-modal"
      scrollable
      v-model="modalDebit"
      max-width="500"
    >
      <DebitDialog
        :items="employeeListNetPay"
        @close="modalDebit = false"
        @submit="submitDebit"
      />
    </v-dialog>
    <!-- end of modal generate Debit -->

    <!-- start of approve modal -->

    <v-navigation-drawer
      width="600"
      right
      v-model="approveModal"
      hide-overlay
      temporary
      absolute
    >
      <ApproveDialog
        v-if="approveModal"
        :item="selectedPayroll"
        :payrollStatus="payrollStatusApprove"
        :payrollStatusDis="payrollStatusDisapprove"
        :method="method"
        @close="approveModal = false"
        @submit="approvePayroll"
      />
    </v-navigation-drawer>
    <!-- end of approve modal -->
  </app-layout>
</template>
<style scoped>
.v-toolbar__title {
  font-weight: bold !important;
  text-align: left;
  line-height: 23px;
  margin-right: 15px;
}
</style>
<script>
import AppLayout from "@/Partials/AdminLayout";
import FilterMonitoring from "@/Pages/Monitoring/Filters";
import historyMonitoring from "@/Pages/Monitoring/history";
import reviewRemarks from "@/Pages/Monitoring/review";
import SnackbarMessage from "@/Pages/SnackbarMessage";
import DeleteDialog from "@/Pages/DeleteModal";
import ApproveDialog from "@/Pages/Monitoring/approveModal";
import moment from "moment";
import DebitDialog from "@/Pages/Monitoring/generateDebit";

export default {
  components: {
    AppLayout,
    FilterMonitoring,
    historyMonitoring,
    reviewRemarks,
    SnackbarMessage,
    DeleteDialog,
    DebitDialog,
    ApproveDialog,
  },
  data: () => ({
    pageLength: 1,
    page: 1,
    method: "",
    approveModal: false,
    loadingFilter: false,
    filterDrawer: false,
    activeFilters: false,
    checkPayroll: [],
    payrollStatus: [],
    payrollStatusApprove: [],
    payrollStatusDisapprove: [],
    allStatus: [],
    filter: {
      search: "",
      quincena: "",//moment().format('D') >= 16 ? 1 : 2,
      control_number: "",
      month: moment().subtract(1, 'months').format("YYYY-MM"),
    },
    historyModal: false,
    reviewModal: false,
    reviewModal: false,
    deleteModal: false,
    modalDebit: false,
    selectedPayroll: [],
    employeeListNetPay: [],

    payrolls: [],
    singleExpand: false,
    expanded: [],
    isLoading: false,
    entry: "",
    tab: "",
    items: [
      {
        text: "Casual Monitoring",
        key: "casual",
        link: "/admin/monitoring/casual",
      },
    ],
    headers: [
      {
        text: "",
        align: "left",
        value: "checkbox",
        sortable: false,
      },
      {
        text: "Payroll Group Name",
        align: "left",
        value: "payroll_groups.group_name",
      },
      {
        text: "Control Number",
        align: "left",
        value: "control_number",
      },
      {
        text: "Status",
        value: "status",
        width: "15%",
      },
      {
        text: "Amount",
        value: "amount",
      },
      {
        text: "Period",
        value: "quincena",
      },
      {
        text: "Remarks",
        value: "remarks",
        align: "left",
        width: "15%",
      },

      {
        text: "Payroll Month",
        value: "month",
        align: "left",
      },
      {
        text: "Action",
        value: "action",
        align: "center",
      },
      { text: "", value: "data-table-expand" },
    ],
    snackbar: {
      status: "",
      message: "",
      show: false,
    },
  }),
  watch: {
    'checkPayroll': function (val) {
      if (val.length == 0) {
        this.loadMonitoring(this.page);
      }
    }
  },
  mounted() {
    this.loadMonitoring(this.page);
    this.loadStatus();
    this.loadStatusApprove();
    this.loadAllStatus();
  },

  methods: {
    submitEntry() {
      this.$emit("submitEntry", this.entry);
    },
    findColor(id) {
      let color = ['grey','blue','red','green','orange','green','orange','green','grey','purple','red','green','red','green','green']
      return color[id];
    },
    findStatus(id) {
      let status = "";
      for (let i = 0; i < this.payrollStatus.length; i++) {
        if (this.payrollStatus[i]["id"] == id) {
          status = this.payrollStatus[i]["status"];
        }
      }
      return status;
    },
    loadStatus() {
      this.$http.get(`/api/monitoring/status`).then((response) => {
        this.payrollStatus = response.data;
        console.log(this.payrollStatus);
      });
    },
    loadAllStatus() {
      this.$http.get(`/api/monitoring/all-status`).then((response) => {
        this.allStatus = response.data;
      });
    },

    loadStatusApprove() {
      this.$http.get(`/api/monitoring/approve-status`).then((response) => {
        let list = response.data;
        this.payrollStatusApprove = list.filter(data => {
          return data.category == 1
        });
        this.payrollStatusDisapprove = list.filter(data => {
          return data.category == 2
        });

       
      });
    },

    loadMonitoring(page) {
      console.log(this.filter)
      this.$http
        .get(
          `/api/monitoring/casual?page=${page}&control_number=${this.filter.control_number}&search=${this.filter.search}&quincena=${this.filter.quincena}&month=${this.filter.month}`
        )
        .then((response) => {
          this.payrolls = response.data.data;
          this.pageLength = response.data.last_page;
        });
    },

    loadHistory(item) {
      this.historyModal = true;
      this.selectedPayroll = item;
    },

    submitFilter(filter) {
      let self = this;
      self.filter = filter;
      self.page = 1;
      self.loadingFilter = true;
      self.loadMonitoring(1);

      //self.selectedType = "all";

      setTimeout(function () {
        self.loadingFilter = false;
        self.filterDrawer = false;
      }, 1000);
    },

    resetFilter() {
      this.filter.search = "";
      this.filter.quincena = "";
      this.filter.control_number = "";
      this.filter.month = moment().format("YYYY-MM-DD");
      this.page = 1;
      this.loadMonitoring(1);
    },
    setPagination(page) {
      this.page = page;
      this.loadMonitoring(page);
    },
    viewRemarks(item) {
      this.reviewModal = true;
      this.selectedPayroll = item;
    },

    deletePayroll(item) {
      this.selectedPayroll = item;
      this.deleteModal = true;
    },

    genDebit() {
      this.$http
        .post(`/api/monitoring/debit/list`, {
          payroll_ids: this.checkPayroll,
        })
        .then((response) => {
          this.employeeListNetPay = response.data;
        });
      this.modalDebit = true;
    },

    confirmDelete(value) {
      this.$http
        .delete(`/api/monitoring/casual/destroy/${value}`)
        .then((response) => {
          if (response.data.error == null) {
            this.snackbar = {
              status: "success",
              message: response.data.message,
              show: true,
            };
          } else {
            this.snackbar = {
              status: "warning",
              message: response.data.message,
              show: true,
            };
          }

          this.deleteModal = false;
          this.loadMonitoring();
        })
        .catch((errorResponse) => {
          this.snackbar = {
            status: "warning",
            message: "Failed to create payroll, Please contact MIS for support",
            show: true,
          };
        });
    },

    showApproveDialog(value, method) {
      this.approveModal = true;
      this.selectedPayroll = value;
      this.method = method; // approve
    },

    submitDebit(value) {
      const self = this;
      this.$http.post(`/api/monitoring/debit/save`, value).then((response) => {
        if (response.data.error == null) {
          this.snackbar = {
            status: "success",
            message: response.data.message,
            show: true,
          };
        } else {
          this.snackbar = {
            status: "warning",
            message: response.data.message,
            show: true,
          };
        }
      });

      this.loadMonitoring(this.page);
      this.$forceUpdate();
      this.checkPayroll = [];
      this.modalDebit = false;

    },

    approvePayroll(value) {
      this.$http
        .post(`/api/monitoring/casual/approve`, value)
        .then((response) => {
          if (response.data.error == null) {
            this.snackbar = {
              status: "success",
              message: response.data.message,
              show: true,
            };
          } else {
            this.snackbar = {
              status: "warning",
              message: response.data.message,
              show: true,
            };
          }
          this.loadMonitoring(this.page);
          this.approveModal = false;
        });
    },
  },
};
</script>
