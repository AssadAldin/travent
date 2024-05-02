@extends('admin.layouts.app')

@section('content')
    @php
        $vendor_payout_methods = json_decode(setting_item('vendor_payout_methods'));
        if (!is_array($vendor_payout_methods)) {
            $vendor_payout_methods = [];
        }
        // $payout_accounts = $row->payout_accounts;
        $payout_accounts = $row->payout_accounts;
        $available_payout_amount = (int) $row->available_payout_amount;
        $user_available_methods = $row->available_payout_methods;
        $res = $row->userMoney($row);
    @endphp
    <div class="container-fluid">
        @if (Session::has('message'))
            <script type="text/javascript">
                window.setTimeout("document.getElementById('successMessage').style.display='none';", 2000);
            </script>
            <p class="alert {{ Session::get('alert-class', 'alert-danger') }}">{{ Session::get('message') }}</p>
        @endif
        <p class="h4 my-2">{{ __('Host payouts create request') }} : {{ $row->name }}</p>
        <form class="mt-3" action="{{ route('storePayout', $row->id) }}" method="post">
            @csrf
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ __('Available for payout') }}</label>
                <div class="col-sm-9">
                    <input type="text" class="form-control" readonly
                        value="{{ format_money(floor($res['total_price'] - $res['vendorPayout'])) }}">
                </div>
                <input type="text" name="user_id" class="form-control d-none" readonly value="{{ $row->id }}">
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ __('Amount') }}
                    <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <input type="number" required max="{{ $available_payout_amount }}" class="form-control" name="amount"
                        value="{{ floor($res['total_price'] - $res['vendorPayout']) }}">
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ __('Method') }}
                    <span class="text-danger">*</span></label>
                <div class="col-sm-9">
                    <select required class="form-control" name="payout_method">
                        <option value="">{{ __('-- Please select --') }}
                        </option>
                        @foreach ($row->available_payout_methods as $id => $method)
                            <option value="{{ $id }}">
                                {{ $method->name }} @if (!empty($method->min))
                                    ({{ __('Minimum: :amount', ['amount' => format_money($method->min)]) }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="form-group row">
                <label class="col-sm-3 col-form-label">{{ __('Note to admin') }}</label>
                <div class="col-sm-9">
                    <textarea name="note_to_admin" class="form-control" cols="30" rows="10">{{ __('Created by admin') }} {{ Auth::user()->name }}</textarea>
                </div>
            </div>
            <button class="btn btn-primary" type="submit">submit</button>
        </form>
    </div>
@endsection
