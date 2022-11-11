<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>New Group</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Add Payroll Group Name
                </label>
                <v-text-field
                    v-model="form.name"
                    label="Payroll Name"
                    single-line
                    outlined
                    autocomplete="false"
                    hint="Please choose name based on the first employee in payroll"
                    required
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
                    :rules="rules"
                    label="Select Payroll Period"
                    outlined
                    single-line
                    required
                ></v-select>
            </v-flex>
            
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Charge to Department
                    <span class="red--text">*</span>
                </label>
                <v-select
                    v-model="form.department_charging_id"
                    :items="departments"
                    item-text="department_initial"
                    item-value="id"
                    :rules="rules"
                    label="Select Department Charging"
                    outlined
                    single-line
                    required
                ></v-select>
            </v-flex>
        </v-row>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="error"
        >Create</v-btn
    >
    <v-btn depressed @click="close">Cancel</v-btn>
    </v-card-actions>
</v-card>
  </div>
</template>

<script>
export default {
    name: "CreateCasualGroup",
    props: {
        departments: [Object,Array]
    },
    data() {
        return {
            form:{
                name:'',
                type: '',
                department_charging_id:''
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
            rules: [v => !!v || 'This field is required'],
        };
    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.$emit("submit", this.form);
            this.form.name = ''
            this.form.type = ''
        },
    }
};
</script>