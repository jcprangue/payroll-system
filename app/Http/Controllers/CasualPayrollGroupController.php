<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Model\casual_payroll_group;
class CasualPayrollGroupController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Inertia::render('Casual-Group/index');
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
           
            $payroll = casual_payroll_group::updateOrCreate(["department" => auth()->user()->department,"group_name"=>$request->name],[
                'department' => auth()->user()->department,
                'group_name' => $request->input('name'),
                'status' => 1,
                
            ]);

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
        //
    }
}
