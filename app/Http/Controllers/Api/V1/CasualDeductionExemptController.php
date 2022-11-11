<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\casual_deduction_exempt;
use DB;

class CasualDeductionExemptController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $exempt = casual_deduction_exempt::when($request->search != null, function ($query) use ($request) {
                            $query->whereHas('casualEmployee', function ($q) use ($request) {
                                $q->where('last_name','LIKE', '%' . $request->search . '%');
                                $q->orWhere('first_name','LIKE', '%' . $request->search . '%');
                                $q->orWhere('middle_name','LIKE', '%' . $request->search . '%');
                            });
                        })
                        ->paginate(10);

        return $exempt;
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU')){
                $dateFormat = date("Y-m-01",strtotime($request->date));
                $deduction = casual_deduction_exempt::firstOrCreate([
                    "casual_employee_id" => $request->employee,
                    "deduction_id" => $request->deduction,
                    "month" => $dateFormat
                ]);
    
                DB::commit();
                $response = [
                    "message" => "Successfully added new employee exemption",
                    "error" => null, // 10001 permission required
                    "data" => $deduction
                ];

            }else{
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
            $deduction = casual_deduction_exempt::findOrFail($id)->delete();
            if ($deduction){
                $response = [
                    "message" => "Successfully deleted employee exempt",
                    "error" => null, // 10001 permission required
                    "data" => null
                ];
            }
           
            return $response;
        } catch (\Throwable $th) {
            return [
                "message" => $e->getMessage(),
                "error" => 40001, // System error
                "data" => null
            ];
        }
    }
}
