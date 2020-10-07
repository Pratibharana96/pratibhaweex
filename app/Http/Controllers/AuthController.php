<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Validator;
use Twilio\Rest\Client;

class AuthController extends Controller
{
    public $successStatus = 200;
    // public function __construct()
    // {
    //     $this->middleware(['auth', 'verified']);
    // }

    public function login(Request $request){
        Log::info($request);
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            return view('home');
        }
        else{
            return Redirect::back ();
        }
    }

    public function loginWithOtp(Request $request){
        $data= $request->all();
        /* Get twiliocredentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $twilio->verify->v2->services($twilio_verify_sid)
            ->verifications
            ->create($data['mobile'], "sms");
        //Log::info($request);
        if($data != null){
            //$user->callToVerify();
            //dd($user['mobile']);exit;
            return redirect()->route('phoneverification.notice')->with(['mobile' => $data['mobile']]);
           // MailController::sendSignupEmail($user->name, $user->email, $user->email_verification_code);
          //  return redirect()->back()->with(session()->flash('alert-success', 'Your account has been created. Please check email for verification link.'));
        }
        else{
            return Redirect::back ();
        }
    }

    public function register(Request $request)
    {
   $data= $request->all();
   //dd($value);exit;
    //dd($data['password']);exit;
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required',
            'mobile' => 'required',
            'password' => 'required',
            'password_confirmation' => 'required|same:password',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
         /* Get twiliocredentials from .env */
         $token = getenv("TWILIO_AUTH_TOKEN");
         $twilio_sid = getenv("TWILIO_SID");
         $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
         $twilio = new Client($twilio_sid, $token);
         $twilio->verify->v2->services($twilio_verify_sid)
             ->verifications
             ->create($data['mobile'], "sms");
        //dd($user->$request->password);exit;
      
        // $input = $request->all();
        $user= new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->mobile = $request->mobile;
        $user->password = Hash::make($request->password);
        $user->email_verification_code = sha1(time());
        $user->save();
        // $input['email_verification_code'] = sha1(time());
        // $input['password'] = bcrypt($input['password']);
        MailController::sendSignupEmail($user->name, $user->email, $data['password'] , $user->email_verification_code);
           
        if($user != null){
            //$user->callToVerify();
            //dd($user['mobile']);exit;
             return redirect()->route('phoneverification.notice')->with(['mobile' => $user['mobile']]);
           // MailController::sendSignupEmail($user->name, $user->email, $user->password , $user->email_verification_code);
          //  return redirect()->back()->with(session()->flash('alert-success', 'Your account has been created. Please check email for verification link.'));
        }
        echo "text1";exit;
        return redirect()->back()->with(session()->flash('alert-danger', 'Something went wrong!'));
        //return redirect('login');
    }
    //Registration with email 
    public function verifyUser(Request $request){
        $verification_code = \Illuminate\Support\Facades\Request::get('code');
        $user = User::where(['email_verification_code' => $verification_code])->first();
        if($user != null){
            $user->is_verified = 1;
            $user->save();
            return redirect()->route('login')->with(session()->flash('alert-success', 'Your account is verified. Please login!'));
        }

            return redirect()->route('login')->with(session()->flash('alert-danger', 'Invalid verification code!'));
        }
    public function sendOtp(Request $request){

        $otp = rand(1000,9999);
        Log::info("otp = ".$otp);
        $user = User::where('mobile','=',$request->mobile)->update(['otp' => $otp]);
        // send otp to mobile no using sms api
        return response()->json([$user],200);
    }
    //otp  with twilio
    public function twiliootpshow(Request $request)
    {
        return view('verifyphone');
    }
//twilio verify
    protected function twilioverify(Request $request)
    {
  
        $data= $request->all();
        //   dd($data);exit;
        // $request->validate([
        //     'verification_code' => ['required', 'numeric'],
        //     'mobile' => ['required', 'string'],
        // ]);
        
        /* Get credentials from .env */
        $token = getenv("TWILIO_AUTH_TOKEN");
       // dd($token);exit;
        $twilio_sid = getenv("TWILIO_SID");
        $twilio_verify_sid = getenv("TWILIO_VERIFY_SID");
        $twilio = new Client($twilio_sid, $token);
        $verification = $twilio->verify->v2->services($twilio_verify_sid)
            ->verificationChecks
            ->create($data['verification_code'], array('to' => $data['mobile']));
           // dd($verification);exit;
            if ($verification->valid) {
          
            $user = tap(User::where('mobile', $data['mobile']))->update(['is_verified' => true]);
            //dd($user);exit;
            /* Authenticate user */
            Auth::login($user->first());
            return redirect()->route('home')->with(['message' => 'Phone number verified']);
        }
        return back()->with(['mobile' => $data['mobile'], 'error' => 'Invalid verification code entered!']);
    }
    //twilio redirect 
    public function buildTwiMl($code)
    {
        $code = $this->formatCode($code);
        $response = new VoiceResponse();
        $response->say("Hi, thanks for Joining. This is your verification code. {$code}. I repeat, {$code}.");
        echo $response;
    }

    public function formatCode($code)
    {
        $collection = collect(str_split($code));
        return $collection->reduce(
            function ($carry, $item) {
                return "{$carry}. {$item}";
            }
        );
    }
}
