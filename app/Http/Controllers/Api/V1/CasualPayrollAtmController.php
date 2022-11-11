<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Casual_Payroll_Atm;
use Illuminate\Http\Request;
use DB;

class CasualPayrollAtmController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $batch = Casual_Payroll_Atm::distinct()->get(['batch']);
        return $batch;
    }

    public function getATM($batch)
    {
        $batch = Casual_Payroll_Atm::where('batch', $batch)->paginate(10);

        return $batch;
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('PTO')) {
                $month = date('Y-m-01', strtotime($request->date));
                $debit = Casual_Payroll_Atm::updateOrCreate(["employee_id" => $request->employee_id, "month" => $month], [
                    'employee_id' => $request->employee_id,
                    'amount' => $request->amount,
                    'month' => $month,
                    'batch' => $request->batch,
                ]);


                DB::commit();
                $response = [
                    "message" => "Successfully added new Debit value",
                    "error" => null, // 10001 permission required
                    "data" => $debit
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to debit value, Please contact PSU for support",
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
        try {
            $user = Auth()->user();
            if ($user->hasRoles('Admin') || $user->hasRoles('PTO')) {
                $deleteSalary = Casual_Payroll_Atm::findOrFail($id)->delete();
                if ($deleteSalary) {
                    $response = [
                        "message" => "Successfully deleted ATM for debit",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access to delete this salary record, Please contact PSU for support",
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
}
