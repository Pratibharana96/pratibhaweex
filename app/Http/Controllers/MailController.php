<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
use App\Mail\SignupEmail;

class MailController extends Controller
{
 public static function sendSignUpEmail($name,$email,$password,$email_verification_code)
 {
     $data= [
         'name' =>$name,
         'password' =>$password,
         'email' =>$email,
         'email_verification_code'=>$email_verification_code
        ];
     Mail::to($email)->send(new signupEmail($data));
 }
}
