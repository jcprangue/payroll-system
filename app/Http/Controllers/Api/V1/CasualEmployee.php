<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\casual_employee;
use App\Models\casual_payroll_group;

class CasualEmployee extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
       
        $employees = casual_employee::when(auth()->user()->hasRoles('PAYROLL_OFFICER'), function ($query) {
            $query->where('department_id', auth()->user()->department);
        })
            ->where('status', 0)
            ->when($request->search != null, function ($query) use ($request) {
                $query->where('last_name', 'LIKE', $request->search . '%');
                $query->orWhere('first_name', 'LIKE', $request->search . '%');
                $query->orWhere('middle_name', 'LIKE', $request->search . '%');
            })
            ->whereNull('group_id')
            ->paginate(10);
        return $employees;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employees(Request $request)
    {
        $employees = casual_employee::when(auth()->user()->hasRoles('PAYROLL_OFFICER'), function ($query) {
            $query->where('department_id', auth()->user()->department);
        })
            ->where('status', 0)
            ->orderBy('last_name')
            ->orderBy('first_name')
            ->get();
        return $employees;
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function setGroup(Request $request)
    {
        //check if payroll group is casual or contractual
        $groupInfo = casual_payroll_group::find($request->group_id);
        $employee = casual_employee::findOrFail($request->employee_id);
        if ($employee->type) {
            if ($employee->type == $groupInfo->payroll_type) {
                //do update
                $employee->group_id = $request->group_id;
                $employee->save();

                $response = [
                    "message" => "Successfully group employee # " . $employee->employee_id,
                    "error" => null,
                    "data" => $employee,
                ];
            } else {
                $typeMethod_casual =  ($employee->type == 1) ? "Casual" : "Contractual";
                $typeMethod_contractual =  ($employee->type == 2) ? "Contractual" : "Casual";
                $response = [
                    "message" => "You cannot group this employee to this payroll",
                    "error" => 10002, // 10002 grouping failed
                    "data" => null,
                ];
            }
        } else { //null employee type
            $response = [
                "message" => "Please set employee type first before do the grouping",
                "error" => 10002, // 10002 grouping failed
                "data" => null,
            ];
        }

        return $response;
        // $group = casual_employee()
    }

    public function unsetGroup(Request $request)
    {
        $employee = casual_employee::findOrFail($request->employee_id);
        $employee->group_id = null;
        $employee->save();

        $response = [
            "message" => "Successfully ungroup employee",
            "error" => null, // 10002 grouping failed
            "data" => $employee,
        ];
        return $response;
        // $group = casual_employee()
    }

    public function setEmployee(Request $request)
    {

        try {
            $casual = casual_employee::find($request->employee_id);
            $casual->salary = $request->salary;
            $casual->type = $request->type;
            $casual->tax = $request->tax;
            $casual->save();

            $response = [
                "message" => "Successfully set employee",
                "error" => null, // 10002 grouping failed
                "data" => $casual,
            ];

            return $response;
        } catch (\Throwable $th) {
            $response = [
                "message" => "Failed to update employee",
                "error" => 40001,
                "data" => null,
            ];

            return $response;
        }
    }

    public function setCharging(Request $request)
    {

        try {
            $casual = casual_employee::find($request->id);
            $casual->charging_id = $request->charging_id;
            $casual->save();

            $response = [
                "message" => "Successfully set employee",
                "error" => null, // 10002 grouping failed
                "data" => $casual,
            ];

            return $response;
        } catch (\Throwable $e) {
            $response = [
                "message" => $e->getMessage(),
                "error" => 40001,
                "data" => null,
            ];

            return $response;
        }
    }
}
