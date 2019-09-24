@component('mail::message')
# Change password

Click on the link or button to reset your password

@component('mail::button', ['url' => 'http://localhost:4200/changepassword?token='.$token])
Click Here to reset your password
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
