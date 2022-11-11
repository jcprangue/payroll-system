<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>Re-Group Employee ID # {{employee.employee_id}}</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Payroll Group 
                    <span class="red--text">*</span>
                </label>
                <v-select
                    v-model="form.group_id"
                    :items="groups"
                    item-text="group_name"
                    item-value="id"
                    outlined
                    single-line
                    required
                ></v-select>
            </v-flex>
            
        </v-row>
    </v-card-text>
    <v-card-actions>
    <v-btn depressed @click="submitUngroup()" color="warning"
        >Ungroup</v-btn
    >
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="success"
        >Group</v-btn
    >
    <v-btn depressed @click="close">Cancel</v-btn>
    </v-card-actions>
</v-card>
  </div>
</template>

<script>
export default {
    name: "EmployeeGrouping",
    props: {
        employee: Object,
        groups: Array,
    },
    data() {
        return {
            form:{
                employee_id:'',
                group_id:''
            },
           
        };
    },
    watch:{
        'employee':function(val){
            this.form.group_id = val.group_id;
        }
    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.form.employee_id = this.employee.id;
            this.$emit("submit", this.form);
        },
        submitUngroup() {
            this.form.employee_id = this.employee.id;
            this.form.group_id = null;
            this.$emit("ungroup", this.form);
        },
    }
};
</script>