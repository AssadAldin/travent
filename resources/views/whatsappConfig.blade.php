@extends('admin.layouts.app')
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-1">

            </div>
            <form action="{{ route('whatsapp.update') }}" method="POST">
                <div class="col-md-10">
                    <h3>{{ __('Whatsapp Messages') }}</h3>
                    <hr>
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-sm-4">
                            <h3 class="form-group-title">{{ __('Whatsapp Server config') }}</h3>
                        </div>
                        <div class="col-sm-8">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <label>{{ __('app key') }}</label>
                                        <div class="form-controls">
                                            <input type="text" id="app_key" name="app_key"
                                                value="{{ $whatsapp->app_key }}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('auth key') }}</label>
                                        <div class="form-controls">
                                            <input type="text" id="auth_key" name="auth_key"
                                                value="{{ $whatsapp->auth_key }}" class="form-control" />
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label>{{ __('Disable') }}</label>
                                        <div class="form-controls">
                                            <select name="open" id="open" name="open" class="form-control">
                                                <option @if ($whatsapp->open == 'yes') selected @endif value="yes">Yes
                                                </option>
                                                <option @if ($whatsapp->open == 'no') selected @endif value="no">No
                                                </option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-sm-4">
                            <h3 class="form-group-title">{{ __('Messages') }}</h3>
                            <div class="form-group-desc">
                                {{ __('Phone number must be E.164 format') }}
                                <p>{{ __('Format') }}:<code> {{ __('[+][country code][subscriber number including area code]') }} </code>
                                </p>
                                <p>{{ __('Example') }}:<code> +12019480710</code></p>
                                <div>{{ __('Message') }}:</div>
                                @foreach (\Modules\Sms\Listeners\SendSmsBookingListen::CODE as $item => $value)
                                    <div><code>{{ $value }}</code></div>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-sm-8">
                            <div class="panel">
                                <div class="panel-body">
                                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="guest_tab" data-toggle="tab" href="#guest"
                                                role="tab" aria-controls="guest" aria-selected="true">Guest</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="host_tab" data-toggle="tab" href="#host"
                                                role="tab" aria-controls="host" aria-selected="false">Host</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="admin_tab" data-toggle="tab" href="#admin"
                                                role="tab" aria-controls="admin" aria-selected="false">admin</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="guest" role="tabpanel"
                                            aria-labelledby="guest_tab">
                                            <div class="row my-3 mx-2">
                                                <b>Guest</b>
                                            </div>
                                            <div class="row justify-content-center">
                                                <div class="mx-3">
                                                    <p>Engilsh</p>
                                                    <textarea name="g_en" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->g_en }}</textarea>
                                                </div>
                                                <div class="mx-3">
                                                    <p>عربي</p>
                                                    <textarea name="g_ar" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->g_ar }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="host" role="tabpanel"
                                            aria-labelledby="host_tab">
                                            <div class="row my-3 mx-2">
                                                <b>Host</b>
                                            </div>
                                            <div class="row justify-content-center mt-3">
                                                <div class="mx-3">
                                                    <p>Engilsh</p>
                                                    <textarea name="h_en" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->h_en }}</textarea>
                                                </div>
                                                <div class="mx-3">
                                                    <p>عربي</p>
                                                    <textarea name="h_ar" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->h_ar }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="tab-pane fade" id="admin" role="tabpanel"
                                            aria-labelledby="admin_tab">
                                            <div class="row my-3 mx-2">
                                                <b>Admin</b>
                                            </div>
                                            <div class="row justify-content-center mt-3">
                                                <div class="mx-3">
                                                    <p>Engilsh</p>
                                                    <textarea name="a_en" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->a_en }}</textarea>
                                                </div>
                                                <div class="mx-3">
                                                    <p>عربي</p>
                                                    <textarea name="a_ar" class="form-control" id="" cols="45" rows="5">{{ $whatsapp->a_ar }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>
                    {{-- <div class="row">
                        <div class="col-sm-4">
                            <h3 class="form-group-title">{{ __('Whatsapp Message Testing') }}</h3>
                        </div>
                        <div class="col-sm-8">
                            <div class="panel">
                                <div class="panel-body">
                                    <div class="form-group">
                                        <div class="form-controls">
                                            <label class="">{{ __('Country') }}</label>
                                            <select name="country" class="form-control" id="country-sms-testing">
                                                <option value="">{{ __('-- Select --') }}</option>
                                                @foreach (get_country_lists() as $id => $name)
                                                    <option value="{{ $id }}">{{ $name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="form-controls">
                                            <label class="">{{ __('To (phone number)') }}</label>
                                            <input type="number" class="form-control" id="to-sms-testing"
                                                name="to"></input>
                                        </div>

                                        <div class="form-controls">
                                            <label class="">{{ __('Message') }}</label>
                                            <textarea class="form-control" id="message-sms-testing" name="message"></textarea>
                                        </div>
                                        <div class="form-controls">
                                            <br>
                                            <div id="sms-testing" style="cursor: pointer;" class="btn btn-primary">
                                                {{ __('Send Test') }}</div>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <div class="form-controls">
                                            <div id="sms-testing-alert" class="" role="alert">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                </div>
                <hr>
                <div class="form-group mt-3 row" dir="rtl">
                    <input type="submit" name="save" value="Save settings" class="btn btn-primary">
                </div>
            </form>
            <div class="col-md-1">

            </div>

        </div>
    </div>
@endsection
{{-- @push('js')
    <script>
        $(document).ready(function() {
            var cant_test = 1;
            $(document).on('click', '#sms-testing', function(e) {
                event.preventDefault();
                var to = $('#to-sms-testing').val();
                var message = $('#message-sms-testing').val();
                var country = $('#country-sms-testing').val();
                var app_key = $('#app_key').val();
                var auth_key = $('#auth_key').val();
                var open = $('#open').val();
                $.ajax({
                    url: '{{ route('sms.admin.testSms') }}',
                    type: 'get',
                    data: {
                        to: to,
                        country: country,
                        message: message
                    },
                    beforeSend: function() {
                        $('#sms-testing').append(' <i class="fa  fa-spinner fa-spin"></i>')
                            .addClass('disabled');
                    },
                    success: function(res) {
                        if (res.error !== false) {
                            $('#sms-testing-alert').removeClass().addClass(
                                'alert alert-warning').html(res.messages);
                        } else {
                            $('#sms-testing-alert').removeClass().addClass(
                                'alert alert-success').html(
                                '<strong>Sms Test Success!</strong>');
                        }
                        cant_test = 1;
                    },
                    complete: function() {
                        $('#sms-testing').removeClass('disabled').find('i').remove();
                        cant_test = 1;
                    },
                    error: function(request, status, error) {
                        err = JSON.parse(request.responseText);
                        html = '<p><strong>' + request.statusText + '</strong></p><p>' + err
                            .message + '</p>';
                        $('#sms-testing-alert').removeClass().addClass('alert alert-warning')
                            .html(html);
                        cant_test = 1;
                    }
                })

                setTimeout(function() {
                    $('#sms-testing-alert').html('').removeClass();
                }, 20000);
            })
        })
    </script>
@endpush --}}
