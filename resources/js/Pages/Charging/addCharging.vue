<template>
  <div>
    <v-card>
    <v-card-title class="grey lighten-2" primary-title>
    <strong>
        <h4>New Charging Details</h4>
    </strong>
    </v-card-title>
    <v-card-text>
        <v-row class="my-4 mx-2">
            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Charging Parent
                    <span class="red--text">*</span>
                </label>
                <v-autocomplete
                    v-model="form.parent_id"
                    :items="charingList"
                    item-text="name"
                    item-value="id"
                    label="Select Charging Parent"
                    outlined
                    single-line
                    required
                ></v-autocomplete>
            </v-flex>

            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Charging Description
                </label>
                <v-text-field
                    v-model="form.charging_name"
                    label="Charging Description"
                    single-line
                    outlined
                    autocomplete="false"
                    required
                ></v-text-field>
            </v-flex>

            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Code
                </label>
                <v-text-field
                    v-model="form.code"
                    label="Charging Code"
                    single-line
                    outlined
                    autocomplete="false"
                    required
                ></v-text-field>
            </v-flex>

            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Account Code
                </label>
                <v-autocomplete
                    v-model="form.accounts"
                    :items="accountList"
                    item-text="name"
                    item-value="account_number"
                    label="Select Account"
                    outlined
                    single-line
                    required
                ></v-autocomplete>
            </v-flex>

            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    KRA Charging
                </label>
                <v-autocomplete
                    v-model="form.kra_charging"
                    :items="accountList"
                    item-text="name"
                    item-value="account_number"
                    label="Select CHarging for KRA"
                    outlined
                    single-line
                    required
                ></v-autocomplete>
            </v-flex>

            <v-flex xs12 class="text-left">
                <label class="font-weight-bold">
                    Visible
                </label>
                <v-autocomplete
                    v-model="form.is_visible"
                    :items="options"
                    item-text="text"
                    item-value="value"
                    label="Visible in OBR"
                    outlined
                    single-line
                    required
                ></v-autocomplete>
            </v-flex>
            
            
        </v-row>
    </v-card-text>
    <v-card-actions>
    <v-spacer></v-spacer>
    <v-btn depressed @click="submit()" color="success"
        >Create</v-btn
    >
    <v-btn depressed @click="close">Cancel</v-btn>
    </v-card-actions>
</v-card>
  </div>
</template>

<script>
export default {
    name: "CreateCharging",
    props: {
        charingList: Array,
        accountList: [Array,Object],
    },
    data() {
        return {
            form:{
                parent_id:'',
                charging_name:'',
                code: '',
                accounts: '',
                kra_charging: '',
                is_visible:1,
            },
            options: [
                {
                    text: 'Yes',
                    value: 1
                },
                {
                    text: 'No',
                    value: 0
                },
            ]
            
        };
    },
    methods: {
        close() {
            this.$emit("close", true);
        },
        submit() {
            this.$emit("submit", this.form);
            this.form = {
                parent_id:'',
                charging_name:''
            }
        },
    }
};
</script>