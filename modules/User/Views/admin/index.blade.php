@extends('admin.layouts.app')

@section('content')
    @php
        use App\UserMeta;
        $vendor_payout_methods = json_decode(setting_item('vendor_payout_methods'));
        if (!is_array($vendor_payout_methods)) {
            $vendor_payout_methods = [];
        }
        // $payout_accounts = $row->payout_accounts;
    @endphp
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('All Users') }}</h1>
            <div class="title-actions">
                <a href="{{ route('user.admin.create') }}" class="btn btn-primary">{{ __('Add new user') }}</a>
                <a class="btn btn-warning btn-icon" href="{{ route('user.admin.export') }}" target="_blank"
                    title="{{ __('Export to excel') }}">
                    <i class="icon ion-md-cloud-download"></i> {{ __('Export to excel') }}
                </a>
            </div>
        </div>
        @include('admin.message')
        <div class="filter-div d-flex justify-content-between ">
            <div class="col-left">
                @if (!empty($rows))
                    <form method="post" action="{{ route('user.admin.bulkEdit') }}"
                        class="filter-form filter-form-left d-flex justify-content-start">
                        {{ csrf_field() }}
                        <select name="action" class="form-control">
                            <option value="">{{ __(' Bulk Actions ') }}</option>
                            <option value="delete">{{ __(' Delete ') }}</option>
                        </select>
                        <button data-confirm="{{ __('Do you want to delete?') }}"
                            class="btn-info btn btn-icon dungdt-apply-form-btn" type="button">{{ __('Apply') }}</button>
                    </form>
                @endif
            </div>
            <div class="col-left">
                <form method="get"
                    class="filter-form filter-form-right d-flex justify-content-end flex-column flex-sm-row" role="search">
                    <select class="form-control" name="role">
                        <option value="">{{ __('-- Select --') }}</option>
                        @foreach ($roles as $role)
                            <option value="{{ $role->name }}" @if (Request()->role == $role->name) selected @endif>
                                {{ ucfirst($role->name) }}</option>
                        @endforeach
                    </select>
                    <input type="text" name="s" value="{{ Request()->s }}"
                        placeholder="{{ __('Search by name') }}" class="form-control">
                    <button class="btn-info btn btn-icon btn_search" type="submit">{{ __('Search User') }}</button>
                </form>
            </div>
        </div>
        <div class="text-right">
            <p><i>{{ __('Found :total items', ['total' => $rows->total()]) }}</i></p>
        </div>
        <div class="panel">
            <div class="panel-body">
                <form action="" class="bravo-form-item">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th width="60px"><input type="checkbox" class="check-all"></th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Credit') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Role') }}</th>
                                    <th class="date">{{ __('Date') }}</th>
                                    {{--                            <th class="status">{{__('Status')}}</th> --}}
                                    <th>{{ __('Pending') }}</th>
                                    <th>{{ __('Earnings') }}</th>
                                    {{-- <th>{{ __('Payouts') }}</th> --}}
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rows as $row)
                                    @php
                                        $payout_accounts = $row->payout_accounts;
                                        $res = $row->userMoney($row);
                                        $user_available_methods = $row->available_payout_methods;
                                        $available_payout_amount = (int) $row->available_payout_amount;
                                        $userMeta = UserMeta::where('user_id', $row->id)
                                            ->where('name', 'vendor_payout_accounts')
                                            ->first();
                                    @endphp
                                    {{-- @dd($row) --}}
                                    <tr>
                                        <td><input type="checkbox" name="ids[]" value="{{ $row->id }}"
                                                class="check-item"></td>
                                        <td class="title">
                                            <a
                                                href="{{ route('user.admin.detail', ['id' => $row->id]) }}">{{ $row->getDisplayName() }}</a>
                                        </td>
                                        <td>{{ $row->email }}
                                            @if ($row->email_verified_at)
                                                <i class="fa fa-check-circle text-success"
                                                    title="{{ __('Verified') }}"></i>
                                            @else
                                                <i class="fa fa-info-circle text-warning"
                                                    title="{{ __('Not Verified') }}"></i>
                                            @endif
                                        </td>
                                        <td>{{ $row->balance }}</td>
                                        <td>{{ $row->phone }}</td>
                                        <td>
                                            {{ $row->role->name ?? '' }}
                                        </td>
                                        <td>{{ display_date($row->created_at) }}</td>
                                        <td class="status">{{ round($res['total_price'] - $res['total_earning']) }}</td>
                                        <td class="status">{{ round($res['total_earning']) }}</td>
                                        {{-- <td>
                                            <div class="">
                                                <a href="{{ route('createPayout', $row->id) }}"
                                                    class="btn btn-success btn-sm"><i class="fa fa-plus"></i>
                                                    {{ __('Create request') }}</a>
                                            </div>
                                        </td> --}}
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button"
                                                    data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-th"></i>
                                                </button>
                                                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                                    <a class="dropdown-item"
                                                        href="{{ route('user.admin.detail', ['id' => $row->id]) }}"><i
                                                            class="fa fa-edit"></i> {{ __('Edit') }}</a>
                                                    @if (!$row->hasVerifiedEmail())
                                                        <a class="dropdown-item"
                                                            href="{{ route('user.admin.verifyEmail', $row) }}"><i
                                                                class="fa fa-edit"></i> {{ __('Verify email') }}</a>
                                                    @else
                                                        <a class="dropdown-item" href="#"><i class="fa fa-check"></i>
                                                            {{ __('Email verified') }}</a>
                                                    @endif
                                                    <a class="dropdown-item"
                                                        href="{{ route('user.admin.password', ['id' => $row->id]) }}"><i
                                                            class="fa fa-lock"></i> {{ __('Change Password') }}</a>
                                                    <a href="{{ route('user.admin.wallet.addCredit', ['id' => $row->id]) }}"
                                                        class="dropdown-item"><i class="fa fa-plus"></i>
                                                        {{ __('Add Credit') }}</a>
                                                    <a href="{{ route('createPayout', $row->id) }}"
                                                        class="dropdown-item"><i class="fa fa-plus"></i>
                                                        {{ __('Payout request') }}</a>
                                                    <a href="#vendor_payout_accounts{{ $row->id }}"
                                                        data-toggle="modal" class="dropdown-item"><i class="fa fa-plus"></i>
                                                        {{ __('User Bank Account') }}</a>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    <div class="modal bravo-form" tabindex="-1" role="dialog"
                                        id="vendor_payout_accounts{{ $row->id }}">
                                        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">{{ __('Setup payout accounts') }}</h5>
                                                    <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                        <span aria-hidden="true">&times;</span>
                                                    </button>
                                                </div>
                                                <form
                                                    action="{{ route('vendor.payout.storeAdminPayoutAccounts', $row->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('POST')
                                                    <div class="modal-body">
                                                        <div class="table table-bordered">
                                                            <div class="d-flex my-3">
                                                                @foreach ($vendor_payout_methods as $k => $method)
                                                                    @php($method_id = $method->id)
                                                                    <div class="mx-4">#{{ $k + 1 }}</div>
                                                                    <di class="mx-4">
                                                                        <span
                                                                            class="method-name"><strong>{{ $method->name }}</strong></span>
                                                                        <div class="method-desc">{!! clean($method->desc) !!}
                                                                        </div>
                                                                    </di>
                                                                    <div class="mx-4">
                                                                        <label>{{ __('Bank number') }}</label>
                                                                        <textarea name="payout_accounts[{{ $method->id }}]" class="form-control" cols="30" rows="3"
                                                                            placeholder="{{ __('Your account info') }}">{{ $payout_accounts->$method_id ?? '' }}</textarea>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                            <hr />
                                                            <div class="d-flex my-3">
                                                                <div class="mx-4">
                                                                    <label>{{ __('Bank Name') }}</label>
                                                                    <input class="form-control"
                                                                        value="{{ $userMeta->bank_name ?? '' }}"
                                                                        type="text" name="bank_name"
                                                                        placeholder="{{__('Bank Name')}}" />
                                                                </div>
                                                                <div class="mx-4">
                                                                    <label>{{ __('Bank IBAN') }}</label>
                                                                    <input class="form-control" type="text"
                                                                        value="{{ $userMeta->bank_IBAN ?? '' }}"
                                                                        name="bank_IBAN" placeholder="{{__('Bank IBAN')}}" />
                                                                </div>
                                                                <div class="mx-4">
                                                                    <label>{{ __('Bank User Name') }}</label>
                                                                    <input class="form-control" type="text"
                                                                        value="{{ $userMeta->bank_user_name ?? '' }}"
                                                                        name="bank_user_name"
                                                                        placeholder="{{__('Bank User Name')}}" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="message_box alert d-none"></div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-dismiss="modal">{{ __('Close') }}</button>
                                                        <button type="submit"
                                                            class="btn btn-success ">{{ __('Save changes') }}
                                                            <i class="fa fa-spinner"></i>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </form>
                {{ $rows->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
