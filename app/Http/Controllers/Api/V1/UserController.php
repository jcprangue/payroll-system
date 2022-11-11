<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\User as UserResource;
use Auth;
use Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    //
    public function login(Request $request){
        $request->validate([
            'email' => 'required|exists:users,email',
            'password' => 'required',
            'token_name' => 'required',
        ]);
        
        $user = User::where('email', $request->email)->first();
        
        if(!$user)
        {
            throw ValidationException::withMessages(['email' => "Credentials not found."]);
        }

        if (Hash::check($request->input('password'), $user->password)) {
            $token = $user->createToken($request->token_name);
            $user->token = $token->plainTextToken;
            return new UserResource($user);
        }

        
        return response('Authentication Failed', 401);
    }


}
