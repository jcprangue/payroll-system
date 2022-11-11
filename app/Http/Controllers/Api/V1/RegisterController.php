<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Facades\App\Actions\Fortify\CreateNewUser;
use App\Http\Resources\User as UserResource;
use App\Models\HealthDeclarationQuestion as HDQ;
use DB;
class RegisterController extends Controller
{


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function showAll()
    {
        
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
            $user = CreateNewUser::create($request->all());
        
            if ($user){
                $token = $user->createToken('Trace-App');
                $user->token = $token->plainTextToken;
        
                if ($request->role){
                    $user->roles()->sync($request->role);
                }


                $hdq = HDQ::all();
                foreach ($hdq as $question) {
                    $user->hdq()->attach($question->id);
                }

            }
            DB::commit();
            return new UserResource($user);
        } catch (\Throwable $th) {
            DB::rollback();
            return $th->getMessage();
            //throw $th;
        }

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
