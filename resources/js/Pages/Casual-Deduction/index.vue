<template>
    <app-layout>
        <SnackbarMessage :snackbar="snackbar" />

        <v-toolbar flat>
            <v-toolbar-title>
                Casual Deduction
            </v-toolbar-title>
            <v-layout align-end justify-end class="mr-20">
                <div xs2 text-right v-if="$can('create_deduction')">
                    <v-btn
                    color="info"
                    @click="deductionModal = true"
                    >Add New Deduction</v-btn>
                </div>
            </v-layout>
        </v-toolbar>

      
        <div class="pa-5">
                <h2 for="">Mandatory Deductions</h2>

            <v-layout row wrap>
                <v-flex xs6 sm6 md6 class="mt-5" style="border-right:3px solid grey">
                    <v-flex xs6 sm6 md6>
                        
                        <v-text-field
                            v-model="search"
                            label="Search for Deduction"
                            single-line
                            outlined
                            
                            v-on:keyup.enter="loadCasualDeduction(1)"
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                    </v-flex>
                    <v-data-table
                        class="mr-5"
                        :headers="headers"
                        :items="casualDeductionList"
                        :hide-default-footer="true"
                        item-key="id">
                        <template v-slot:item.name="{  item }">
                            <label for="" class="font-weight-bold">
                                {{ item.deductions.deduction_nick}}
                            </label>
                        </template>

                        <template v-slot:item.action="{  item }">
                            <v-btn color="primary" dark small @click="editDeduction(item)" v-if="$can('update_deduction')">
                                <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>
                            </v-btn>
                            <v-btn color="red" dark small @click="deletePayroll(item)" v-if="$can('delete_deduction')">
                                <v-icon class="white--text" :icon="['fas', 'trash']"></v-icon>
                            </v-btn>
                        </template>
                    </v-data-table>

                    <div class="text-xs-center py-3">
                        <v-pagination
                        v-model="page"
                        :length="pageLength"
                        @input="setPagination"
                        :total-visible="8"
                        ></v-pagination>
                    </div>
                </v-flex>

                <v-flex xs6 sm6 md6 class="mt-8 pl-4">
                    <v-row class="px-5">
                        <v-flex xs6 sm6 md6>
                            <v-text-field
                                v-model="searchEmployee"
                                label="Search for Employee"
                                single-line
                                outlined
                                v-on:keyup.enter="loadEmpExcemptDeduction(1)"
                            ></v-text-field>
                            
                        </v-flex>
                        <v-flex xs6 sm6 md6 class="text-right">
                            <v-btn
                                v-if="$can('create_deduction_exempt')"
                                color="info"
                                x-large
                                @click="exemptModal = true"
                                >Add New</v-btn>
                        </v-flex>
                    </v-row>

                    <v-row class="px-5">
                        <v-flex xs12 sm12 md12>
                            <v-data-table
                                class="mr-5"
                                :headers="headersEmployee"
                                :items="employeeExemptList"
                                :hide-default-footer="true"
                                item-key="id">
                                <template v-slot:item.name="{  item }">
                                    <label for="" class="font-weight-bold">
                                        {{ item.casual_employee.last_name}} {{ item.casual_employee.first_name}}, {{ item.casual_employee.middle_name}}.
                                    </label>
                                </template>

                                <template v-slot:item.action="{  item }">
                                    <v-btn color="red" dark small @click="deleteExempt(item)" v-if="$can('delete_deduction_exempt')">
                                        <v-icon class="white--text" :icon="['fas', 'trash']"></v-icon>
                                    </v-btn>
                                </template>
                            </v-data-table>
                        </v-flex>

                        <v-flex xs12 sm12 md12>
                            <div class="text-xs-center py-3">
                                <v-pagination
                                v-model="pageEmployee"
                                :length="pageLengthEmployee"
                                @input="setPaginationEmployee"
                                :total-visible="8"
                                ></v-pagination>
                            </div>
                        </v-flex>
                      

                        
                    </v-row>
                     
                     
                   
                </v-flex>

                

            

            </v-layout>
        </div>


        
        <!-- set dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="deductionModal"
            max-width="450">

           <DeductionModal
                :deductionList="deductionList"
                @close="deductionModal = false"
                @submit="submitDeduction"
            />
        </v-dialog>
        <!-- end modal history -->

        <!-- delete dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="deleteModal"
            max-width="300">

            <DeleteDialog
                :item="selectedDeduction"
                @close="deleteModal = false"
                @submit="confirmDelete"
            />
        
        </v-dialog>
        <!-- end of delete dialog -->

         <!-- delete dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="deleteModalExempt"
            max-width="300">

            <DeleteDialogExempt
                :item="selectedEmployee"
                @close="deleteModalExempt = false"
                @submit="confirmDeleteExempt"

            />
        
        </v-dialog>
        <!-- end of delete dialog -->

        <!-- edit dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="editModal"
            max-width="450">

           <EditDeductionModal
                :deductionList="deductionList"
                :item="selectedDeduction"
                @close="editModal = false"
                @submit="submitUpdateDeduction"
            />
        </v-dialog>
        <!-- end of delete dialog -->

        <!-- edit dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="exemptModal"
            max-width="450">

           <ExemptDialog
                :employees="employees"
                :deductions="casualDeductionList"
                @close="exemptModal = false"
                @submit="submitExempt"
            />
        </v-dialog>
        <!-- end of delete dialog -->

  </app-layout>
</template>

<script>
import AppLayout from '@/Partials/AdminLayout'
import SnackbarMessage from '@/Pages/SnackbarMessage';
import DeductionModal from '@/Pages/Casual-Deduction/deductionModal';
import EditDeductionModal from '@/Pages/Casual-Deduction/editDeductionModal';
import DeleteDialog from '@/Pages/DeleteModal';
import DeleteDialogExempt from '@/Pages/DeleteModal';
import ExemptDialog from '@/Pages/Casual-Deduction/exemptModal';

export default {
    components: {
        AppLayout,
        SnackbarMessage,
        DeductionModal,
        DeleteDialog,
        EditDeductionModal,
        ExemptDialog,
        DeleteDialogExempt
    },
    data: () => ({
        selectedDeduction:[],
        deleteModal: false,
        deductionModal: false,
        editModal: false,
        deductionList: [],
        casualDeductionList: [],
        snackbar: {
            status: "",
            message: "",
            show: false,
        },
        headers: [
            {
                text: "Deduction Name",
                align: "left",
                value: "name",
            },
            {
                text: "Month Start",
                align: "left",
                value: "date_start",
            },
            {
                text: "Amount",
                align: "left",
                value: "amount",
            },
            {
                text: "Action",
                value: "action",
            },
        ],
        search:'',
        pageLength:1,
        page:1,


        //right table 
        selectedEmployee:[],
        employeeExemptList: [],
        headersEmployee: [
            {
                text: "Employee",
                align: "left",
                value: "name",
            },
            {
                text: "Month",
                align: "left",
                value: "month",
            },
            {
                text: "Deduction",
                align: "left",
                value: "deduction.deductions.deduction_nick",
            },
            {
                text: "Action",
                value: "action",
            },
        ],
        searchEmployee:'',
        pageLengthEmployee:1,
        pageEmployee:1,
        exemptModal:false,
        deleteModalExempt:false,
        employees:[],
        
    }),
    mounted(){
        this.loadCasualDeduction(1);
        this.loadDeductionList();
        this.loadEmpExcemptDeduction(1);
        this.loadEmployeees();
    },  
  
    methods: {
        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
     
        loadDeductionList(){
            this.$http.get(`/api/payroll/codes`).then(response => {
                this.deductionList = response.data
            });
        },
        loadCasualDeduction(page){
            this.$http.get(`/api/payroll/casual/deduction?page=${page}&search=${this.search}`).then(response => {
                this.casualDeductionList = response.data.data
                this.pageLength = response.data.last_page
                this.search = '';
            });
        },
        setPagination(page) {
            this.page = page;
            this.loadCasualDeduction(page);
        },
        deletePayroll(item){
            this.selectedDeduction = item;
            this.deleteModal = true;
        },
       
        editDeduction(item){
            this.selectedDeduction = item;
            this.editModal = true;
        },
        confirmDelete(value){
            this.$http.delete(`/api/payroll/casual/deduction/${value}`).then(response => {
                let mode = response.data.error == null ? "success" : "warning";
                this.snackbar = {
                    status: mode,
                    message: response.data.message,
                    show: true,
                };

                this.deleteModal = false;
                this.loadCasualDeduction(1);
                
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to create payroll, Please contact MIS for support",
                    show: true,
                };
            });
        },

        submitDeduction(value){
            this.$http.post(`/api/payroll/casual/deduction`,value).then(response => {
                let mode = response.data.error == null ? "success" : "warning";
                this.snackbar = {
                    status: mode,
                    message: response.data.message,
                    show: true,
                };
                this.deductionModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.deductionModal = false;

            });
            this.loadCasualDeduction(1);

        },

        submitUpdateDeduction(value){
            this.$http.put(`/api/payroll/casual/deduction/${value.id}`,value).then(response => {
                let mode = response.data.error == null ? "success" : "warning";
                this.snackbar = {
                    status: mode,
                    message: response.data.message,
                    show: true,
                };
                this.editModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.editModal = false;

            });
            this.loadCasualDeduction(1);

        },


        ///right table function
        loadEmpExcemptDeduction(pageEmployee){
            this.$http.get(`/api/payroll/casual/deduction/employee-deduction-exempt?page=${pageEmployee}&search=${this.searchEmployee}`).then(response => {
                this.employeeExemptList = response.data.data
                this.pageLengthEmployee = response.data.last_page
                this.searchEmployee = '';
            });
        },
        loadEmployeees(){
            this.$http.get(`/api/employees/list`).then(response => {
                this.employees = response.data
            });
        },
        submitExempt(value){
            this.$http.post(`/api/payroll/casual/deduction/employee-deduction-exempt`,value).then(response => {
                let mode = response.data.error == null ? "success" : "warning";
                this.snackbar = {
                    status: mode,
                    message: response.data.message,
                    show: true,
                };
                this.exemptModal = false;
                this.loadEmpExcemptDeduction(1);

            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.exemptModal = false;

            });
        },
        setPaginationEmployee(page) {
            this.pageEmployee = page;
            this.loadEmpExcemptDeduction(page);
        },

        deleteExempt(item){
            this.selectedEmployee = item;
            this.deleteModalExempt = true;
        },
        confirmDeleteExempt(value){
            this.$http.delete(`/api/payroll/casual/deduction/employee-deduction-exempt/${value}`).then(response => {
                let mode = response.data.error == null ? "success" : "warning";
                this.snackbar = {
                    status: mode,
                    message: response.data.message,
                    show: true,
                };

                this.deleteModalExempt = false;
                this.loadEmpExcemptDeduction(1);
                
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to create payroll, Please contact MIS for support",
                    show: true,
                };
            });
            this.deleteModalExempt = false
        },
        
    }
}
</script>
