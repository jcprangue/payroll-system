<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\department;
use App\Models\payroll_signatory;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PayrollSignatoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth()->user();

        $signatoryList = payroll_signatory::when($request->search != null, function ($query) use ($request) {
            $query->whereHas('department', function ($q) use ($request) {
                $q->where('description', 'LIKE', '%' . $request->search . '%');
            });
            $query->orWhere('department_head', 'LIKE', '%' . $request->search . '%');
        })->paginate(10);

        return $signatoryList;
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
                $payroll = payroll_signatory::create([
                    'department_id' => $request->department_id,
                    'department_head' => $request->department_head,
                    'department_head_position' => $request->department_head_position,
                    'signatory_role' => $request->signatory_role,
                    'company' => 'PGOM',
                    'status' => $request->status
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully added signatory",
                    "error" => null, // 10001 permission required
                    "data" => $payroll
                ];
            } else {
                $response = [
                    "message" => "You don't have the right access to add signatory, Please contact PSU for support",
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
                $signatory = payroll_signatory::updateOrCreate(["id" => $request->id], [
                    'department_id' => $request->department_id,
                    'department_head' => $request->department_head,
                    'department_head_position' => $request->department_head_position,
                    'signatory_role' => $request->signatory_role,
                    'company' => 'PGOM',
                    'status' => $request->status
                ]);

                DB::commit();
                $response = [
                    "message" => "Successfully updated payroll",
                    "error" => null, // 10001 permission required
                    "data" => $signatory
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
            if ($user->hasRoles('Admin') || $user->hasRoles('PSU')) {
                $payroll = payroll_signatory::findOrFail($id)->delete();
                if ($payroll) {
                    $response = [
                        "message" => "Successfully deleted signatory",
                        "error" => null, // 10001 permission required
                        "data" => null
                    ];
                }
            } else {
                $response = [
                    "message" => "You don't have the right access to delete this signatory, Please contact PSU for support",
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
        CURLOPT_URL => 'http://192.168.255.5/pgomadmin/pages/menus/getSignatory.php?token=WENSAnCVWFW7TD9r9laScpzn9Ta5nVABmTx3KMlt',
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
            'Cookie: PHPSESSID=ce7hu60sdn00k0qpurb48a2gp1'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        
        $json = json_decode($response);
        foreach ($json as $key => $value) {
            # code...
            $dept = department::where('code',$value->code)->first();
            $words = explode(" ", $value->dh_mname);
            $acronym = "";

            foreach ($words as $w) {
                $acronym .= mb_substr($w, 0, 1);
            }
            $data = [
                "department_id" => $dept->id ?? null,
                "department_head" => $value->dh_fname . ' ' . $acronym . '. ' . $value->dh_lname . ' ' . $value->dh_extension,
                "department_head_position" => $value->position,
                "signatory_role" => 1,
                "company" => 'PGOM',
                "status" => 1,
            ];
            if (isset($dept->id)){
                payroll_signatory::updateOrCreate(['department_id'=>$dept->id,'signatory_role'=>1],$data);
            }

        }

        $response = [
            "message" => "Successfully Sync Signatory",
            "error" => null, // 10001 permission required
            "data" => null
        ];

        return $response;
        
    }


    public function account(){

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://192.168.255.5/pgomadmin/pages/menus/getAccount.php?token=WENSAnCVWFW7TD9r9laScpzn9Ta5nVABmTx3KMlt',
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
            'Cookie: PHPSESSID=ce7hu60sdn00k0qpurb48a2gp1'
        ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $json = json_decode($response);

        foreach ($json as $key => $value) {
            # code...
            $dept = department::where('code',$value->department)->first();
            $data = [
                "first_name" => $value->first_name,
                "last_name" => $value->last_name,
                "nickname" => $value->nickname,
                "email" => $value->email,
                "department" => $dept->id ?? null,
                "password" => bcrypt($value->password),
                "email_verified_at" => date('Y-m-d h:i:s'),
            ];
            $user = User::updateOrCreate(['email'=>$value->email],$data);
            if($value->userAccess == 3){
                $user->assignRole('PAYROLL_OFFICER');
            }elseif($value->userAccess == 4){
                $user->assignRole('HR');
            }elseif($value->userAccess == 5){
                $user->assignRole('OPA');
            }elseif($value->userAccess == 6){
                $user->assignRole('PTO');
            }elseif($value->userAccess == 7){
                $user->assignRole('PBO');
            }elseif($value->userAccess == 9){
                $user->assignRole('PBO_HEAD');
            }elseif($value->userAccess == 10){
                $user->assignRole('OPA_HEAD');
            }elseif($value->userAccess == 99){
                $user->assignRole('PSU');
            }elseif($value->userAccess == 1){
                $user->assignRole('Admin');
            }
           
        }

        $response = [
            "message" => "Successfully Sync Signatory",
            "error" => null, // 10001 permission required
            "data" => null
        ];

        return $response;
    }
}
