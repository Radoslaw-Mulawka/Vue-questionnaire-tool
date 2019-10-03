@component('mail::message')
<div class="header-logo">
    <!--[if mso ]> <img src="cid:tell-it-us_logo_mail" alt="{{ config('app.name') }}" /> <![endif]-->    <!-- Outlook -->

    <!--[if !mso]><!-- -->
    <img src="https://tell-it.us/img/tell-it-us_logo_mail.png" alt="{{ config('app.name') }}" />
    <!--<![endif]-->
</div>

{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level == 'error')
# Whoops!
@else
# Hello!
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
switch ($level) {
  case 'success':
    $color = 'green';
    break;
  case 'error':
    $color = 'red';
    break;
  default:
    $color = 'blue';
}
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
{{ $actionText }}
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
<div class="salutation" >{!! $salutation !!}</div>
@else
Regards,<br>{{ config('app.name') }}
@endif


{{-- Subcopy --}}
@isset($actionText)
@component('mail::subcopy')
Jeśli masz problem z kliknięciem przycisku "{{ $actionText }}" , skopiuj i wklej poniższy 
adres URL do przeglądarki internetowej: [{{ $actionUrl }}]({{ $actionUrl }})
@endcomponent
@endisset
@endcomponent
