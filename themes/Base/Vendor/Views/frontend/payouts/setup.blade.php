@php
    use App\UserMeta;
    $vendor_payout_methods = json_decode(setting_item('vendor_payout_methods'));
    if (!is_array($vendor_payout_methods)) {
        return;
    }
    $vendor_payout_methods = array_values(
        \Illuminate\Support\Arr::sort($vendor_payout_methods, function ($value) {
            return $value->order ?? 0;
        }),
    );
    $payout_accounts = $currentUser->payout_accounts;
    $userMeta = UserMeta::where('user_id', Auth::user()->id)
        ->where('name', 'vendor_payout_accounts')
        ->first();
@endphp
<h4>{{ __('Your payment accounts') }}</h4>
<div class="mt-4">
    <a href="#vendor_payout_accounts" data-toggle="modal" class="btn btn-primary btn-sm">{{ __('Your accounts') }}</a>
</div>
<br>
{{-- <p><i>{{ __('To create payout request, please setup your payment account first') }}</i></p> --}}

<div class="modal bravo-form" tabindex="-1" role="dialog" id="vendor_payout_accounts">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Your payout accounts') }}</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body ">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>{{ __('Method') }}</th>
                            <th>{{ __('Your account') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($vendor_payout_methods as $k => $method)
                            @php($method_id = $method->id)
                            <tr>
                                <td>#{{ $k + 1 }}</td>
                                <td>
                                    <span class="method-name"><strong>{{ $method->name }}</strong></span>
                                    <div class="method-desc">{!! clean($method->desc) !!}</div>
                                </td>
                                <td>
                                    <textarea disabled name="payout_accounts[{{ $method->id }}]" class="form-control" cols="30" rows="3"
                                        placeholder="{{ __('Your account info') }}">{{ $payout_accounts->$method_id ?? '' }}</textarea>
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td>
                                <label>{{ __('Bank Name') }}</label>
                                <input disabled class="form-control" value="{{ $userMeta->bank_name ?? '' }}" type="text"
                                    name="bank_name" placeholder="{{__('Bank Name')}}" />
                            </td>
                            <td>
                                <label>{{ __('Bank IBAN') }}</label>
                                <input disabled class="form-control" type="text" value="{{ $userMeta->bank_IBAN ?? '' }}"
                                    name="bank_IBAN" placeholder="{{__('Bank IBAN')}}" />
                            </td>
                            <td>
                                <label>{{ __('Bank User Name') }}</label>
                                <input disabled class="form-control" type="text"
                                    value="{{ $userMeta->bank_user_name ?? '' }}" name="bank_user_name"
                                    placeholder="{{__('Bank User Name')}}" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="message_box alert d-none"></div>
            </div>
            {{-- <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Close') }}</button>
                <button type="button" class="btn btn-success "
                    onclick="vendorPayout.saveAccounts(this)">{{ __('Save changes') }}
                    <i class="fa fa-spinner"></i>
                </button>
            </div> --}}
        </div>
    </div>
</div>
