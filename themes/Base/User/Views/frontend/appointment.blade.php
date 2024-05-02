@extends('layouts.user')
@section('content')
    <h1>{{ __('Appointment') }}</h1>
    <div>
        <!-- Calendly inline widget begin -->
        <div class="calendly-inline-widget" data-url="https://calendly.com/travent-ae/host-support"
            style="min-width:320px;height:700px;"></div>
        <script type="text/javascript" src="https://assets.calendly.com/assets/external/widget.js" async></script>
    </div>
@endsection
