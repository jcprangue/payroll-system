<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\casual_withoutpay;
use Illuminate\Http\Request;
use DB;

class CasualWithoutpayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth()->user();
        $casualWOPay = casual_withoutpay::when($request->search != null, function ($query) use ($request) {
            $query->whereHas('employees', function ($q) use ($request) {
                $q->where('last_name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('first_name', 'LIKE', '%' . $request->search . '%');
                $q->orWhere('middle_name', 'LIKE', '%' . $request->search . '%');
            });
        })->when($request->month != null, function ($query) use ($request) {
            $month = date('Y-m-01', strtotime($request->month));
            $query->where('month', $month);
        })
            ->paginate(10);

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
                $month = date('Y-m-01', strtotime($request->month));
                $payroll = casual_withoutpay::updateOrCreate(['casual_employee_id' => $request->casual_employee_id, 'month' => $month], [
                    'casual_employee_id' => $request->casual_employee_id,
                    'month' => $month,
                    'credit' => $request->credit,
                    'under' => $request->under,
                    'ulwop' => $request->ulwop,
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully added without pay for employee",
                    "error" => null, // 10001 permission required
                    "data" => $payroll
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to add without pay, Please contact PSU for support",
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
                $month = date('Y-m-01', strtotime($request->month));
                $withoutpay = casual_withoutpay::updateOrCreate(["id" => $request->id], [
                    'casual_employee_id' => $request->casual_employee_id,
                    'month' => $month,
                    'credit' => $request->credit,
                    'under' => $request->under,
                    'ulwop' => $request->ulwop,
                    'is_modified' => 1,

                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully updated withoutpay",
                    "error" => null, // 10001 permission required
                    "data" => $withoutpay
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to update withoutpay, Please contact PSU for support",
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU') || $user->hasRoles('PAYROLL_OFFICER')) {
                $payroll = casual_withoutpay::findOrFail($id)->delete();
                if ($payroll) {
                    $response = [
                        "message" => "Successfully deleted without pay",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access to delete this without pay, Please contact PSU for support",
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

    
    public function sync(){
                
        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://192.168.255.5/pgomadmin/pages/menus/getDtrHistory.php?token=sdadad',
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
            'Content-Type: application/json',
            'Cookie: PHPSESSID=7lttdne7h9o6nobghsr99l3003'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response);

        foreach ($json as $key => $value) {
            $data = casual_withoutpay::where("casual_employee_id",$value->employee_id)->where("month",$value->hdate)->where("quin",$value->quin)->first();
            if ($data){
                if (!$data->is_modified){
                    $data->update([
                        "casual_employee_id" => $value->employee_id,
                        "month" => $value->hdate,
                        "credit" => $value->credit,
                        "under" => $value->under,
                        "quin" => $value->quin,
                        "travel" => $value->travelctr,
                        "is_modified" => 0,
                    ]);
                }
            }else{
                casual_withoutpay::create([
                    "casual_employee_id" => $value->employee_id,
                    "month" => $value->hdate,
                    "credit" => $value->credit,
                    "under" => $value->under,
                    "quin" => $value->quin,
                    "travel" => $value->travelctr,
                    "is_modified" => 0,
                ]);
            }
            
            
        }

         $response = [
            "message" => "Successfully Sync Casual Withoutpay",
            "error" => null, // 10001 permission required
            "data" => null
        ];

        return $response;

    }
}
