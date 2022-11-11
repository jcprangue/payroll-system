<template>
    <app-layout>
        <SnackbarMessage :snackbar="snackbar" />

        <v-toolbar flat>
            <v-toolbar-title>
                Casual Payroll Group 
            </v-toolbar-title>
            <v-layout align-end justify-end class="mr-5">
                <div xs2 text-right class="mr-2" v-if="$can('sync_casual_info')">
                    <v-btn
                    color="secondary"
                    @click="syncCasual()"
                    >Sync Casual Information</v-btn>
                </div>
                <div xs2 text-right v-if="$can('add_casual_group')">
                    <v-btn
                    color="info"
                    @click="payrollGroupModal = true"
                    >Add New Casual Group</v-btn>
                </div>
            </v-layout>
        </v-toolbar>

        <div class="pa-5">
            <v-layout row wrap>
                <v-flex xs6 sm6 md6 class="mt-5" style="border-right:3px solid grey">
                    <v-flex xs6 sm6 md6>
                        
                        <v-text-field
                            v-model="search"
                            label="Search for employee"
                            single-line
                            outlined
                            v-on:keyup.enter="loadEmployees(1)"
                        ></v-text-field>
                        <v-spacer></v-spacer>
                        <v-spacer></v-spacer>
                    </v-flex>

                    
                    <v-data-table
                        class="mr-5"
                        :headers="headers"
                        :items="employees"
                        :hide-default-footer="true"
                        item-key="id">
                        <template v-slot:item.name="{  item }">
                            <label for="" class="font-weight-bold">
                                {{ capitalizeFirstLetter(item.last_name) }} {{ capitalizeFirstLetter(item.first_name) }}, {{ capitalizeFirstLetter(item.middle_name) }}
                                <small class="green--text" v-show="item.type == 1">(Contract of Service)</small>    
                                <small class="purple--text" v-show="item.type == 2">(Casual)</small>    
                                <small class="purple--text" v-show="item.type == 3">(Contractual)</small>    
                                <small class="purple--text" v-show="item.type == 4">(Job Order)</small>    
                                <small class="red--text" v-show="item.type == null">(Not Set)</small>    
                            </label>
                        </template>

                        <template v-slot:item.action="{  item }">
                            <v-btn color="primary" dark small @click="showGroup(item)">
                                <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>&nbsp;Group
                            </v-btn>

                            <v-btn color="green" dark small @click="showChargingDialog(item,1)">
                                <v-icon class="white--text" :icon="['fas', 'check']"></v-icon>&nbsp;Charging
                            </v-btn>

                            <v-btn color="info" dark small @click="setEmployeeDialog(item)">
                                <v-icon class="white--text" :icon="['fas', 'file']"></v-icon>&nbsp;Set
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

                <v-flex xs6 sm6 md6 class="mt-5 pl-5">
                    <h2 class="mb-3">List of Groups</h2>
                    <v-expansion-panels>
                        <v-expansion-panel v-for="(group,i) in groups" :key="i">
                        <v-expansion-panel-header>
                            <v-row no-gutters>
                                <v-col>
                                    {{group.group_name}}
                                    <small class="green--text" v-show="group.payroll_type == 1">(Contract of Service)</small>    
                                    <small class="purple--text" v-show="group.payroll_type == 2">(Casual)</small>    
                                    <small class="purple--text" v-show="group.payroll_type == 3">(Contractual)</small>    
                                    <small class="blue--text" v-show="group.payroll_type == 4">(Job Order)</small>  
                                    <v-btn icon @click="editGroup(group)">
                                        <v-icon :icon="['fas', 'pencil-alt']"></v-icon>
                                    </v-btn>

                                </v-col>
                                <v-col class="text-right pr-3">
                                    <small v-if="group.department_charging_id != null">(Charge to {{group.department_charging.department_initial}})</small>
                                    <v-btn small
                                    @click="showPayrollModal(group)"
                                    dark
                                    >
                                    Payroll
                                    </v-btn>

                                    <v-btn small
                                    @click="showObrModal(group)"
                                    dark
                                    >
                                    OBR
                                    </v-btn>

                                    <v-btn small
                                    @click="showDeleteModal(group)"
                                    color="red"
                                    class=" white--text"
                                    >
                                    DELETE
                                    </v-btn>
                                </v-col>

                              
                            </v-row>
                            
                                
                        </v-expansion-panel-header>
                        <v-expansion-panel-content>
                            <v-list>
                                <v-list-item-group>
                                    <v-list-item
                                    v-for="(employee, count) in group.employees" :key="count">
                                        <!-- <v-list-item-icon>
                                            <v-icon v-text="item.icon"></v-icon>
                                        </v-list-item-icon> -->
                                        <v-list-item-content>
                                            <v-list-item-title>
                                                    {{ capitalizeFirstLetter(employee.last_name) }} {{ capitalizeFirstLetter(employee.first_name) }}, {{ capitalizeFirstLetter(employee.middle_name) }}
                                                    <v-btn color="primary" dark small @click="showGroup(employee)">
                                                        <v-icon class="white--text" :icon="['fas', 'pencil-alt']"></v-icon>&nbsp;Group
                                                    </v-btn>
                                                    <v-btn color="green" dark small @click="showChargingDialog(employee,1)">
                                                        <v-icon class="white--text" :icon="['fas', 'check']"></v-icon>&nbsp;Charging
                                                    </v-btn>
                                                    <v-btn color="info" dark small @click="setEmployeeDialog(employee)">
                                                        <v-icon class="white--text" :icon="['fas', 'file']"></v-icon>&nbsp;Set
                                                    </v-btn>

                                            </v-list-item-title>
                                        </v-list-item-content>
                                    </v-list-item>
                                </v-list-item-group>
                            </v-list>
                        </v-expansion-panel-content>
                        </v-expansion-panel>
                    </v-expansion-panels>
                </v-flex>
            </v-layout>
        </div>

        <!-- payroll dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="printPayrollModal"
            max-width="300">

           <Payroll
                :group="selectedGroup"
                @close="printPayrollModal = false"
                @submit="submitPayroll"
            />
        </v-dialog>
        <!-- end modal payroll -->

        <!-- obr dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="printObrModal"
            max-width="300">

           <OBR
                :group="selectedGroup"
                @close="printObrModal = false"
                @submit="submitObr"
            />
        </v-dialog>
        <!-- end modal obr -->

        <v-dialog
            content-class="app-modal"
            v-model="editPayrollGroupModal"
            max-width="300">

           <EditCasualGroup
                :group="selectedGroup"
                :departments="departments"
                @close="editPayrollGroupModal = false"
                @submit="submitEditGroup"
            />
        </v-dialog>
        
        <!-- delete dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="payrollGroupModal"
            max-width="300">

           <CasualGroup
                :departments="departments"
                @close="payrollGroupModal = false"
                @submit="submitGroup"
            />
        </v-dialog>
        <!-- end modal history -->

        <!-- group dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="groupModal"
            max-width="350">

           <GroupModal
                :employee="selectedEmployee"
                :groups="groups"
                @close="groupModal = false"
                @submit="submitGroupEmployee"
                @ungroup="submitUngroupEmployee"
            />
        </v-dialog>
        <!-- end modal history -->

        <!-- set dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="setModal"
            max-width="350">

           <SetEmployee
                :employee="selectedEmployee"
                @close="setModal = false"
                @submit="submitSetModal"
            />
        </v-dialog>
        <!-- end modal history -->

        <!-- set charging -->
        <v-dialog
            content-class="app-modal"
            v-model="chargingModal"
            max-width="500">

           <ChargingEmployee
                :chargingList="listCharging"
                :employee="selectedEmployee"
                @close="chargingModal = false"
                @submit="submitCharging"
            />
        </v-dialog>
        <!-- end modal history -->

         <!-- delete dialog -->
        <v-dialog
            content-class="app-modal"
            v-model="deleteModal"
            max-width="300">

           <DeleteGroup
                :item="selectedGroup"
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
import CasualGroup from '@/Pages/Casual-Group/addCasualGroup';
import EditCasualGroup from '@/Pages/Casual-Group/editCasualGroup';
import Payroll from '@/Pages/Casual-Group/payroll';
import OBR from '@/Pages/Casual-Group/obr';
import GroupModal from '@/Pages/Casual-Group/groupCasualModal';
import SetEmployee from '@/Pages/Casual-Group/employeeSetModal';
import ChargingEmployee from '@/Pages/Casual-Group/chargingModal';
import DeleteGroup from '@/Pages/DeleteModal';

export default {
    components: {
        AppLayout,
        SnackbarMessage,
        CasualGroup,
        GroupModal,
        SetEmployee,
        ChargingEmployee,
        Payroll,
        OBR,
        DeleteGroup,
        EditCasualGroup

    },
    data: () => ({
        selectedPayroll:[],
        payrollGroupModal: false,
        editPayrollGroupModal: false,
        printPayrollModal: false,
        printObrModal: false,
        snackbar: {
            status: "",
            message: "",
            show: false,
        },
        deleteModal:false,

        headers: [
            {
                text: "Employee Name",
                align: "left",
                value: "name",
            },
            {
                text: "Action",
                value: "action",
            },
        ],
        employees:[],
        listCharging:[],
        search:'',
        page:1,
        pageLength:1,
        selectedEmployee:'',
        groupModal:false,
        setModal:false,
        chargingModal:false,
        groups:[],
        departments: [],
        selectedGroup:''

        
    }),
    mounted(){
        this.loadEmployees(1);
        this.loadGroups();
        this.loadListCharging();
        this.loadDepartments();
    },  
  
    methods: {
        showPayrollModal(group) {
            this.printPayrollModal = true
            this.selectedGroup = group
        },

        showObrModal(group) {
            this.printObrModal = true
            this.selectedGroup = group
        },

        showDeleteModal(group) {
            this.deleteModal = true
            this.selectedGroup = group
        },

        capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        },
        loadListCharging(){
            // this.$http.get(`/api/payroll-officer/list/charging`).then(response => {
            //     this.listCharging = response.data
            // });

            this.$http.get(`/api/payroll-officer/charging`).then(response => {
                this.listCharging = response.data
            });
        },
     
        loadEmployees(page){
            this.$http.get(`/api/employees?page=${page}&search=${this.search}`).then(response => {
                this.employees = response.data.data
                this.pageLength = response.data.last_page
                // this.search = '';
            });
        },
        loadGroups(){
            this.$http.get(`/api/groups/department`).then(response => {
                this.groups = response.data
            });
        },
        loadDepartments(){
            this.$http.get(`/api/department`).then(response => {
                this.departments = response.data
            });
        },
        setPagination(page) {
            this.page = page;
            this.loadEmployees(page);
        },
        submitGroup(value){
            this.$http.post(`/api/payroll/groups`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                    this.loadGroups();


                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.payrollGroupModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to create payroll group, Please contact MIS for support",
                    show: true,
                };
                this.payrollGroupModal = false;

            });
        },

        submitEditGroup(value){
            this.$http.put(`/api/payroll/groups/${this.selectedGroup.id}`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                    this.loadGroups();


                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.editPayrollGroupModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to update payroll group, Please contact MIS for support",
                    show: true,
                };
                this.editPayrollGroupModal = false;

            });
        },

        showGroup(item){
            this.groupModal = true;
            this.selectedEmployee = item;
        },
        editGroup(group){
            this.editPayrollGroupModal = true;
            this.selectedGroup = group;
        },
        setEmployeeDialog(item){
            this.setModal = true;
            this.selectedEmployee = item;
        },
        showChargingDialog(item){
            this.chargingModal = true;
            this.selectedEmployee = item;
        },

        submitGroupEmployee(value){
            this.$http.post(`/api/employees/groups`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                    
                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.loadEmployees(1);
                this.loadGroups();
                this.groupModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.groupModal = false;

            });
           
        },

        submitPayroll(value) {
            this.printPayrollModal = false
             window.open(`/api/monitoring/generatePayroll/print/${value.id}?month=${value.month}&quincena=${value.period}`, '_blank').focus();
        },

        submitObr(value){
             window.open(`/api/monitoring/generateOBR/print/${value.id}?month=${value.month}&quincena=${value.period}`, '_blank').focus();

        },

        submitUngroupEmployee(value){
            this.$http.post(`/api/employees/ungroups`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                    
                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.loadEmployees(1);
                this.loadGroups();
                this.groupModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.groupModal = false;

            });
           
        },

        submitCharging(value){
            console.log("charging",value)
            this.$http.post(`/api/employees/setCharging`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.loadEmployees(1);
                this.chargingModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.chargingModal = false;

            });
        },

        submitSetModal(value){
            this.$http.post(`/api/employees/set`,value).then(response => {
                if (response.data.error == null){
                    this.snackbar = {
                        status: "success",
                        message: response.data.message,
                        show: true,
                    };
                }else{
                    this.snackbar = {
                        status: "warning",
                        message: response.data.message,
                        show: true,
                    };
                }
                this.loadEmployees(1);
                this.setModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to approve payroll, Please contact MIS for support",
                    show: true,
                };
                this.setModal = false;

            });
        },

        syncCasual() {
                this.$http
                .get(`/api/casual-list/sync`)
                .then((response) => {
                    this.snackbar = {
                    status: "success",
                    message: response.data.message,
                    show: true,
                    };
                })
                .catch((errorResponse) => {
                self.snackbar = {
                    status: "warning",
                    message: "Failed to sync portal list, Please contact MIS for support",
                    show: true,
                };
                });
            },


        submitDelete(value){
            this.$http.delete(`/api/payroll/groups/${value}`).then(response => {
                let status = response.data.error == null ? 'success' : 'warning';
                this.snackbar = {
                    status: status,
                    message: response.data.message,
                    show: true,
                };
                this.loadGroups();
                this.loadEmployees(1);

                this.deleteModal = false;
            }).catch((errorResponse) => {
                this.snackbar = {
                    status: "warning",
                    message: "Failed to delete group, Please contact MIS for support",
                    show: true,
                };
                this.deleteModal = false;

            });
        }
    }
}
</script>
