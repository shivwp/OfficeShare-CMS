@component('mail::message')

<h4>Hi,{{Str::ucfirst($user['name'])}} you requested to reset password. Click on bellow button to reset your password</h4>

<h4><a href="{{url('/reset-password'.'/'.$user['token'])}}" style="background-color:black;color:white;padding:12px;border:inset 1px black;text-decoration:none;">Click Here to reset password</a></h4>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
