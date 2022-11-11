<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>Set Employee Payroll Type</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <!--  v-if="$can('update_salary') -->
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Employee Salary
                </label>
                <v-text-field
                    v-model="form.salary"
                    label="Employee Salary"
                    single-line
                    outlined
                    disabled
                    autocomplete="false"
                ></v-text-field>
            </v-flex>
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Payroll Type
                    <span class="red--text">*</span>
                </label>
                <v-select
                    v-model="form.type"
                    :items="payrollTypes"
                    item-text="name"
                    item-value="type"
                    label="Select Payroll Type"
                    outlined
                    single-line
                    required
                ></v-select>
            </v-flex>

             <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Employee Tax (%)
                </label>
                <v-text-field
                    v-model="form.tax"
                    label="Employee Tax"
                    single-line
                    outlined
                    autocomplete="false"
                ></v-text-field>
            </v-flex>
            
        </v-row>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="info"
        >Update</v-btn
    >
    <v-btn depressed @click="close">Cancel</v-btn>
    </v-card-actions>
</v-card>
  </div>
</template>

<script>
export default {
    name: "employeeSetModal",
    props: {
        employee: Object,
    },
    data() {
        return {
            form:{
                employee_id:'',
                salary:'',
                type:'',
                tax:''
            },
            payrollTypes:[{
                name:'Contract of Service',
                type: 1,
            },{
                name:'Casual',
                type: 2,
            },{
                name:'Contractual',
                type: 3,
            },{
                name:'Job Order',
                type: 4,
            }],

            salaryReadOnly:false
        };
    },
    watch:{
        'employee':function(val){
            this.form.salary = val.salary;
            this.form.type = val.type;
            this.form.tax = val.tax;

            console.log(val)
        }
    },
    mounted() {
        if (this.$can('update_salary')) {
            this.salaryReadOnly = true;
        }
        this.form.salary = this.employee.salary;
        this.form.type = this.employee.type;
        this.form.tax = this.employee.tax;


    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.form.employee_id = this.employee.id;
            this.$emit("submit", this.form);
            this.form.employee_id = ''
            this.form.salary = ''
            this.form.type = ''
        },
    }
};
</script>