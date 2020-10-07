<?php

namespace App;

use App\Traits\HasRolesAndPermissions;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Twilio\Rest\Client;
class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRolesAndPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'email_verification_code','otp','provider', 'provider_id', 
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
    ];

    public function hasVerifiedPhone()
    {
        return ! is_null($this->phone_verified_at);
    }

    public function markPhoneAsVerified()
    {
        return $this->forceFill([
            'phone_verified_at' => $this->freshTimestamp(),
        ])->save();
    }
    public function callToVerify()
        {
            $code = random_int(100000, 999999);
            
            $this->forceFill([
                'verification_code' => $code
            ])->save();

            $client = new Client(env('TWILIO_SID'), 
                                 env('TWILIO_AUTH_TOKEN'));

            $client->calls->create(
                $this->phone,
                "+91 78955 26889", // REPLACE WITH YOUR TWILIO NUMBER
                ["url" => "http://localhost/weexpan/public/build-twiml/{$code}"]
            );
        }
}
