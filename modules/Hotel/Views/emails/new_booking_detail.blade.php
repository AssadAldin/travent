<?php
$translation = $service->translate();
$lang_local = app()->getLocale();
use Modules\Media\Models\MediaFile;
use Modules\Hotel\Models\Hotel;
//use app\User;
$hotel = MediaFile::where('id', $translation->getRecordRoot->image_id)->firstOrFail();
$hotelr = Hotel::where('id', $translation->origin_id)->firstOrFail();
if (Auth::check()) {
    $customer = App\User::find($booking->customer_id);
}
$vendor = App\Models\User::find($booking->vendor_id);
// dd($service);
?>
{{-- import hotel to use the banner image --}}

{{-- This is new change --}}
<table class="b-table shadow-lg" cellspacing="0" cellpadding="0" style="margin-bottom:20px">
    @if ($booking->status == 'cancelled')
        <tr>
            <td class="label" style="color: #ff0000">{{ __('Your booking has been cancelled') }}</td>
            <td class="val"></td>
        </tr>
    @endif
    <tr>
        <td class="label">{{ __('Booking Number') }}</td>
        <td class="val">#{{ $booking->id }}</td>
    </tr>
</table>
{{-- <div class="b-panel-title">{{__('Hotel information')}}</div> --}}
@if ($to ?? 0)
    <img src="{{ asset('/uploads/' . $hotel->file_path) }}"
        style="height: auto;width:100%; margin-bottom: 20px; border-radius: 7px;" class="" alt="">
@endif
<div class="b-table-wrap">
    {{-- This is new change --}}
    <table class="b-table " cellspacing="0" cellpadding="0">
        @if ($booking->start_date && $booking->end_date)
            <tr>
                <td class="label">{{ __('Check in') }}</td>
                <td class="val">{{ display_date($booking->start_date) }}</td>
            </tr>
            <tr>
                <td class="label">{{ __('Check out:') }}</td>
                <td class="val">
                    @if ($booking->price_type != 1)
                        {{ display_date($booking->end_date) }}
                    @else
                        {{ display_date($booking->start_date) }}
                    @endif
                </td>
            </tr>
            @if ($booking->price_type != 1)
                <tr>
                    <td class="label">{{ __('nights') }}</td>
                    <td class="val">
                        {{ $booking->duration_nights }}
                    </td>
                </tr>
                <tr>
                    <td class="label">{{ __('Full Day') }}</td>
                    <td class="val">
                        <div>
                            {{ __('Check in') }}&nbsp; <b>{{ $service->check_in_time }}</b> <br>
                            {{ __('Check out') }}&nbsp; <b>{{ $service->check_out_time }}</b>
                        </div>
                    </td>
                </tr>
            @else
                <tr>
                    <td class="label">{{ __('Day Stay') }}</td>
                    <td class="val">
                        @if (!empty($service->check_in2) and !empty($service->check_out2))
                            <div>
                                {{ __('Check in') }}&nbsp;&nbsp; <b>{{ $service->check_in2 }}</b> <br>
                                {{ __('Check out') }}&nbsp;&nbsp; <b>{{ $service->check_out2 }}</b>
                            </div>
                        @else
                            <div>
                                {{ __('Check in') }}&nbsp; <b>{{ $service->check_in_time }}</b> <br>
                                {{ __('Check out') }}&nbsp; <b>{{ $service->check_out_time }}</b>
                            </div>
                        @endif
                    </td>
                </tr>
            @endif
        @endif
    </table>
    <table class="b-table" width="100%">
        @php $rooms = \Modules\Hotel\Models\HotelRoomBooking::getByBookingId($booking->id) @endphp
        @if (!empty($rooms))
            @foreach ($rooms as $room)
                {{--            {{dd($room)}} --}}
                <tr>
                    <td class="label">{{ $room->room->title }} * {{ $room->number }}
                    </td>
                    <td class="val no-r-padding">
                        @if ($room->price_type == 1)
                            {{ format_money($room->price * $room->number) }}
                        @else
                            {{ format_money($room->price2 * $room->number) }}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
        @php $extra_price = $booking->getJsonMeta('extra_price')@endphp

        {{-- @if (!empty($extra_price))
        <tr>
            <td colspan="2" class="label-title"><strong>{{__("Extra Prices:")}}</strong></td>
        </tr>
        <tr class="">
            <td colspan="2" class="no-r-padding no-b-border">
                <table width="100%">
                    @foreach ($extra_price as $type)
                    <tr>
                        <td class="label">{{$type['name']}}:</td>
                        <td class="val no-r-padding">
                            <strong>{{format_money($type['total'] ?? 0)}}</strong>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr>

        @endif --}}

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
        @endphp
        @if (!empty($list_all_fee))
            @foreach ($list_all_fee as $item)
                @php
                    $fee_price = $item['price'];
                    if (!empty($item['unit']) and $item['unit'] == 'percent') {
                        $fee_price = ($booking->total_before_fees / 100) * $item['price'];
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
                @endphp
                @if ($to ?? 0)
                    @if ($to != 'vendor')
                        @if ($fees_service_coupon)
                            <tr>
                                <td class="label">
                                    {{ $item['name_' . $lang_local] ?? $item['name'] }}
                                    <i class="icofont-info-circle" data-toggle="tooltip" data-placement="top"
                                        title="{{ $item['desc_' . $lang_local] ?? $item['desc'] }}"></i>
                                    @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                        {{ $booking->total_guests }} * {{ format_money($fee_price) }}
                                    @endif
                                </td>
                                <td class="val" style="text-decoration: line-through; color:red">
                                    @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                        {{ format_money($fee_price * $booking->total_guests) }}
                                    @else
                                        {{ format_money($fee_price) }}
                                    @endif
                                </td>
                            </tr>
                        @else
                            <tr>
                                <td class="label">
                                    {{ $item['name_' . $lang_local] ?? $item['name'] }}
                                    <i class="icofont-info-circle" data-toggle="tooltip" data-placement="top"
                                        title="{{ $item['desc_' . $lang_local] ?? $item['desc'] }}"></i>
                                    @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                        {{ $booking->total_guests }} * {{ format_money($fee_price) }}
                                    @endif
                                </td>
                                <td class="val">
                                    @if (!empty($item['per_person']) and $item['per_person'] == 'on')
                                        {{ format_money($fee_price * $booking->total_guests) }}
                                    @else
                                        {{ format_money($fee_price) }}
                                    @endif
                                </td>
                            </tr>
                        @endif
                    @endif
                @endif
            @endforeach
        @endif
        @if (!empty($booking->coupon_amount) and $booking->coupon_amount > 0)
            <tr>
                <td class="label">
                    {{ __('Coupon') }}
                </td>
                <td class="val">
                    -{{ format_money($booking->coupon_amount) }}
                </td>
            </tr>
        @endif
    </table>
    <table class="b-table" cellspacing="0" cellpadding="0" style="">
        <tr style="margin: 0; padding:0;">
            @if (!empty($extra_price))
        <tr>
            {{-- <td colspan="2" class="label-title"><strong>{{__("Extra Prices")}}</strong></td> --}}
        </tr>
        <tr class="">
            <td colspan="2" class="no-r-padding no-b-border">
                <table width="100%">
                    @foreach ($extra_price as $type)
                        <tr>
                            <td class="label">{{ $type['name'] }}</td>
                            <td class="val no-r-padding">
                                <div>{{ format_money($type['total'] ?? 0) }}</div>
                            </td>
                        </tr>
                    @endforeach
                </table>
            </td>
        </tr>
        @endif
        <td class="label" style="background-color:#f7a559;">
            <div style="font-weight: bold;">{{ __('Amount') }}</div>
            {{-- <strong><a target="_blank" href="#" >View Receipt</a></strong> --}}
        </td>
        @if ($to ?? 0)
            @if ($to == 'vendor')
                <td class="val" style="background-color:#f7a559;">
                    <Strong>{{ format_money($booking->total) }}</strong>
                </td>
            @else
                <td class="val" style="background-color:#f7a559;">
                    <Strong>{{ format_money($booking->total) }}</strong>
                </td>
            @endif
        @else
            <td class="val" style="background-color:#f7a559;">
                <Strong>{{ format_money($booking->total) }}</strong>
            </td>
        @endif
        </tr>
        @if ($to ?? 0)
            @if ($to == 'customer')
                <tr style="margin: 0;padding:0;">
                    @if ($translation->address)
                        <td class="label">
                            <div>{{ __('Address') }}</div>{{ $translation->address }}
                        </td>
                        <td class="val">
                            {{-- <a target="_blank" href="{{$service->getDetailUrl()}}/#direction" >Get Direction</a> --}}
                            <a target="_blank"
                                href={{ 'http://maps.google.com/maps?q=' . $hotelr->map_lat . ',' . $hotelr->map_lng }}
                                target="_blank">Get Direction</a>
                        </td>
                    @endif
                </tr>
                <tr>
                    <td class="label">
                        <div>{{ __('Host Name') }}</div>
                    </td>
                    <td class="val" colspan="2" style="color: #f7a559;width: 260px;">
                        <a target="_blank" href="{{ $service->getDetailUrl() }}">{!! clean($translation->title) !!}</a>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div>{{ __('Host Phone') }}</div>
                    </td>
                    <td class="val" colspan="2" style="color: #f7a559;width: 260px;">
                        {{-- {!! clean($translation->phone) !!} --}}
                        @if ($booking->status != 'cancelled')
                            <div class="d-flex">
                                <p class="">
                                    <a class="mx-3"
                                        href="tel:{{ $vendor->phone ?? '' }}">{{ $vendor->phone ?? '' }}</a>
                                    <a
                                        href="{{ 'https://wa.me/' . $vendor->phone . '?text=' . 'Hi' . ', ' . __('I am, ') . $booking->first_name . ' I booked your property ' . $translation->title . ' check in ' . display_date($booking->start_date) . ' - ' . ' check out ' . display_date($booking->end_date) . ';' }}">{{ __('Whatsapp Chat') }}</a>
                                    {{--                        <a href="{{ url('whatsapp://send?'.$translation->title.'/&+971566628637/') }}"><img src="{{asset('image/whatsapp.png')}}" style="width: 15%" class="mx-3" alt=""></a> --}}
                                </p>
                            </div>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div>{{ __('Know what to expect') }}</div>
                        <p style="color: #f7a559;width: 260px;">
                            {{ __('Make sure you know more about the property and its house rules') }}</p>
                    </td>
                    <td class="val">
                        <a target="_blank" href="{{ $service->getDetailUrl() }}/#rules">{{ __('View House Rules') }}
                        </a>
                    </td>
                </tr>
            @endif
            @if ($to == 'vendor')
                @if (Auth::check())
                    <tr>
                        <td class="label">
                            <div>{{ __('Guest Name') }}</div>
                        </td>
                        <td class="val">
                            {{-- {!! clean($translation->phone) !!} --}}
                            <div>{!! clean($booking->first_name) !!} {!! clean($booking->last_name) !!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <div>{{ __('Guest Phone') }}</div>
                        </td>
                        <td class="val">
                            {{-- {!! clean($translation->phone) !!} --}}
                            @if ($booking->status != 'cancelled')
                                {{ $customer->phone ?? '' }}
                            @endif
                        </td>
                    </tr>
                @else
                    <tr>
                        <td class="label">
                            <div>{{ __('Guest Name') }}</div>
                        </td>
                        <td class="val">
                            <div>{!! clean($booking->first_name) !!} {!! clean($booking->last_name) !!}</div>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">
                            <div>{{ __('Guest Phone') }}</div>
                        </td>
                        <td class="val">
                            {{-- {!! clean($translation->phone) !!} --}}
                            @if ($booking->status != 'cancelled')
                                {{ $booking->phone ?? '' }}
                            @endif
                        </td>
                    </tr>
                @endif
            @endif
            @if ($to == 'admin')
                @if ($translation->address)
                    <tr style="margin: 0;padding:0;">
                        <td class="label">
                            <div>{{ __('Address') }}</div>{{ $translation->address }}
                        </td>
                        <td class="val">
                            {{-- <a target="_blank" href="{{$service->getDetailUrl()}}/#direction" >Get Direction</a> --}}
                            <a target="_blank"
                                href={{ 'http://maps.google.co.uk/maps?q=' . $hotelr->map_lat . ',' . $hotelr->map_lng }}
                                target="_blank">Get Direction</a>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td class="label">
                        <div style="font-weight: bold;">{{ __('Guest Name') }}</div>
                    </td>
                    <td class="val">
                        {{-- {!! clean($translation->phone) !!} --}}
                        <p>{!! clean($booking->first_name) !!} {!! clean($booking->last_name) !!}</p>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div style="font-weight: bold;">{{ __('Guest Phone') }}</div>
                    </td>
                    <td class="val">
                        {{-- {!! clean($translation->phone) !!} --}}
                        {{ $booking->phone ?? '' }}<br>
                        <a class="mx-3"
                            href="{{ 'https://wa.me/' . $booking->phone . '?text=' . 'Hi' . ', ' . __('I am, ') . $vendor->first_name . ' you booked my property ' . $translation->title . ', check in ' . display_date($booking->start_date) . ' - ' . ' check out ' . display_date($booking->end_date) . ';' }}">{{ __('Whatsapp Chat') }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div style="font-weight: bold;">{{ __('Host Name') }}</div>
                    </td>
                    <td class="val" colspan="2" style="color: #f7a559;width: 260px;">
                        {{-- {!! clean($translation->phone) !!} --}}
                        <a target="_blank" href="{{ $service->getDetailUrl() }}">{!! clean($translation->title) !!}</a>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div style="font-weight: bold;">{{ __('Host Phone') }}</div>
                    </td>
                    <td class="val" colspan="2" style="color: #f7a559;width: 260px;">
                        {{-- {!! clean($translation->phone) !!} --}}
                        <div class="d-flex">
                            <p class="d-flex align-items-center">
                                <a class="mx-3"
                                    href="tel:{{ $vendor->phone2 ?? '' }}">{{ $vendor->phone2 ?? '' }}</a><br>
                                <a class="mx-3"
                                    href="tel:{{ $vendor->phone ?? '' }}">{{ $vendor->phone ?? '' }}</a><br>
                                <a class="mx-3"
                                    href="{{ 'https://wa.me/' . $vendor->phone . '?text=' . 'Hi' . ', ' . __('I am, ') . $booking->first_name . ' I booked your property ' . $translation->title . ', check in ' . display_date($booking->start_date) . ' - ' . ' check out ' . display_date($booking->end_date) . ';' }}">{{ __('Whatsapp Chat') }}</a>
                                {{--                        <a href="{{ url('whatsapp://send?'.$translation->title.'/&+971566628637/') }}"><img src="{{asset('image/whatsapp.png')}}" style="width: 15%" class="mx-3" alt="{{__("Whatsapp Me")}}"></a> --}}
                            </p>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td class="label">
                        <div style="font-weight: bold;">{{ __('Know what to expect') }}</div>
                        <p style="width: 260px;">
                            {{ __('Make sure you know more about the property and its house rules') }}</p>
                    </td>
                    <td class="val">
                        <a target="_blank" href="{{ $service->getDetailUrl() }}/#rules">{{ __('View House Rules') }}
                        </a>
                    </td>
                </tr>
            @endif
        @endif
        <tr>
            <td class="label" colspan=2>
                <div style="color: #ff0000;font-weight: bold;">{{ __('Cancellation Policy') }}</div>
                <p>{{ __($booking->cancelation) ?? '' }}</p>
            </td>
        </tr>
        @if ($booking->customer_notes)
            <tr>
                <td class="label" colspan=2>
                    <div style="color: #ff0000;font-weight: bold;">{{ __('Special Requirements') }}</div>
                    <p>{{ __($booking->customer_notes) ?? '' }}</p>
                </td>
            </tr>
        @endif
        <tr>
            <td class="label">
                <div>{{ __('Customer Support') }}</div>
                <p>24/7</p>
            </td>
            <td class="val">
                <a target="_blank" href="{{ $service->getDetailUrl() }}/#contact"><a
                        href="tel:+971502205523">971502205523</a></a>
            </td>
        </tr>
        {{-- <tr>
            <td class="label">{{__('Booking Status')}}</td>
        <td class="val">{{$booking->statusName}}</td>
        </tr>
        @if ($booking->gatewayObj)
        <tr>
            <td class="label">{{__('Payment method')}}</td>
            <td class="val">{{$booking->gatewayObj->getOption('name')}}</td>
        </tr>
        @endif
        @if ($booking->gatewayObj and ($note = $booking->gatewayObj->getOption('payment_note')))
        <tr>
            <td class="label">{{__('Payment Note')}}</td>
            <td class="val">{!! clean($note) !!}</td>
        </tr>
        @endif

        @if ($meta = $booking->getMeta('adults'))
        <tr>
            <td class="label">{{__('Adults')}}:</td>
            <td class="val">
                <strong>{{$meta}}</strong>
            </td>
        </tr>
        @endif
        @if ($meta = $booking->getMeta('children'))
        <tr>
            <td class="label">{{__('Children')}}:</td>
            <td class="val">
                <strong>{{$meta}}</strong>
            </td>
        </tr>
        @endif --}}
        <tr>
            {{-- <td class="label">{{__('Pricing')}}</td> --}}
            {{-- <td class="val"> --}}
            {{-- <table class="pricing-list" width="100%"> --}}
            @php $rooms = \Modules\Hotel\Models\HotelRoomBooking::getByBookingId($booking->id) @endphp
            @if (!empty($rooms))
                @foreach ($rooms as $room)
                    {{-- <tr>
                        <td class="label">{{$room->room->title}} * {{$room->number}}
            :
            </td>
            <td class="val no-r-padding">
                <strong>{{format_money($room->price * $room->number)}}</strong>
            </td>
        </tr> --}}
                @endforeach
            @endif
            @php $extra_price = $booking->getJsonMeta('extra_price')@endphp

            @if (!empty($extra_price))
                {{-- <tr>
                        <td colspan="2" class="label-title"><strong>{{__("Extra Prices:")}}</strong></td>
        </tr>
        <tr class="">
            <td colspan="2" class="no-r-padding no-b-border">
                <table width="100%">
                    @foreach ($extra_price as $type)
                    <tr>
                        <td class="label">{{$type['name']}}:</td>
                        <td class="val no-r-padding">
                            <strong>{{format_money($type['total'] ?? 0)}}</strong>
                        </td>
                    </tr>
                    @endforeach
                </table>
            </td>
        </tr> --}}
            @endif

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
            @if (!empty($list_all_fee))
                @foreach ($list_all_fee as $item)
                    @php
                        $fee_price = $item['price'];
                        if (!empty($item['unit']) and $item['unit'] == 'percent') {
                            $fee_price = ($booking->total_before_fees / 100) * $item['price'];
                        }
                    @endphp
                    {{-- <tr>
                        <td class="label">
                            {{$item['name_'.$lang_local] ?? $item['name']}}
        <i class="icofont-info-circle" data-toggle="tooltip" data-placement="top" title="{{ $item['desc_'.$lang_local] ?? $item['desc'] }}"></i>
        @if (!empty($item['per_person']) and $item['per_person'] == 'on')
        : {{$booking->total_guests}} * {{format_money( $fee_price )}}
        @endif
        </td>
        <td class="val">
            @if (!empty($item['per_person']) and $item['per_person'] == 'on')
            {{ format_money( $fee_price * $booking->total_guests ) }}
            @else
            {{ format_money( $fee_price ) }}
            @endif
        </td>
        </tr> --}}
                @endforeach
            @endif
            @if (!empty($booking->coupon_amount) and $booking->coupon_amount > 0)
                {{-- <tr>
                        <td class="label">
                            {{__("Coupon")}}
        </td>
        <td class="val">
            -{{ format_money($booking->coupon_amount) }}
        </td>
        </tr> --}}
            @endif
    </table>
    </td>
    </tr>
    {{-- <tr>
            <td class="label fsz21">{{__('Total')}}</td>
    <td class="val fsz21"><strong style="color: #FA5636">{{format_money($booking->total)}}</strong></td>
    </tr>
    <tr>
        <td class="label fsz21">{{__('Paid')}}</td>
        <td class="val fsz21"><strong style="color: #FA5636">{{format_money($booking->paid)}}</strong></td>
    </tr>
    @if ($booking->total > $booking->paid)
    <tr>
        <td class="label fsz21">{{__('Remain')}}</td>
        <td class="val fsz21"><strong style="color: #FA5636">{{format_money($booking->total - $booking->paid)}}</strong></td>
    </tr>
    @endif --}}
    </table>
</div>
<div class="text-center mt20">
    <a target="_blank" href="{{ route('user.booking_history') }}" target="_blank"
        class="btn btn-primary manage-booking-btn">{{ __('Manage Bookings') }}</a>
</div>
