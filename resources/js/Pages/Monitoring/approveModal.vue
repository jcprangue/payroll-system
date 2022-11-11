<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4 v-if="method == 1">
            Approve Payroll {{ item.payroll_groups.group_name }}
            <v-icon class="green--text" :icon="['fas', 'check']"></v-icon>
          </h4>
          <h4 v-if="method == 2">
            Disapprove Payroll {{ item.payroll_groups.group_name }}
            <v-icon class="red--text" :icon="['fas', 'times']"></v-icon>
          </h4>
        </strong>
      </v-card-title>
      <v-card-text class="pa-3">
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Status </label>
          <v-select
            v-model="form.status"
            :items="method==1 ? payrollStatus : payrollStatusDis"
            item-text="status"
            item-value="id"
            label="Select Payroll Status"
            outlined
            single-line
            required
          ></v-select>
        </v-flex>

        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Remarks </label>
          <ckeditor v-model="form.remarks"></ckeditor>
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
  name: "DeleteModal",
  props: {
    item: Object,
    payrollStatus: Array,
    payrollStatusDis: Array,
    method: Number,
  },
  data: () => ({
    form: {
      id: "",
      status: "",
      remarks: "",
      method: "",
    },
    status: [],
  }),
  mounted() {
    const self = this;
  },
  methods: {
    close() {
      this.$emit("close", true);
    },
    submit() {
      this.form.id = this.item.id;
      this.form.method = this.method;
      this.$emit("submit", this.form);
    },
  },
};
</script>