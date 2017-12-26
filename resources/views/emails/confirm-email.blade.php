@component('mail::message')
# Welcome, {{ $user->name }}

You should confirm your email, before you can start posting to our forum.

@component('mail::button', ['url' => url('/register/confirm?token=' . $user->confirmation_token)])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
