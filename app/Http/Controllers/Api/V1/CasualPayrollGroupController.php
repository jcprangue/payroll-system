<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\casual_employee;
use Illuminate\Http\Request;
use App\Models\casual_payroll_group;
use App\Models\departments;
use App\Models\PayrollControlMonitoring;
use DB;
class CasualPayrollGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $casualGroups = casual_payroll_group::where('status',1)->get();
        return $casualGroups;
    }

    public function fetchControlNumbers()
    {
        $casualGroupsControlNumber = PayrollControlMonitoring::with('payroll_group')->get();
        return $casualGroupsControlNumber;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function groupByDept()
    {
        $user = auth()->user();
        $casualGroups = casual_payroll_group::where('department',$user->department)->where('status',1)->get();
        return $casualGroups;
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
            if ($user->hasRoles('PAYROLL_OFFICER')){
                $dept = $user->department;
                $group = casual_payroll_group::firstOrCreate ([
                    'department' => $dept,
                    'group_name' => $request->name,
                    'status' => 1,
                    'payroll_type' => $request->type,
                    'with_hazard' => 0,
                    'department_charging_id' => $request->department_charging_id
                ]);

                $response = [
                    "message" => "Successfully added new casual group",
                    "error" => null, // 10001 permission required
                    "data" => $group
                ];

            }else{
                $response = [
                    "message" => "You don't have the right access to add group, Please contact PSU for support",
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
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasRoles('PAYROLL_OFFICER')){
                $dept = $user->department;
                $group = casual_payroll_group::updateOrCreate (["id" => $id],[
                    'group_name' => $request->name,
                    'payroll_type' => $request->type,
                    'department_charging_id' => $request->department_charging_id
                ]);

                $response = [
                    "message" => "Successfully update casual group",
                    "error" => null, // 10001 permission required
                    "data" => $group
                ];

            }else{
                $response = [
                    "message" => "You don't have the right access to add group, Please contact PSU for support",
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
            $employees = casual_employee::where('group_id',$id)->get();

            foreach ($employees as $key => $employee) {
                $employee->group_id = null;
                $employee->save();
            }

            $group = casual_payroll_group::findOrFail($id)->delete();


            

            if ($group) {
                $response = [
                    "message" => "Successfully deleted payroll group",
                    "error" => null, // 10001 permission required
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

    public function sync(){
                
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://192.168.255.5/pgomadmin/pages/menus/getCasualInfo.php?token=saasdsad',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_POSTFIELDS =>'{
            "token":"asdsaddasd"
        }',
        CURLOPT_HTTPHEADER => array(
            'Accept: application/json',
            'Content-Type: application/json'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response);

        foreach ($json as $key => $value) {
            casual_employee::updateOrCreate(["employee_id" => $value->employee_id],[
                "last_name" => $value->last_name,
                "first_name" => $value->first_name,
                "middle_name" => $value->middle_name,
                "salary" => $value->salary,
                "department_id" => $value->department_id,
                "position_id" => $value->position_id,
                "tax" => $value->tax,
                "status" => $value->status
            ]);
        }

         $response = [
            "message" => "Successfully Sync Casual Info",
            "error" => null, // 10001 permission required
            "data" => null
        ];

        return $response;

    }
}
