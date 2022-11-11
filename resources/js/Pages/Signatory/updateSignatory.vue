<template>
  <div>
    <v-card flat>
      <v-card-title class="grey lighten-2" primary-title>
        <strong>
          <h4>Update Signatory</h4>
        </strong>
      </v-card-title>
      <v-card-text class="pa-3">
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Status </label>
          <v-autocomplete
            v-model="form.department_id"
            :items="departments"
            item-text="department_initial"
            item-value="id"
            label="Select Department"
            outlined
            single-line
            required
          ></v-autocomplete>
        </v-flex>
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Department Head </label>
          <v-text-field
            v-model="form.department_head"
            label="Department Head"
            single-line
            outlined
            required
          ></v-text-field>
        </v-flex>
        <v-flex xs12 class="text-left">
          <label class="font-weight-bold"> Department Head Position </label>
          <v-text-field
            v-model="form.department_head_position"
            label="Head Position"
            single-line
            outlined
            required
          ></v-text-field>
        </v-flex>
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Signatory for </label>
          <v-select
            v-model="form.signatory_role"
            :items="signatory"
            item-text="value"
            item-value="id"
            label="Select Signatory"
            outlined
            single-line
            required
          ></v-select>
        </v-flex>
        <v-flex xs12 class="text-left mt-4">
          <label class="font-weight-bold"> Status </label>
          <v-select
            v-model="form.status"
            :items="status"
            item-text="value"
            item-value="id"
            label="Select Status"
            outlined
            single-line
            required
          ></v-select>
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
  name: "AddSignatory",
  props: {
    departments: Array,
    item: Object,
  },
  data: () => ({
    signatory: [
      {
        id: 1,
        value: "Department Signatory",
      },
      {
        id: 2,
        value: "HR Signatory",
      },
      {
        id: 3,
        value: "Treasurer Signatory",
      },
      {
        id: 4,
        value: "Accounting Signatory",
      },
      {
        id: 5,
        value: "Signed Cheque Signatory",
      },
    ],
    status: [
      {
        id: 0,
        value: "Inactive",
      },
      {
        id: 1,
        value: "Active",
      },
    ],
  }),
  computed: {
    form: function () {
      return this.item;
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
      this.$emit("submit", this.form);
      this.form.department_id = "";
      this.form.department_head = "";
      this.form.department_head_position = "";
      this.form.signatory_role = "";
      this.form.status = "";
    },
  },
};
</script>