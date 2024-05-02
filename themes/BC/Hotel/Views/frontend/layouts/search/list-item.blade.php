<div class="row">
    <div class="col-lg-3 col-md-12">
        @include('Hotel::frontend.layouts.search.filter-search')
    </div>
    <div class="col-lg-9 col-md-12">
        <div class="bravo-list-item">
            <div class="topbar-search">
                {{-- <h2 class="text">
                    @if ($rows->total() > 1)
                        {{ __(':count hotels found', ['count' => $rows->total()]) }}
                    @else
                        {{ __(':count hotel found', ['count' => $rows->total()]) }}
                    @endif
                </h2> --}}
                <div class="control">
                    @include('Hotel::frontend.layouts.search.orderby')
                </div>
            </div>
            {{-- <button class="btn btn-primary" onclick="myFunction()">All Available</button> --}}
            <div class="row m-2 h5">
                {{ count($aval) }} {{ __('All Available') }} {{ Request::query('start') }} -
                {{ Request::query('end') }}
            </div>
            <div class="list-item">
                <div class="row">
                    {{-- <div class="row"> --}}
                    @if (!empty($aval))
                        @foreach ($aval as $row)
                            @php
                                $translation = $row->translate();
                            @endphp
                            <div class="col-lg-4 col-md-12">
                                <div class="item-loop {{ $wrap_class ?? '' }}" style="border-radius: 25px;">
                                    @if ($row->is_featured == '1')
                                        <div class="featured mt-5">
                                            {{ __('Featured') }}
                                        </div>
                                    @endif
                                    <div class="thumb-image">
                                        <a @if (!empty($blank)) target="_blank" @endif
                                            href="{{ $row->getDetailUrl(). '?startCheck=' . implode('-', array_reverse(explode('/', Request::query('start')))) . '&' . 'endCheck=' . implode('-', array_reverse(explode('/', Request::query('end')))) }}">
                                            @if ($row->image_url)
                                                @if (!empty($disable_lazyload))
                                                    <img src="{{ $row->image_url }}" class="img-responsive"
                                                        alt="">
                                                @else
                                                    {!! get_image_tag($row->image_id, 'medium', ['class' => 'img-responsive', 'alt' => $translation->title]) !!}
                                                @endif
                                            @endif
                                        </a>
                                        @if ($row->star_rate)
                                            <div class="star-rate">
                                                <div class="list-star">
                                                    <ul class="booking-item-rating-stars">
                                                        @for ($star = 1; $star <= $row->star_rate; $star++)
                                                            <li><i class="fa fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="service-wishlist {{ $row->isWishList() }}"
                                            data-id="{{ $row->id }}" data-type="{{ $row->type }}">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                    </div>
                                    <div class="item-title">

                                        <a @if (!empty($blank)) target="_blank" @endif
                                            href="{{ $row->getDetailUrl(). '?startCheck=' . implode('-', array_reverse(explode('/', Request::query('start')))) . '&' . 'endCheck=' . implode('-', array_reverse(explode('/', Request::query('end')))) }}">
                                            {{--            {{dd($row->getDetailUrl())}} --}}
                                            @if ($row->is_instant)
                                                <i class="fa fa-bolt d-none"></i>
                                            @endif
                                            {{ $translation->title }}
                                        </a>
                                        {{-- Discount Percent --}}
                                        @if ($row->discount_percent)
                                            <div class="sale_info">50%{{ $row->discount_percent }}</div>
                                        @endif
                                    </div>
                                    <div class="location">
                                        @if (!empty($row->location->name))
                                            @php $location =  $row->location->translate() @endphp
                                            {{ $location->name ?? '' }}
                                        @endif
                                    </div>
                                    @if (setting_item('hotel_enable_review'))
                                        <?php
                                        $reviewData = $row->getScoreReview();
                                        $score_total = $reviewData['score_total'];
                                        ?>
                                        <div class="service-review d-none">
                                            <!--<span class="rate">-->
                                            <!--    @if ($reviewData['total_review'] > 0)-->
                                            <!--        {{ $score_total }}/5-->
                                            <!--    @endif <span-->
                                            <!--        class="rate-text">{{ $reviewData['review_text'] }}</span>-->
                                            <!--</span>-->
                                            <span class="review">
                                                @if ($reviewData['total_review'] > 1)
                                                    {{ __(':number Reviews', ['number' => $reviewData['total_review']]) }}
                                                @else
                                                    {{ __(':number Review', ['number' => $reviewData['total_review']]) }}
                                                @endif
                                            </span>
                                        </div>
                                    @endif
                                    <div class="info mt-2" style="height: 4rem;">
                                        @if ($row->lessPrF)
                                        @if ($row->lessPrF != 0)
                                            <div class="text-secondary d-flex align-items-center">{{__("Full Day")}} : <div
                                                    class="font-weight-bold text-dark px-1">
                                                    AED{{ number_format($row->lessPrF) }}</div><small>/{{__("night")}}</small>
                                            </div>
                                        @endif
                                        @endif
                                        @if ($row->between == 1)
                                            @if ($row->lessPrD)
                                            @if ($row->lessPrD != 0)
                                                <div class="text-secondary d-flex align-items-center">{{__("Day Stay")}} :
                                                    <div class="font-weight-bold text-dark px-1">
                                                        AED{{ number_format($row->lessPrD) }}</div><small>/{{__("day")}}</small>
                                                </div>
                                            @endif
                                            @endif
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-lg-12">
                            {{ __('Hotel not found') }}
                        </div>
                    @endif
                    {{-- </div> --}}
                </div>
                <hr class="mb-5">
                <div class="row m-2 h5 pb-3">
                    {{ count($notAval) }}{{ __('Not available in between ') }}{{ Request::query('start') }} -
                    {{ Request::query('end') }}
                </div>
                <div class="row">
                    @if (!empty($notAval))
                        @foreach ($notAval as $row)
                            @php
                                $translation = $row->translate();
                            @endphp
                            <div class="col-lg-4 col-md-12">
                                <div class="item-loop {{ $wrap_class ?? '' }}" style="border-radius: 25px;">
                                    @if ($row->is_featured == '1')
                                        <div class="featured mt-5">
                                            {{ __('Featured') }}
                                        </div>
                                    @endif
                                    <div class="featured">
                                        {{ __('Booked') }}
                                    </div>
                                    <div class="thumb-image ">
                                        <a @if (!empty($blank)) target="_blank" @endif
                                            href="{{ $row->getDetailUrl() }}">
                                            @if ($row->image_url)
                                                @if (!empty($disable_lazyload))
                                                    <img src="{{ $row->image_url }}" class="img-responsive"
                                                        alt="">
                                                @else
                                                    {!! get_image_tag($row->image_id, 'medium', ['class' => 'img-responsive', 'alt' => $translation->title]) !!}
                                                @endif
                                            @endif
                                        </a>
                                        @if ($row->star_rate)
                                            <div class="star-rate">
                                                <div class="list-star">
                                                    <ul class="booking-item-rating-stars">
                                                        @for ($star = 1; $star <= $row->star_rate; $star++)
                                                            <li><i class="fa fa-star"></i></li>
                                                        @endfor
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="service-wishlist {{ $row->isWishList() }}"
                                            data-id="{{ $row->id }}" data-type="{{ $row->type }}">
                                            <i class="fa fa-heart"></i>
                                        </div>
                                    </div>
                                    <div class="item-title">

                                        <a @if (!empty($blank)) target="_blank" @endif
                                            href="{{ $row->getDetailUrl() }}">
                                            {{--            {{dd($row->getDetailUrl())}} --}}
                                            @if ($row->is_instant)
                                                <i class="fa fa-bolt d-none"></i>
                                            @endif
                                            {{ $translation->title }}
                                        </a>
                                        @if ($row->discount_percent)
                                            <div class="sale_info">{{ $row->discount_percent }}</div>
                                        @endif
                                    </div>
                                    <div class="location">
                                        @if (!empty($row->location->name))
                                            @php $location =  $row->location->translate() @endphp
                                            {{ $location->name ?? '' }}
                                        @endif
                                    </div>
                                    @if (setting_item('hotel_enable_review'))
                                        <?php
                                        $reviewData = $row->getScoreReview();
                                        $score_total = $reviewData['score_total'];
                                        ?>
                                        <!--<div class="service-review">-->
                                        <!--    <span class="rate">-->
                                        <!--        @if ($reviewData['total_review'] > 0)-->
                                        <!--            {{ $score_total }}/5-->
                                        <!--        @endif <span-->
                                        <!--            class="rate-text">{{ $reviewData['review_text'] }}</span>-->
                                        <!--    </span>-->
                                        <!--    <span class="review">-->
                                        <!--        @if ($reviewData['total_review'] > 1)-->
                                        <!--            {{ __(':number Reviews', ['number' => $reviewData['total_review']]) }}-->
                                        <!--        @else-->
                                        <!--            {{ __(':number Review', ['number' => $reviewData['total_review']]) }}-->
                                        <!--        @endif-->
                                        <!--    </span>-->
                                        <!--</div>-->
                                    @endif
                                    <div class="info">
                                        <div class="g-price">
                                            <div class="prefix">
                                                <span class="fr_text">{{ __('from') }}</span>
                                            </div>
                                            <div class="price">
                                                <span class="text-price">{{ $row->display_price }} <span
                                                        class="unit">{{ __('/night') }}</span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>
            {{-- <div class="bravo-pagination">
                {{ $rows->appends(request()->query())->links() }}
                @if ($rows->total() > 0)
                    <span
                        class="count-string">{{ __('Showing :from - :to of :total Hotels', ['from' => $rows->firstItem(), 'to' => $rows->lastItem(), 'total' => $rows->total()]) }}</span>
                @endif
            </div> --}}
        </div>
    </div>
</div>
