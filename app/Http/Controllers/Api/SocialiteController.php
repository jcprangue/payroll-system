<?php

namespace App\Http\Controllers\Api;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;
use Hash;
use Str;

class SocialiteController extends Controller
{
    public function socialite($social) {
        return Socialite::driver($social)->with(["prompt" => "select_account","auth_type" => 'reauthenticate'])->redirect();
    }

    public function socialite_callback($social){
        
        $user = Socialite::driver($social)->stateless()->user();
        $create_user = User::firstOrCreate(
            [
                'email' => $user->getEmail()
            ],[
                'first_name' => $user->getName(),
                'nickname' => $user->getNickname(),
                'email' => $user->getEmail()
            ]
        );
        Auth::login($create_user, true);
        return redirect()->route('dashboard');
    }
}
