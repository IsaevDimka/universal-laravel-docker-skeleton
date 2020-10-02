@component('mail::message')
    # Welcome

    Register successful

    @component('mail::button', ['url' => url('/login')])
        Login
    @endcomponent

    Thanks,<br>
    {{ config('app.name') }}
@endcomponent