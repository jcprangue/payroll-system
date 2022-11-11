<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Generate Debit Batch</h4>
        </strong>
      </v-card-title>
      <v-card-text class="pa-3">
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Batch Number </label>
          <v-text-field
            v-model="form.batch"
            label="Enter Batch Number"
            single-line
            outlined
          ></v-text-field>
        </v-flex>
        <v-flex>
          <v-data-table
            :headers="headers"
            :hide-default-footer="true"
            :items="items"
            item-key="count"
          >
          </v-data-table>
        </v-flex>
      </v-card-text>
      <v-card-text class="pa-3"> TOTAL: {{ totalData }} </v-card-text>
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
  name: "AddSignatory",
  props: {
    items: Array,
  },
  data: () => ({
    form: {
      batch: "",
      items: "",
    },
    headers: [
      {
        text: "#",
        align: "left",
        value: "count",
      },
      {
        text: "Employee Name",
        align: "left",
        value: "employee_name",
      },
      {
        text: "Net Pay",
        value: "amount",
      },
    ],
  }),
  computed: {
    totalData: function () {
      let totals = 0;
      this.items.forEach((query) => {
        totals += query.amount;
      });

      return totals;
    },
  },
  mounted() {
    const self = this;
  },
  methods: {
    close() {
      this.$emit("close", true);
    },
    submit() {
      this.form.items = this.items;
      this.$emit("submit", this.form);
    },
  },
};
</script>