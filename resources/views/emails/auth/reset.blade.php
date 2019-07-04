@component('mail::message')
# Introduction

Blood bank reset password.

@component('mail::button', ['url' => 'http://ipda3.com', 'color'=>'success'])
Reset
@endcomponent
<p>Your pin code is {{$user->pin_code}}</p>
Thanks,<br>
{{ config('app.name') }}
@endcomponent
