<template>
    <app-layout>
        <SnackbarMessage :snackbar="snackbar" />

        <v-toolbar flat>
            <v-toolbar-title>
                Casual Charging
            </v-toolbar-title>
            
        </v-toolbar>

      
        <div class="pa-5">
                <h2 for="">Payroll Charging Details</h2>

            <v-layout row wrap>
             
                <v-flex xs6 sm6 md6 class="mt-8 ">
                    <v-row class="px-5">
                        <v-btn
                            v-if="$can('create_charging')"
                            color="info"
                            class="mx-2"
                            @click="addNewChargingModal = true"
                            >Add</v-btn>
                        <v-btn
                            v-if="$can('update_charging')"
                            color="warning"
                            class="mx-2"
                            @click="showEditCharging()"
                            >Edit</v-btn>
                        <v-btn
                            v-if="$can('delete_charging')"
                            color="red"
                            class="mx-2 white--text"
                            @click="deleteModal = true"
                            >Delete</v-btn>
                    </v-row>

                    <v-row class="pl-5">
                        <v-treeview
                            v-model="selection"
                            :items="items"
                            selection-type="independent"
                            rounded
                            hoverable
                            activatable
                            return-object
                            @update:active="onUpdate"
                        ></v-treeview>
                    </v-row>
                </v-flex>


            </v-layout>
        </div>

        <!-- add charging -->
        <v-dialog
            content-class="app-modal"
            v-model="addNewChargingModal"
            max-width="350">

           <AddCharging
                :charingList="listCharging"
                :accountList="chartAccounts"
                @close="addNewChargingModal = false"
                @submit="submitAddCharging"
            />
        </v-dialog>
        <!-- end add charging -->

        <!-- edit charging -->
        <v-dialog
            content-class="app-modal"
            v-model="editChargingModal"
            max-width="350">

           <EditCharging
                :chargingList="listCharging"
                :accountList="chartAccounts"
                :charging="selection"
                @close="editChargingModal = false"
                @submit="submitEditCharging"
                ref="modalCharging"
            />
        </v-dialog>
        <!-- end edit charging -->

        <!-- delete dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="deleteModal"
            max-width="300">

           <DeleteCharging
                :item="selection[0]"
                @close="deleteModal = false"
                @submit="submitDelete"
            />
        </v-dialog>
         <!-- end of delete dialog -->

  </app-layout>
</template>

<script>
import AppLayout from '@/Partials/AdminLayout'
import SnackbarMessage from '@/Pages/SnackbarMessage';
import AddCharging from '@/Pages/Charging/addCharging';
import EditCharging from '@/Pages/Charging/editCharging';
import DeleteCharging from '@/Pages/DeleteModal';

export default {
    components: {
        AppLayout,
        SnackbarMessage,
        AddCharging,
        EditCharging,
        DeleteCharging,
    },
    data: () => ({
        selection: [],
        items: [],
        snackbar: {
            status: "",
            message: "",
            show: false,
        },
        addNewChargingModal:false,
        editChargingModal:false,
        deleteModal:false,
        listCharging: [],
        chartAccounts:[]
        
    }),
    mounted(){
       this.loadCharging();
       this.loadListCharging();
       this.loadAccounts();
    },  
  
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        
        onUpdate(selection) {
            this.selection = selection;
        },
        showEditCharging(){
            this.editChargingModal = true
        },
     
        loadCharging(){
            this.$http.get(`/api/payroll-officer/charging`).then(response => {
                this.items = response.data
            });
        },
        loadListCharging(){
            this.$http.get(`/api/payroll-officer/list/charging`).then(response => {
                this.listCharging = response.data
            });
        },
        loadAccounts(){
            this.$http.get(`/admin/list/chart-accounts`).then(response => {
                this.chartAccounts = response.data
               
            });
        },
        submitAddCharging(value){
            this.$http.post(`/api/payroll-officer/charging`,value).then(response => {
                let status = response.data.error == null ? 'success' : 'warning';
                this.snackbar = {
                    status: status,
                    message: response.data.message,
                    show: true,
                };
                this.loadCharging();
                this.loadListCharging();
                this.addNewChargingModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.addNewChargingModal = false;

            });
        },

        submitEditCharging(value) {
            console.log(value)
            this.$http.put(`/api/payroll-officer/charging/${value.id}`,value).then(response => {
                let status = response.data.error == null ? 'success' : 'warning';
                this.snackbar = {
                    status: status,
                    message: response.data.message,
                    show: true,
                };
                this.loadCharging();
                this.loadListCharging();
                this.editChargingModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.editChargingModal = false;

            });
        },

        submitDelete(value){
            this.$http.delete(`/api/payroll-officer/charging/${value}`).then(response => {
                let status = response.data.error == null ? 'success' : 'warning';
                this.snackbar = {
                    status: status,
                    message: response.data.message,
                    show: true,
                };
                this.loadCharging();
                this.loadListCharging();
                this.deleteModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.deleteModal = false;

            });
        }
        
        
    }
}
</script>
