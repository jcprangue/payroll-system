<template>
  <div>
    <v-card class="flexcard" style="height: 100vh">
      <v-toolbar dark color="deep-purple darken-1">
        <v-btn text @click="closeModal">
          <v-icon class="white--text" :icon="['fas', 'times']"></v-icon>
        </v-btn>
        <v-toolbar-title
          >Payroll history of
          {{ item.payroll_groups.group_name }}</v-toolbar-title
        >
        <v-spacer></v-spacer>
      </v-toolbar>

      <div class="pa-5">
        <v-data-table
          :headers="headers"
          :items="historyRecords"
          :single-expand="singleExpand"
          :expanded.sync="expanded"
          :hide-default-footer="true"
          item-key="id"
        >
          <template v-slot:item.name="{ item }">
            <label class="font-weight-black"
              >{{ item.user.first_name }} {{ item.user.last_name }}
            </label>
          </template>
          <template v-slot:item.status="{ item }">
            <v-chip color="purple darken-3" dark small>
              {{ findStatus(item.status) }}
            </v-chip>
          </template>
          <template v-slot:item.remarks="{ item }">
            <read-more
              v-if="item.remarks != null"
              class="mt-2"
              more-str="Read More"
              :text="item.remarks"
              link="#"
              less-str="Read Less"
              :max-chars="75"
            ></read-more>
          </template>
          <template v-slot:item.method="{ item }">
            <v-chip v-if="item.method == 1" color="green darken-3" dark small>
              <v-icon class="white--text" :icon="['fas', 'check']"></v-icon>
              &nbsp;Approved
            </v-chip>

            <v-chip v-else color="red darken-3" dark small>
              <v-icon class="white--text" :icon="['fas', 'times']"></v-icon>
              &nbsp;Declined
            </v-chip>
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
    </v-card>
  </div>
</template>

<script>
export default {
  name: "PayrollHistory",
  props: {
    loading: Boolean,
    item: Object,
    payrollStatus: Array,
  },
  data() {
    return {
      historyRecords: [],
      singleExpand: false,
      expanded: [],
      pageLength: 1,
      headers: [
        {
          text: "",
          align: "left",
          value: "counter",
          sortable: false,
        },
        {
          text: "Portal User",
          align: "left",
          value: "name",
        },
        {
          text: "Status",
          value: "status",
          width: "15%",
        },

        {
          text: "User Remarks",
          value: "remarks",
          align: "left",
          width: "30%",
        },
        {
          text: "Methods",
          value: "method",
          align: "left",
        },
        {
          text: "Date Created",
          value: "created_at",
          align: "center",
        },
        {
          text: "Duration",
          value: "created_at",
          align: "center",
        },
      ],
      page: 1,
    };
  },
  watch: {
    'item': function () {
      this.loadHistory(1)
    }
  },
  methods: {
    closeModal() {
      this.$emit("close", true);
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
    loadHistory(page) {
      this.$http
        .get(`/api/monitoring/casual/history?page=${page}&id=${this.item.id}`)
        .then((response) => {
          this.historyRecords = response.data.data;
          this.pageLength = response.data.last_page;
        });
    },
    setPagination(page) {
      this.page = page;
      this.loadHistory(page);
    },
  },
  mounted() {
    this.loadHistory(1);
  },
};
</script>