@php
    use Modules\Hotel\Models\Hotel;
    use App\Models\HotelCancelationPolicy;
    use Modules\Coupon\Models\Coupon;
    $line = false;
    $terms_ids = $row->terms->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
    $hotel_p = Hotel::find($translation->origin_id);
    $policy_h = HotelCancelationPolicy::find($hotel_p->cancelation_id);
    $coupon = Coupon::find(Request::get('coupon'));
    if ($coupon) {
        if ($coupon->checkCoupon()) {
            if ($coupon->status == 'publish') {
                $line = true;
            }
        }
    }
    // dd($line);
    $couponCode = Request::get('coupon');
    $w2n = Str::contains(request()->userAgent(), 'w2n');
@endphp
<style>
    .float {
        position: fixed;
        width: 60px;
        height: 60px;
        bottom: 40px;
        right: 15px;
        /*background-color: #f7a559;*/
        color: #FFF;
        border-radius: 50px;
        text-align: center;
        font-size: 15px;
        z-index: 100;
    }
    
    .w2n-h{
        bottom: 85px;
    }

    .float:hover {
        color: #f1f1f1;
        /*background-color: #fd7e14;*/
    }

    .my-float {
        margin-top: 18px;
        font-weight: bold;
    }

    .btn-circle.btn-xl {
        width: 60px;
        height: 60px;
        padding: 10px;
        border-radius: 60px;
        font-size: 15px;
        text-align: center;
        color: white;
        background-color: #0275d8;
    }
</style>

<div class="g-header">
    <div class="left">
        @if ($row->star_rate)
            <div class="star-rate">
                @for ($star = 1; $star <= $row->star_rate; $star++)
                    <i class="fa fa-star"></i>
                @endfor
            </div>
        @endif
        <h1>{{ $translation->title }}</h1>
        @if ($translation->address)
            <h2 class="address"><i class="fa fa-map-marker"></i>
                {{ $translation->address }}
            </h2>
        @endif
    </div>
    <div class="right">
        @if ($row->getReviewEnable())
            @if ($review_score)
                <div class="review-score">
                    <div class="head">
                        <div class="left">
                            <span class="head-rating">{{ $review_score['score_text'] }}</span>
                            <span
                                class="text-rating">{{ __('from :number reviews', ['number' => $review_score['total_review']]) }}</span>
                        </div>
                        <div class="score">
                            {{ $review_score['score_total'] }}<span>/5</span>
                        </div>
                    </div>
                    <div class="foot">
                        {{ __(':number% of guests recommend', ['number' => $row->recommend_percent]) }}
                    </div>
                </div>
            @endif
        @endif
    </div>
</div>
<!--<div class="d-flex align-items flex-row-reverse d-none">-->
<!--    <div class="d-md-none d-sm-block mx-2">-->
<!--        <a href="{{ 'https://wa.me/971502205523?text=' . __('Hi I am looking for this farm,') . $translation->title . ',' }}" ><img src="{{ asset('image/whatsapp.png') }}" style="width: 60px" class="" alt="whatsapp"></a>-->
<!--    </div>-->
<!--    <div class="d-md-none d-sm-block">-->
<!--        <a href="#book-now" >-->
<!--            <button type="button" class="btn btn-circle btn-xl font-weight-bold">{{ __('Book') }}</button>-->
<!--        </a>-->
<!--    </div>-->
<!--</div>-->
@if ($row->getGallery())
    <div class="g-gallery">
        <!--data-width="100%"-->
        <!--Sort image edited by Assadaldin-->
        <div class="d-sm-block d-md-none">
            <div class="row justify-content-center">
                <div class="fotorama" data-width="100%" data-thumbwidth="100" data-thumbheight="135"
                    data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
                    @foreach (collect($row->getGallery())->sortBy('priority') as $key => $item)
                        <!--sort image edit by Assadaldin-->
                        @if ($item != null)
                            <a href="<?php echo \Modules\Media\Helpers\FileHelper::url($item, 'thumb'); ?>" data-thumb="{{ $item['thumb'] }}"
                                data-alt="{{ __('Gallery') }}"></a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div id="book-now"></div>
        {{-- data-fit="cover" --}}
        <div class="d-md-block d-none">
            <div class="row justify-content-center">
                <div class="fotorama" data-ratio="600/350" data-width="100%" data-thumbwidth="100"
                    data-thumbheight="135" data-thumbmargin="15" data-nav="thumbs" data-allowfullscreen="true">
                    @foreach (collect($row->getGallery())->sortBy('priority') as $key => $item)
                        <!--sort image edit by Assadaldin-->
                        @if ($item != null)
                            <a href="<?php echo \Modules\Media\Helpers\FileHelper::url($item, 'thumb'); ?>" data-thumb="{{ $item['thumb'] }}"
                                data-alt="{{ __('Gallery') }}"></a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="social">
            <!--<div class="social-share">-->
            <!--    <span class="social-icon">-->
            <!--        <i class="icofont-share"></i>-->
            <!--    </span>-->
            <!--    <ul class="share-wrapper">-->
            <!--        <li>-->
            <!--            <a class="facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}" target="_blank" rel="noopener" original-title="{{ __('Facebook') }}">-->
            <!--                <i class="fa fa-facebook fa-lg"></i>-->
            <!--            </a>-->
            <!--        </li>-->
            <!--        <li>-->
            <!--            <a class="twitter" href="https://twitter.com/share?url={{ $row->getDetailUrl() }}&amp;title={{ $translation->title }}" target="_blank" rel="noopener" original-title="{{ __('Twitter') }}">-->
            <!--                <i class="fa fa-twitter fa-lg"></i>-->
            <!--            </a>-->
            <!--        </li>-->
            <!--    </ul>-->
            <!--</div>-->
            <a href="whatsapp://send?text={{ $row->getDetailUrl(false) }}">
                <div class="service-wishlist">
                    <i class="fa fa-whatsapp"></i>
                </div>
            </a>
            <div class="service-wishlist {{ $row->isWishList() }}"
                @if ($row->isWishList()) style="color: #f2a260;" @endif data-id="{{ $row->id }}"
                data-type="{{ $row->type }}">
                <i class="fa fa-heart-o"></i>
            </div>
        </div>
    </div>

@endif
<div class="g-attributes">
    <h3>{{ __('Book now') }}</h3>
</div>
<div>
    @include('Hotel::frontend.layouts.details.hotel-rooms')
</div>
@if ($translation->content)
    <div class="g-overview">
        <h3>{{ __('Description') }}</h3>
        <div class="description">
            <?php echo $translation->content; ?>
        </div>
    </div>
@endif
@if ($policy_h->item ?? '' != null)
    <div class="g-attributes">
        <h3>{{ __('Cancelation policy') }}</h3>
        <div class="">
            <div class="d-flex">
                <div class="m-1">
                    {{-- <i class="fa fa-ban fa-3x" style="color:red!important;" aria-hidden="true"></i> --}}
                    <img src="{{ asset('image/cancellation-policy.png') }}" style="width: 30px" class="" />
                </div>
                <div>
                    <p class="text-justify">{{ __($policy_h->item ?? '') }}.</p>
                </div>
            </div>
        </div>
    </div>
@endif

<div class="g-rules" id="rules">
    <h3>{{ __('Rules') }}</h3>
    <div class="description">
        <div class="row">
            <div class="col-lg-4">
                <div class="key">{{ __('Check In') }}</div>
            </div>
            <div class="col-lg-8">
                <div class="value"> {{ $row->check_in_time }} </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4">
                <div class="key">{{ __('Check Out') }}</div>
            </div>
            <div class="col-lg-8">
                <div class="value"> {{ $row->check_out_time }} </div>
            </div>
        </div>
        @if ($translation->policy)
            <div class="row">
                <div class="col-lg-4">
                    <div class="key">{{ __('Hotel Policies') }}</div>
                </div>
                <div class="col-lg-8">
                    @foreach ($translation->policy as $key => $item)
                        <div class="item @if ($key > 1) d-none @endif">
                            <div class="strong">{{ $item['title'] }}</div>
                            <div class="context">{!! $item['content'] !!}</div>
                        </div>
                    @endforeach
                    @if (count($translation->policy) > 2)
                        <div class="btn-show-all">
                            <span class="text">{{ __('Show All') }}</span>
                            <i class="fa fa-caret-down"></i>
                        </div>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>
<div class="g-all-attribute is_mobile">
    @include('Hotel::frontend.layouts.details.hotel-attributes')
</div>
<div class="bravo-hr"></div>
@includeIf('Hotel::frontend.layouts.details.hotel-surrounding')
@if ($row->map_lat && $row->map_lng)
    <a class="btn btn-primary my-3" target="_blank"
        href="https://maps.google.com/?ll={{ $row->map_lat }},{{ $row->map_lng }}">{{ __('Google Map') }}</a>
    <div class="g-location">
        <div class="location-title">
            <h3>{{ __('Location') }}</h3>
            @if ($translation->address)
                <div class="address">
                    <i class="icofont-location-arrow"></i>
                    {{ $translation->address }}
                </div>
            @endif
        </div>

        <div class="location-map">
            <div id="map_content"></div>
        </div>
    </div>
@endif
<div class="d-flex align-items">
    <!--<div class="d-md-none d-sm-block mx-2">-->
    <!--    <a target="_blank"-->
    <!--        href="{{ 'https://wa.me/971502205523?text=' . $row->getDetailUrl() . ' , ' . __('Hi I am looking for this farm,') . $translation->title . ',' }}"><img-->
    <!--            src="{{ asset('image/whatsapp.png') }}" style="width: 60px" class="" alt="whatsapp"></a>-->
    <!--</div>-->
    <!--<div class="d-md-none d-sm-block">-->
    <!--    <a href="#book-now" >-->
    <!--        <button type="button" class="btn btn-circle btn-xl font-weight-bold">{{ __('Book') }}</button>-->
    <!--    </a>-->
    <!--</div>-->
</div>
<div class="bravo-hr"></div>
