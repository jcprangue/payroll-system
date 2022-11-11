<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>Set Employee Charging</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <label v-html="`Selected Charging : ${employee.charging ? employee.charging.charging_name : ''}`"></label>
        </v-row>
        <v-row class="my-4 mx-2">
            <!-- <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Payroll Charging
                    <span class="red--text">*</span>
                </label>
                <v-select
                    v-model="form.charging_id"
                    :items="chargingList"
                    item-text="charging_name"
                    item-value="id"
                    label="Select Charging"
                    outlined
                    single-line
                    required
                ></v-select>
            </v-flex> -->

            <v-treeview
                v-model="selection"
                :items="chargingList"
                selection-type="independent"
                rounded
                hoverable
                activatable
                return-object
                @update:active="onUpdate"
            ></v-treeview>
            
        </v-row>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="info"
        >Set</v-btn
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
        chargingList: Array,
        employee: Object
    },
    data() {
        return {
            form:{
                id:'',
                charging_id:'',
            },

            selection: [],
            items: [],
           
        };
    },
    watch:{
        'employee': function (val) {
            this.form.charging_id = val.charging_id;
            this.selection[0] = val.charging;

        }
    },
    mounted() {
        this.form.charging_id = this.employee.charging_id;
    },
    methods: {
        onUpdate(selection) {
            this.selection = selection;
            this.form.charging_id = selection[0].id
        },
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.form.id = this.employee.id;
            console.log("charging form",this.form)
            this.$emit("submit", this.form);
            // this.form.id = ''
            // this.form.charging_id = ''
        },
    }
};
</script>