@component('mail::message')
# {{ $data['title'] }}
{{ $data['body'] }}
{{-- @component('mail::button', ['url' => $maildata['url']])
Verify
@endcomponent --}}
Thanks,<br>
{{ config('app.name') }}
@endcomponent
