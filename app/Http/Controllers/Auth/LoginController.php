<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Socialite;
use App\User;
use Illuminate\Support\Facades\Auth;
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    //social login 
    public function redirectToProvider($service)
    {
        return Socialite::driver($service)->redirect();
    }

    /**
     * Obtain the user information from $service.
     *
     * @return \Illuminate\Http\Response
     */
    public function handleProviderCallback($service)
    {
        
        $userSocial = Socialite::driver($service)->stateless()->user();
        $users= User::where(['email' => $userSocial->getEmail()])->first();
        //check if the user is alredy registered
        if($users){
            Auth::login($users);
            return redirect('/');
        }else{
        $user = User::create([
            'name'          => $userSocial->getName(),
            'email'         => $userSocial->getEmail(),
            'image'         => $userSocial->getAvatar(),
            'password'      => bcrypt(123456),
            'provider_id'   => $userSocial->getId(),
            'provider'      => $service,
        ]);    
       // Auth::login($user);
        return redirect('home');
       // return  $user->email;
       }
    }
}
