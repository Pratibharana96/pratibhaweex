<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Socialite;
use Auth;
use App\User;
class SocialController extends Controller
{

    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }
    public function Callback($provider){
        if($provider='facebook'){
        $userSocial =   Socialite::driver($provider)->user(); 
        }
        else{
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        }
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if($users){
             Auth::login($users);
             return redirect('/');    
            }else
            {
                $user = User::create([
                'name'          => $userSocial->getName(),
                'email'         => $userSocial->getEmail(),
                'image'         => $userSocial->getAvatar(),
                'provider_id'   => $userSocial->getId(),
                'provider'      => $provider,
            ]);
            //Auth::login($users);
         return redirect()->route('home');
        //}
}
}
