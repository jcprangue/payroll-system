<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>Print OBR</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <v-flex xs12 class="text-left">
                <label>Month</label>
                <v-menu
                    ref="menu"
                    v-model="payrollMonth"
                    :close-on-content-click="false"
                    :return-value.sync="form.month"
                    transition="scale-transition"
                    offset-y
                    max-width="290px"
                    min-width="auto"
                >
                    <template v-slot:activator="{ on, attrs }">
                    <v-text-field
                        v-model="form.month"
                        label="Picker in menu"
                        readonly
                        v-bind="attrs"
                        outlined
                        clearable
                        single-line
                        v-on="on"
                    ></v-text-field>
                    </template>
                    <v-date-picker v-model="form.month" type="month" no-title scrollable>
                    <v-spacer></v-spacer>
                    <v-btn text color="primary" @click="payrollMonth = false">
                        Cancel
                    </v-btn>
                    <v-btn text color="primary" @click="$refs.menu.save(form.month)">
                        OK
                    </v-btn>
                    </v-date-picker>
                </v-menu>
            </v-flex>

            <v-flex xs12 class="text-left">
                  <label class="font-weight-bold">
                    OBR Period
                    <span class="red--text">*</span>
                  </label>
                  <v-select
                    v-model="form.period"
                    :items="period"
                    item-text="name"
                    item-value="value"
                    :rules="rules"
                    label="Select Payroll Period"
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
        >Print</v-btn
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
        departments: [Object,Array],
        group: [Object,Array],

    },
    data() {
        return {
            form: {
                id:this.group.id,
                month:'',
                period: '',
            },
            payrollMonth:false,
            period: [
            {
                name: "1st Quincena",
                value: 1,
            },
            {
                name: "2nd Quincena",
                value: 2,
            },
            {
                name: "Whole Month",
                value: 3,
            },
            ],
            rules: [v => !!v || 'This field is required'],
        };
    },
    watch:{
        'group':function(val){
            this.form.id = val.id;
            this.form.month = "";
            this.form.period = "";
        }
    },
    mounted() {
        this.form.id = this.group.id;
        this.form.month = "";
        this.form.period = "";
    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.$emit("submit", this.form);
            // this.form.month = ''
            // this.form.period = ''
        },
    }
};
</script>