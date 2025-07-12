@php
    $deleteAccount =
        route('deleteAccount.delete', ['token' => $token]) .
        "?email=$user->email";
@endphp

<x-mail::message>
# Hello, {{$user->name??''}}!

You are receiving this email because we have received a request to __delete your account__.

<x-mail::panel :color="'error'">
All your data will be deleted without the possibility of recovery.
</x-mail::panel>

Clicking the link below will delete your account.

<x-mail::caption :color="'warning'">
If you did not request account deletion, please review your account and change your password.
</x-mail::caption>

This delete link will expire in __20 minutes__.

<x-mail::button :url="$deleteAccount " :color="'red'">
Delete account
</x-mail::button>

Respectfully,<br>
__{{ config('app.name') }}__
</x-mail::message>
