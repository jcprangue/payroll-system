<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\casual_deduction;
use DB;

class CasualDeductionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $deductionList = casual_deduction::when($request->search != null, function ($query) use ($request) {
            $query->whereHas('deductions', function ($q) use ($request) {
                $q->where('deduction_nick', 'LIKE', '%' . $request->search . '%');
            });
        })
            ->paginate(10);

        return $deductionList;
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU')) {
                $dateFormat = date("Y-m-01", strtotime($request->date));
                $deduction = casual_deduction::updateOrCreate(["deduction_id" => $request->deduction], [
                    'deduction_id' => $request->deduction,
                    'amount' => $request->amount,
                    'date_start' => $dateFormat
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully added new deduction",
                    "error" => null, // 10001 permission required
                    "data" => $deduction
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU')) {
                $dateFormat = date("Y-m-01", strtotime($request->date));
                $deduction = casual_deduction::updateOrCreate(["id" => $request->id], [
                    'deduction_id' => $request->deduction,
                    'amount' => $request->amount,
                    'date_start' => $dateFormat
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully update deduction",
                    "error" => null, // 10001 permission required
                    "data" => $deduction
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
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $deduction = casual_deduction::findOrFail($id)->delete();
            if ($deduction) {
                $response = [
                    "message" => "Successfully deleted deduction",
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
