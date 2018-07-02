@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
{{ $greeting }}
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{!! $line !!}
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
{!! $line !!}
@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
<br>{!! $salutation !!}
@endif
@endcomponent
