<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Casual_ATM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CasualATMController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth()->user();

        $casualWOPay = Casual_ATM::when($request->search != null, function ($query) use ($request) {
            $query->whereHas('employees', function ($q) use ($request) {
                $q->where('last_name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('first_name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('middle_name', 'LIKE', '%' . $request->search . '%');
            });
        })->whereHas('employees', function ($q) use ($request) {
            $q->when(auth()->user()->hasRoles('PAYROLL_OFFICER'), function ($query) {
                $query->where('department_id', auth()->user()->department);
            });
        })->paginate(10);

        return $casualWOPay;
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('PAYROLL_OFFICER')) {
                $atm = Casual_ATM::updateOrCreate(['employee_id' => $request->employee_id], [
                    'employee_id' => $request->employee_id,
                    'employee_atm' => $request->employee_atm,
                    'isLock' => $request->isLock,
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully added ATM for this employee",
                    "error" => null, // 10001 permission required
                    "data" => $atm
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to add ATM for employee, Please contact PSU for support",
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('PAYROLL_OFFICER')) {
                $atm = Casual_ATM::updateOrCreate(['id' => $id], [
                    'employee_id' => $request->employee_id,
                    'employee_atm' => $request->employee_atm,
                    'isLock' => 1,
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully update employee ATM record",
                    "error" => null, // 10001 permission required
                    "data" => $atm
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access update the ATM of this employee, Please contact PSU for support",
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateLock(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $user = Auth()->user();
            if ($request->lock) {
                $lock = Casual_ATM::updateOrCreate(["id" => $request->id], [
                    'isLock' => $request->lock,
                ]);
                $response = [
                    "message" => "Successfully lock atm for encoding",
                    "error" => null, // 10001 permission required
                    "data" => $lock
                ];
            } else {
                if ($user->hasRoles('Admin') || $user->hasRoles('PSU')) {
                    $lock = Casual_ATM::updateOrCreate(["id" => $request->id], [
                        'isLock' => $request->lock,
                    ]);


                    $response = [
                        "message" => "Successfully unlock atm for encoding",
                        "error" => null, // 10001 permission required
                        "data" => $lock
                    ];
                } else {
                    $response = [
                        "message" => "You don't have the right access to unlock this atm, Please contact PSU for support",
                        "error" => 10001, // 10001 permission required
                        "data" => null
                    ];
                }
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
            $user = Auth()->user();
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU')) {
                $deduction = Casual_ATM::findOrFail($id)->delete();
                if ($deduction) {
                    $response = [
                        "message" => "Successfully deleted ATM",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access delete the ATM of this employee, Please contact PSU for support",
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
