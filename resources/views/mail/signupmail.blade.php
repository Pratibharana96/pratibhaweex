Hello {{$email_data['name']}}
<br><br>
Welcome to my Website!
Your Username is : {{$email_data['email']}}
Your Password is : {{$email_data['password']}}
<br>
Please click the below link to verify your email and activate your account!
<br><br>
<a href="http://localhost/weexpan/public/verify?code={{$email_data['email_verification_code']}}">Click Here!</a>

<br><br>
Thank you!
<br>
Pratibha rana.com