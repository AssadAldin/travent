@extends ('admin.layouts.app')
@php
        use App\UserMeta;
@endphp
<!-- DataTables -->
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
<link rel="stylesheet" href="{{ asset('libs/data-table/datatables-buttons/css/buttons.bootstrap4.min.css') }}">
<title>Travent Report / </title>
@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between mb20">
            <h1 class="title-bar">{{ __('All Bookings Report') }}</h1>
        </div>
        <div class="col-md-12 mb-2 d-flex">
            <form action="{{ route('report.admin.booking2') }}" method="GET" role="search">
                <div class="row">
                    <div class="d-flex m-2 align-items-center">
                        <label for="from" class="mx-2">{{ __('From') }}</label>
                        <input type="date" value="{{ old('from') }}" class="form-control" name="from"
                            id="from">
                    </div>
                    <div class="d-flex m-2 align-items-center">
                        <label for="to" class="mx-2">{{ __('To') }}</label>
                        <input type="date" value="{{ old('to') }}" class="form-control" name="to"
                            id="to">
                        <button class="btn btn-info mx-2 p-2" type="submit" title="Search">
                            <span class="fa fa-search"></span>
                        </button>
                        <a href="{{ route('report.admin.booking2') }}" class="btn btn-success text-center p-2"
                            title="Refresh"><span class="fa fa-refresh"></span></a>
                    </div>
                </div>
            </form>
        </div>
        <table id="example1" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>{{ __('ID') }}</th>
                    <th>{{ __('Service') }}</th>
                    <th>{{ __('Host name') }}</th>
                    <th>{{ __('Host Email') }}</th>
                    <th>{{ __('Guest name') }}</th>
                    <th>{{ __('Check out date') }}</th>
                    <th>{{ __('Total amount') }}</th>
                    <th>{{ __('Host commission') }}</th>
                    <th>{{ __('Guest commission') }}</th>
                    <th>{{ __('Receipt from Guest') }}</th>
                    <th>{{ __('Payment to host') }}</th>
                    <th>{{ __('Margin') }}</th>
                    <th>{{ __('booking payable date') }}</th>
                    <th>{{ __('Status') }}</th>
                    <th>{{ __('Payment method') }}</th>
                    <th>{{ __('Created at') }}</th>
                    <th>{{ __('IBAN') }}</th>
                    <th>{{ __('Bank Username') }}</th>
                    <th>{{ __('Bank Name') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($data as $row)
                    @php
                        $booking = $row;
                        $userMeta = UserMeta::where('user_id', $row->vendor->id)
                            ->where('name', 'vendor_payout_accounts')
                            ->first();
                    @endphp
                    <tr>

                        <td>
                            {{ $row->id }}
                        </td>
                        <td>
                            {{ $row->vendor->business_name }}
                        </td>
                        <td>
                            {{ $row->vendor->first_name }} {{ $row->vendor->last_name }}
                        </td>
                        <td>
                            {{ $row->vendor->email }}<br>
                        </td>
                        <td>
                            {{ $row->first_name }} {{ $row->last_name }}
                        </td>
                        <td>
                            {{ $row->end_date }}
                        </td>
                        <td>
                            {{ $booking->total_before_fees }}
                        </td>
                        <td>
                            {{ $booking->commission }}
                        </td>
                        @php
                            $list_all_fee = [];
                            if (!empty($booking->buyer_fees)) {
                                $buyer_fees = json_decode($booking->buyer_fees, true);
                                $list_all_fee = $buyer_fees;
                            }
                            if (!empty(($vendor_service_fee = $booking->vendor_service_fee))) {
                                $list_all_fee = array_merge($list_all_fee, $vendor_service_fee);
                            }
                        @endphp
                        @php
                            $fees_service_coupon = false;
                            if (count($booking->coupons)) {
                                foreach ($booking->coupons as $coupon) {
                                    if ($coupon->without_service_fees) {
                                        $fees_service_coupon = true;
                                    }
                                }
                            }
                            $host_comm = 0;
                        @endphp
                        @if (!empty($list_all_fee))
                            @foreach ($list_all_fee as $item)
                                @php
                                    $fee_price = $item['price'];
                                    if (!empty($item['unit']) and $item['unit'] == 'percent') {
                                        $fee_price = ($booking->total_before_fees / 100) * $item['price'];
                                    }
                                    $host_comm += $fee_price;
                                @endphp
                                @php
                                    $fees_service_coupon = false;
                                    if (count($booking->coupons)) {
                                        foreach ($booking->coupons as $coupon) {
                                            if ($coupon->without_service_fees) {
                                                $fees_service_coupon = true;
                                                $host_comm = 0;
                                            }
                                        }
                                    }
                                @endphp
                            @endforeach
                        @endif
                        <td>
                            {{ $host_comm }} </td>
                        <td>
                            {{ $booking->total_before_fees + $host_comm }}
                        </td>
                        <td>
                            {{ $booking->total_before_fees - $booking->commission }}
                        </td>
                        <td>
                            {{ $host_comm + $booking->commission }}
                        </td>
                        <td>
                            @php
                                $day = date('N', strtotime($row->end_date));
                                $d = 0;
                                for ($i = 0; $i <= 6; $i++) {
                                    $date = date(
                                        'Y-m-d H:i:s',
                                        strtotime('+' . $d . ' day', strtotime($row->end_date)),
                                    );
                                    $dayId = date('N', strtotime($date));
                                    if ($dayId == 3) {
                                        if ($d != 0) {
                                            break;
                                        }
                                    }
                                    $d += 1;
                                }
                                $date = date('Y-m-d H:i:s', strtotime('+' . $d . ' day', strtotime($row->end_date)));
                            @endphp
                            {{ $date }}
                        </td>
                        <td>
                            <span class="label label-{{ $row->status }}">{{ $row->statusName }}</span>
                        </td>
                        <td>
                            {{ $row->gatewayObj ? $row->gatewayObj->getDisplayName() : '' }}
                        </td>
                        <td>{{ display_datetime($row->created_at) }}</td>
                        <td>{{ $userMeta->bank_IBAN ?? '' }}</td>
                        <td>{{ $userMeta->bank_user_name ?? '' }}</td>
                        <td>{{ $userMeta->bank_name ?? '' }}</td>

                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@push('js')
    <!-- DataTables  & Plugins -->
    <script src="{{ asset('libs/data-table/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('libs/data-table/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
    <script>
        $(function() {
            $("#example1").DataTable({
                // "paging": false,
                "order": [
                    [0, 'desc']
                ],
                "pageLength": 100,
                "responsive": true,
                "lengthChange": false,
                "autoWidth": false,
                "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
            }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
        });
    </script>
@endpush
