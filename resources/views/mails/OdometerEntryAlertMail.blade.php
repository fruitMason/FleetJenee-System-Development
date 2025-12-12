@component('mail::message')
Hello {{$user->full_name()}},
<article>
        It's been over {{$days_late}} day(s) since you last updated your odometer readings. Kindly login to the autoSpa portal and do so.
</article>
@component('mail::button', ['url' => route('dashboard')])
    Login
@endcomponent

Thanks,<br>
autoSpa
@endcomponent
