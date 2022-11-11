<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\casual_charging;
use DB;
class CasualChargingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $charging = casual_charging::with('children')
                        ->whereNull('parent_id')
                        ->where('department_id',auth()->user()->department)
                        ->get();

        return $charging;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function listCharging()
    {
        $charging = casual_charging::where('department_id',auth()->user()->department)
                        ->get();
                        
        return $charging;
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
            if ($user->hasRoles('PAYROLL_OFFICER') || $user->hasRoles('Admin')){
                $deduction = casual_charging::create([
                    "charging_name" => $request->charging_name,
                    "parent_id" => $request->parent_id,
                    "code" => $request->code,
                    "accounts" => $request->accounts,
                    "kra_charging" => $request->kra_charging,
                    "department_id" => auth()->user()->department,
                    "is_visible" => $request->is_visible,

                ]);
    
                DB::commit();
                $response = [
                    "message" => "Successfully added new charging",
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
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($user->hasRoles('PAYROLL_OFFICER')){
                $deduction = casual_charging::updateOrCreate(["id" => $id],[
                    'parent_id' => $request->parent_id,
                    'charging_name' => $request->charging_name,
                    "code" => $request->code,
                    "kra_charging" => $request->kra_charging,
                    "accounts" => $request->accounts,
                    "is_visible" => $request->is_visible,
                ]);
    
                DB::commit();
                $response = [
                    "message" => "Successfully update charging",
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deduction = casual_charging::findOrFail($id)->delete();
            if ($deduction){
                $response = [
                    "message" => "Successfully deleted charging",
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
}
