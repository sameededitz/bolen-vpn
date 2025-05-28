<x-mail::message>
<h1 style="text-align: center; color: #333; font-size: 24px;">
    {{ config('app.name') }} Purchase Code
</h1>

<span style="color: #333; text-transform: capitalize; font-weight: bold; font-size: 18px;">Hello {{ $user->name }}</span>,

Thank you for purchasing the **{{ $plan->name ?? 'plan' }}**!

<x-mail::panel>
Your activation code is: **{{ $code }}**
</x-mail::panel>

Please use this code as instructed in your account or to activate your plan.

If you have any questions or did not make this purchase, please contact our support.

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
