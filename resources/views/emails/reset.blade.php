<x-mail::message>
<h1 style="text-align: center; color: #333; font-size: 24px;">
    {{ config('app.name') }} Password Reset
</h1>

<span style="color: #333; text-transform: capitalize; font-weight: bold; font-size: 18px;">Hello {{ $user->name }}</span>,

We received a request to reset your password. Click the button below to reset it:

<x-mail::button :url="$url" color="primary">
Reset Password
</x-mail::button>

If you did not request a password reset, no further action is required.

{{-- Fallback URL in a panel --}}
<x-mail::panel>
If the button above doesn't work, copy and paste the following URL into your browser:

<span style="word-break: break-all; color: #555; font-size: 12px;">
   {{ $url }}
</span>
</x-mail::panel>

Best Regards,<br>
{{ config('app.name') }} Team
</x-mail::message>
