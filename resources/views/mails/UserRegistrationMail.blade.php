@component('mail::message')
Hello {{$user->full_name()}},
<article>
        You have successfully been registered on the autoSpa portal and a copy of your credentials has been shared with you below.

        Email: {{$user->email}}
        Password: {{$password}}
</article>
@component('mail::button', ['url' => route('dashboard')])
    Login
@endcomponent

Thanks,<br>
autoSpa
@endcomponent
