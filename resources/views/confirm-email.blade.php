@component('mail::message')
# Introduction

Hi. Please confirm your email for unlocking your account. Ok? Be nice boy/girl!

@component('mail::button', ['url' => route('confirmation.index', ['token' => $user->confirmation_token])])
Confirm email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
