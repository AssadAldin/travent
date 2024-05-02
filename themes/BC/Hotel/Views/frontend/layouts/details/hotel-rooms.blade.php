<div id="hotel-rooms" class="hotel_rooms_form" v-cloak="" :class="{ 'd-none': enquiry_type != 'book' }">
    <h3 class="heading-section">{{ __('Available Rooms') }}</h3>
    {{--    <div class="nav-enquiry" v-if="is_form_enquiry_and_book"> --}}
    {{--    {{dd()}} --}}
    <div class="form-book">
        <div class="form-search-rooms">
            <div class="d-flex form-search-row">
                <div class="col-md-6">
                    <div class="form-group form-date-field form-date-search" @click="openStartDate"
                        data-format="{{ get_moment_date_format() }}">
                        <i class="fa fa-angle-down arrow"></i>
                        <input type="text" class="start_date" ref="start_date"
                            style="height: 1px; visibility: hidden">
                        <div class="date-wrapper form-content">
                            <label class="form-label">{{ __('Check In - Out') }}</label>
                            <div class="render check-in-render" v-html="start_date_html"></div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 d-none">
                    <div class="form-group">
                        <i class="fa fa-angle-down arrow"></i>
                        <div class="form-content dropdown-toggle" data-toggle="dropdown">
                            <label class="form-label">{{ __('Guests') }}</label>
                            <div class="render">
                                <span class="adults">
                                    <span class="one">@{{ adults }}
                                        <span v-if="adults < 2">{{ __('Adult') }}</span>
                                        <span v-else>{{ __('Adults') }}</span>
                                    </span>
                                </span>
                                <!----->
                                <span class="children hidden d-none">
                                    <span class="one">@{{ children }}
                                        <span v-if="children < 2">{{ __('Child') }}</span>
                                        <span v-else>{{ __('Children') }}</span>
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="dropdown-menu select-guests-dropdown">
                            <div class="dropdown-item-row">
                                <div class="label">{{ __('Adults') }}</div>
                                <div class="val">
                                    <span class="btn-minus2" data-input="adults" @click="minusPersonType('adults')"><i
                                            class="icon ion-md-remove"></i></span>
                                    <span class="count-display"><input type="number" v-model="adults"
                                            min="1" /><input type="text" name="price_type"
                                            value="5" /></span>
                                    <span class="btn-add2" data-input="adults" @click="addPersonType('adults')"><i
                                            class="icon ion-ios-add"></i></span>
                                </div>
                            </div>
                            <div class="dropdown-item-row hidden d-none">
                                <div class="label">{{ __('Children') }}</div>
                                <div class="val">
                                    <span class="btn-minus2" data-input="children"
                                        @click="minusPersonType('children')"><i class="icon ion-md-remove"></i></span>
                                    <span class="count-display"><input type="number" v-model="children"
                                            min="0" /></span>
                                    <span class="btn-add2" data-input="children" @click="addPersonType('children')"><i
                                            class="icon ion-ios-add"></i></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-btn">
                    <div class="g-button-submit">
                        <button class="btn btn-primary btn-search" @click="checkAvailability"
                            :class="{ 'loading': onLoadAvailability }" type="submit">
                            {{ __('Check Availability') }}
                            <i v-show="onLoadAvailability" class="fa fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="nav-enquiry" v-if="start_date && end_date">
            <div :class="{ 'enquiry-item': true, 'active': !togglePrice }" @click="fullDay">
                <span>{{ __('Full Day') }}</span>
            </div>
            <div :class="{ 'enquiry-item': true, 'active': togglePrice, 'd-none': dayStayDisable }" @click="dayStay">
                <span>{{ __('Day Stay') }}</span>
            </div>
        </div>
        <div class="start_room_sticky"></div>
        <div v-if="start_date && end_date" class="hotel_list_rooms" :class="{ 'loading': onLoadAvailability }">
            <div class="row">
                <div class="col-md-12">
                    <div class="room-item" v-for="room in rooms">
                        <div class="row mt-2">
                            <div class="col-xs-12 p-1">
                                <div class="image d-none" @click="showGallery($event,room.id,room.gallery)">
                                    <img :src="room.image" alt="">
                                    <div class="count-gallery"
                                        v-if="typeof room.gallery !='undefined' && room.gallery && room.gallery.length > 1">
                                        <i class="fa fa-picture-o"></i>
                                        @{{ room.gallery.length }}
                                    </div>
                                </div>
                                <div class="modal" :id="'modal_room_' + room.id" tabindex="-1" role="dialog">
                                    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">@{{ room.title }}</h5>
                                                <span class="c-pointer" data-dismiss="modal" aria-label="Close">
                                                    <i class="input-icon field-icon fa">
                                                        <img src="{{ asset('images/ico_close.svg') }}"
                                                            alt="close">
                                                    </i>
                                                </span>
                                            </div>
                                            <div class="modal-body">
                                                <div class="fotorama" data-nav="thumbs" data-width="100%"
                                                    data-auto="false" data-allowfullscreen="true">
                                                    <a v-for="g in room.gallery" :href="g.large"></a>
                                                </div>
                                                <div class="list-attributes">
                                                    <div class="attribute-item" v-for="term in room.terms">
                                                        <h4 class="title">@{{ term.parent.title }}</h4>
                                                        <ul v-if="term.child">
                                                            <li v-for="term_child in term.child">
                                                                <i class="input-icon field-icon"
                                                                    v-bind:class="term_child.icon"
                                                                    data-toggle="tooltip" data-placement="top"
                                                                    :title="term_child.title"></i>
                                                                @{{ term_child.title }}
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-6 ">
                                <div class="hotel-info">
                                    <h3 class="room-name" @click="showGallery($event,room.id,room.gallery)">
                                        @{{ room.title }}</h3>
                                    <ul class="room-meta">
                                        <li v-if="room.size_html">
                                            <div class="item" data-toggle="tooltip" data-placement="top"
                                                title="" data-original-title="{{ __('Room Footage') }}">
                                                <i class="input-icon field-icon icofont-ruler-compass-alt"></i>
                                                <span v-html="room.size_html"></span>
                                            </div>
                                        </li>
                                        <li v-if="room.beds_html">
                                            <div class="item" data-toggle="tooltip" data-placement="top"
                                                title="" data-original-title="{{ __('No. Beds') }}">
                                                <i class="input-icon field-icon icofont-hotel"></i>
                                                <span v-html="room.beds_html"></span>
                                            </div>
                                        </li>
                                        <li v-if="room.adults_html">
                                            <div class="item" data-toggle="tooltip" data-placement="top"
                                                title="" data-original-title="{{ __('No. Adults') }}">
                                                <i class="input-icon field-icon icofont-users-alt-4"></i>
                                                <span v-html="room.adults_html"></span>
                                            </div>
                                        </li>
                                        <li class="hidden d-none" v-if="room.children_html">
                                            <div class="item" data-toggle="tooltip" data-placement="top"
                                                title="" data-original-title="{{ __('No. Children') }}">
                                                <i class="input-icon field-icon fa-child fa"></i>
                                                <span v-html="room.children_html"></span>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <div class="col-md-3" v-if="room.number">
                                <div class="col-price clear">
                                    <!--<div class="text-center" v-if="Number(room.number_selected)">-->
                                    <!--    <span class="price" v-if="togglePrice && total_price"-->
                                    <!--        v-html="room.price_html"></span>-->
                                    <!--</div>-->
                                    <!--<div class="text-center" v-if="Number(room.number_selected)">-->
                                    <!--    <span class="price" v-if="!togglePrice && total_price"-->
                                    <!--        v-html="room.price_html2"></span>-->
                                    <!--</div>-->
                                    <div>
                                        <div v-if="room.fullDay || togglePrice">
                                            <div v-if="room.dayStay || !togglePrice">
                                                <div class="d-flex align-items-center">
                                                    <label class="px-2">{{ __('Price') }}</label>
                                                    <select v-if="room.number" v-model="room.number_selected"
                                                        class="custom-select">
                                                        {{-- Add disabled to make sure that customer choose one room --}}
                                                        {{-- <option v-for="i in (1,room.number)" :disabled="total_price ? true : false" :value="i" @click="togglePrice = !togglePrice"> --}}
                                                        @if (false)
                                                            <option v-for="i in (1,room.number)" v-if="togglePrice"
                                                                :value="i">
                                                                @{{ i + ' ' + (i > 1 ? i18n.rooms : i18n.room) }} &nbsp;&nbsp;(@{{ formatMoney(i * room.price) }})
                                                            </option>

                                                            <div v-if="room.fullDay">
                                                                <option v-for="i in (1,room.number)" v-if="!togglePrice"
                                                                    :value="i">
                                                                    @{{ i + ' ' + (i > 1 ? i18n.rooms : i18n.room) }} &nbsp;&nbsp;
                                                                    (@{{ formatMoney(i * room.price2) }})
                                                                </option>
                                                            </div>
                                                        @else
                                                            <option v-for="i in (1,room.number)" v-if="togglePrice"
                                                                :value="i">
                                                                @{{ i + ' ' + (i > 1 ? i18n.rooms : i18n.room) }} &nbsp;&nbsp;
                                                                (@{{ formatMoney(i * room.price+room.service_fee) }})
                                                            </option>

                                                            <div v-if="room.fullDay">
                                                                <option v-for="i in (1,room.number)" v-if="!togglePrice"
                                                                    :value="i">
                                                                    @{{ i + ' ' + (i > 1 ? i18n.rooms : i18n.room) }} &nbsp;&nbsp;
                                                                    (@{{ formatMoney(i * room.price2+room.service_fee2) }})
                                                                </option>
                                                            </div>
                                                        @endif
                                                        <option value="0">0</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="text-justify p-2">
                            @{{ room.content }}
                        </div>
                        <div class="p-2" style="background-color: #f1f1f1">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="row p-1 mb-1 mt-0 mx-0 align-items-center" v-if="room.term_features">
                                    <div class="d-flex" v-for="term_child in room.term_features">
                                        {{-- v-bind:class="term_child.icon" --}}
                                        <i class="fa fa-check m-1" aria-hidden="true"></i>
                                        <i class="m-1" style="font-size:12px; font-style: normal;"
                                            data-toggle="tooltip" data-placement="top" v-html="term_child.title"></i>
                                    </div>
                                </div>
                                <div v-if="room.term_features.length>4">
                                    <button type="button" @click="showGallery($event,room.id,room.gallery)"
                                        style="font-size:12px; font-style: normal;background-color: #264653;font-weight: normal;line-height: 1.5;"
                                        class="btn-sm btn btn-primary p-1 mx-3 text-sm-left">{{ __('Show more') }}</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="" v-if="!total_price">
            {{-- <div class="d-none d-md-block"> --}}
            <a href="#book-now" class="float @if(!$w2n) w2n-h @endif text-decoration-none" style="background-color: #0275d8;">
                <p class="my-float">{{ __('Book') }}</p>
                {{-- <i class="fa fa-home fa-2x" aria-hidden="true"></i> --}}
            </a>
        </div>
        <div class="" v-if="!total_price">&nbsp;
            {{-- <div class="d-none d-md-block d-none">&nbsp; --}}
            <a target="_blank"
                href="{{ 'https://wa.me/971502205523?text=' . $row->getDetailUrl() . ' , ' . __('Hi I am looking for this farm,') . $translation->title . ',' }}"
                class="float @if(!$w2n) w2n-h @endif text-decoration-none" style="right: 80px!important;background-color: white;">
                <p class=""><img src="{{ asset('image/whatsapp.png') }}" style="width: 60px" class=""
                        alt="whatsapp"></p>
                {{-- <i class="fa fa-home fa-2x" aria-hidden="true"></i> --}}
            </a>
        </div>
        <div class="hotel_room_book_status" v-if="total_price">
            <div class="row row_extra_service" v-if="extra_price.length">
                <div class="col-md-12">
                    <div class="form-section-group">
                        <label>{{ __('Extra prices:') }}</label>
                        <div class="row">
                            <div class="col-md-6 extra-item" v-for="(type,index) in extra_price">
                                <div class="extra-price-wrap d-flex justify-content-between">
                                    <div class="flex-grow-1">
                                        <label>
                                            <input type="checkbox" true-value="1" false-value="0"
                                                v-model="type.enable"> @{{ type.name }}
                                            <div class="render" v-if="type.price_type">(@{{ type.price_type }})</div>
                                        </label>
                                    </div>
                                    <div class="flex-shrink-0">@{{ type.price_html }}
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row row_total_price">
                <div class="col-md-6">
                    <div class="extra-price-wrap d-flex justify-content-between">
                        <div class="flex-grow-1">
                            <label>
                                {{ __('Total Room') }}:
                            </label>
                        </div>
                        <div class="flex-shrink-0">
                            @{{ total_rooms }}
                        </div>
                    </div>
                    @if (false)
                        <div class="extra-price-wrap d-flex justify-content-between" v-for="(type,index) in buyer_fees">
                            <div class="flex-grow-1">
                                <label>
                                    @{{ type.type_name }}
                                    <span class="render" v-if="type.price_type">(@{{ type.price_type }})</span>
                                    <i class="icofont-info-circle" v-if="type.desc" data-toggle="tooltip"
                                        data-placement="top" :title="type.type_desc"></i>
                                </label>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="unit" v-if='type.unit == "percent"'>
                                    @if ($line)
                                        @if ($coupon->without_service_fees)
                                            <s class="text-danger">
                                                @endif@{{ formatMoney(total_price_before_fee * type.price / 100) }}@if ($line)
                                            </s>
                                        @endif
                                    @else
                                        @{{ formatMoney(total_price_before_fee * type.price / 100) }}
                                    @endif
                                </div>
                                <div class="unit" v-else>
                                    @{{ formatMoney(type.price) }}
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="extra-price-wrap  is_mobile">
                        <div class="flex-grow-1">
                            <label>
                                {{ __('Total Price') }}:
                            </label>
                        </div>
                        <div class="total-room-price">
                            @if ($line)
                                <del>
                                    @endif @{{ total_price_html }} @if ($line)
                                </del>
                            @endif
                        </div>&nbsp
                        <br>
                        @if ($line)
                            {{-- @dd($coupon) --}}
                            @if ($coupon->without_service_fees and $coupon->amount > 0)
                                @if ($coupon->discount_type == 'percent')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_before_fee - total_price_before_fee * @json($coupon->amount) / 100 }}
                                    </div>
                                @endif
                                @if ($coupon->discount_type == 'fixed')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_before_fee - @json($coupon->amount) }}
                                    </div>
                                @endif
                            @else
                                @if ($coupon->discount_type == 'percent')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_html2 - total_price_html2 * @json($coupon->amount) / 100 }}
                                    </div>
                                @endif
                                @if ($coupon->discount_type == 'fixed')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_html2 - @json($coupon->amount) }}
                                    </div>
                                @endif
                            @endif
                        @endif
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="control-book">
                        <div class="total-room-price">
                            <span> {{ __('Total Price') }}:</span>
                            @if ($line)
                                <del>
                                    @endif @{{ total_price_html }} @if ($line)
                                </del>
                            @endif
                        </div>
                        @if ($line)
                            @if ($coupon->without_service_fees)
                                @if ($coupon->discount_type == 'percent')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_before_fee - total_price_before_fee * @json($coupon->amount) / 100 }}
                                    </div>
                                @endif
                                @if ($coupon->discount_type == 'fixed')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_before_fee - @json($coupon->amount) }}
                                    </div>
                                @endif
                            @else
                                @if ($coupon->discount_type == 'percent')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_html2 - total_price_html2 * @json($coupon->amount) / 100 }}
                                    </div>
                                @endif
                                @if ($coupon->discount_type == 'fixed')
                                    <div class="total-room-price">
                                        <span>{{ __('After Discount') }}</span>&nbsp@{{ total_price_html2 - @json($coupon->amount) }}
                                    </div>
                                @endif
                            @endif
                        @endif
                        <div v-if="is_deposit_ready" class="total-room-price">
                            <span>{{ __('Pay now') }}</span>
                            @{{ pay_now_price_html }}
                        </div>
                        <button type="button" class="btn btn-primary"
                            @click="doSubmit($event, '{{ $couponCode }}')" :class="{ 'disabled': onSubmit }"
                            name="submit">
                            <span>{{ __('Book Now') }}</span>
                            <i v-show="onSubmit" class="fa fa-spinner fa-spin"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="end_room_sticky"></div>
        <div class="alert alert-warning" v-if="!firstLoad && !rooms.length">
            {{ __('No room available with your selected date. Please change your search critical') }}
        </div>
    </div>
</div>
@include('Booking::frontend.global.enquiry-form', ['service_type' => 'hotel'])
