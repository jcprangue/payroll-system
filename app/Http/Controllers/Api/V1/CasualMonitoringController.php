<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\casual_monitoring;
use App\Models\casual_monitoring_remarks;
use App\Models\Casual_Payroll_Atm;
use App\Models\casual_payroll_group;
use App\Models\payroll_casual_records;
use App\Models\PayrollControlMonitoring;
use App\Traits\HandleResponse;
use App\Traits\PayrollResponse;
use DB;

class CasualMonitoringController extends Controller
{
    use HandleResponse, PayrollResponse;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth()->user();
        $status = $this->getListStatus();
        $payrollRecords = casual_monitoring::when($user->hasRoles('PAYROLL_OFFICER'), function ($query) use ($user) {
            $query->where('department_id', $user->department);
        })
            ->when($request->quincena != null, function ($query) use ($request) {
                $query->where('quincena', $request->quincena);
            })
            ->whereIN('status', $status)
            ->when($request->search != null, function ($query) use ($request) {

                $query->whereHas('payrollGroups', function ($q) use ($request) {
                    $q->where('group_name', 'LIKE', $request->search . '%');
                });
            })
            ->when($request->month != null, function ($query) use ($request) {
                if ($request->month == "null"){

                }else{
                    $month = date('Y-m-01', strtotime($request->month));
                    $query->where('month', $month);
                }
            })
            ->when($request->control_number != null, function ($query) use ($request) {
                $query->where('control_number', $request->control_number);
            })
            ->paginate(10);

        return $payrollRecords;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('HR')) {
                $dateFormat = date("Y-m-01", strtotime($request->date));
                $departmentID = casual_payroll_group::find($request->input('payroll_group'));
                $payroll = casual_monitoring::updateOrCreate(["casual_payroll_group" => $request->payroll_group, "month" => $dateFormat], [
                    'control_number' => $request->input('control_number'),
                    'casual_payroll_group' => $request->input('payroll_group'),
                    'department_id' => $departmentID->department,
                    'status' => $request->input('status'),
                    'month' => $dateFormat,
                    'quincena' => $request->input('period'),
                    'amount' => $request->input('amount'),
                    'remarks' => $request->input('remarks'),
                    'user_id' => auth()->user()->id
                ]);

                PayrollControlMonitoring::where('control_number',$request->input('control_number'))->delete();

                $remarks = casual_monitoring_remarks::create([
                    "casual_monitoring_id" => $payroll->id,
                    "status" => $request->input('status'),
                    'user_id' => auth()->user()->id,
                    'remarks' => $request->input('remarks'),
                    'method' => 1 // 1 is approve
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully added new payroll",
                    "error" => null, // 10001 permission required
                    "data" => $payroll
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to add payroll, Please contact PSU for support",
                    "error" => 10001, // 10001 permission required
                    "data" => null
                ];
            }

            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        } //end try

        return false;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('HR')) {
                $dateFormat = date("Y-m-01", strtotime($request->date));
                $departmentID = casual_payroll_group::find($request->input('payroll_group'));

                $payroll = casual_monitoring::updateOrCreate(["id" => $request->id], [
                    'casual_payroll_group' => $request->input('payroll_group'),
                    'department_id' => $departmentID->department,
                    'status' => $request->input('status'),
                    'month' => $dateFormat,
                    'quincena' => $request->input('period'),
                    'amount' => $request->input('amount'),
                    'remarks' => $request->input('remarks'),
                    'user_id' => auth()->user()->id
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully updated payroll",
                    "error" => null, // 10001 permission required
                    "data" => $payroll
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to update payroll, Please contact PSU for support",
                    "error" => 10001, // 10001 permission required
                    "data" => null
                ];
            }

            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        } //end try

        return false;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $user = Auth()->user();
            if ($user->hasPermission('delete_payroll')) {
                $payroll = casual_monitoring::findOrFail($id)->delete();
                if ($payroll) {
                    $response = [
                        "message" => "Successfully deleted payroll",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access to delete this payroll, Please contact PSU for support",
                    "error" => 10001, // 10001 permission required
                    "data" => null
                ];
            }

            return $response;
        } catch (\Throwable $e) {
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function approve(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasPermission('approve_payroll')) {
                // $status = $this->nextStatus($request->status);
                $payroll = casual_monitoring::updateOrCreate(["id" => $request->id], [
                    "status" => $request->status,
                    "remarks" => $request->remarks
                ]);

                $remarks = casual_monitoring_remarks::create([
                    "casual_monitoring_id" => $payroll->id,
                    "status" => $payroll->status,
                    'user_id' => auth()->user()->id,
                    'remarks' => $payroll->remarks,
                    'method' => $request->method
                ]);



                if ($payroll) {
                    $response = [
                        "message" => "Successfully update payroll",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access to approve this payroll, Please contact PSU for support",
                    "error" => 10001, // 10001 permission required
                    "data" => null
                ];
            }
            DB::commit();
            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        }
    }



    public function payrollStatus()
    {
        $status = $this->getAllowedStatus();
        return $status;
    }
    public function payrollApproveStatus()
    {
        $status = $this->getAllowedStatusApprove();
        return $status;
    }
    public function allStatus()
    {
        $status = $this->fetchStatus();
        return $status;
    }


    public function listofDebit(Request $request)
    {
        $monitoring = casual_monitoring::whereIN('id', $request->payroll_ids)->get();
        $allDetails = [];
        $count = 0;
        foreach ($monitoring as $key => $payroll) {
            $payroll_records = payroll_casual_records::where('casual_payroll_group_id', $payroll->casual_payroll_group)
                ->where('month', $payroll->month)->first();
            if ($payroll_records){
                $detailsEmployee = json_decode($payroll_records->data);

                $payrollDetails = collect($detailsEmployee->data)->map(function ($query) {
                    $generalListATM = [];
                    foreach ($query as $key => $employee) {
                        $employee = (array) $employee;
                        $generalListATM[] = [
                            "employee_id" => $employee["Employee Number"],
                            "employee_name" => $employee["Employee Name"],
                            "amount" => $employee["Netpay"]
                        ];
                    }
    
                    return $generalListATM;
                })->values();
    
                foreach ($payrollDetails as $key => $charging) {
                    foreach ($charging as $value) {
                        $count++;
                        $allDetails[]  = [
                            "count" => $count,
                            "monitoring_id" => $payroll->id,
                            "employee_id" => $value["employee_id"],
                            "employee_name" => $value["employee_name"],
                            "amount" => $this->floatvalsafe($value["amount"]),
                            "month" => $payroll->month
                        ];
                    }
                }
            }                
            
        }

        return $allDetails;
    }

    public function saveDebit(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasRoles('Admin') || $user->hasRoles('PTO')) {
                if (count($request->items) > 0) {
                    foreach ($request->items as $key => $debitAmount) {
                        $debit = Casual_Payroll_Atm::updateOrCreate(["employee_id" => $debitAmount['employee_id'], 'month' => $debitAmount["month"]], [
                            'employee_id' => $debitAmount['employee_id'],
                            'amount' => $debitAmount['amount'],
                            'month' => $debitAmount['month'],
                            'batch' => $request->batch
                        ]);
                        $monitoring = casual_monitoring::where('id', $debitAmount['monitoring_id'])->first();
                        $monitoring->status = 8;
                        $monitoring->save();
                    }
                    $msg = "Successfully generate debit value";
                } else {
                    $msg = "No records to generate";
                    $monitoring = null;
                }

                DB::commit();
                $response = [
                    "message" => $msg,
                    "error" => null, // 10001 permission required
                    "data" => $monitoring
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to generate debit, Please contact PSU for support",
                    "error" => 10001, // 10001 permission required
                    "data" => null
                ];
            }

            return $response;
        } catch (\Exception $e) {
            DB::rollback();
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        } //end try

        return false;
    }
}
