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
                        href="{{ $row->getDetailUrl() . '?startCheck=' . implode('-', array_reverse(explode('/', Request::query('start')))) . '&' . 'endCheck=' . implode('-', array_reverse(explode('/', Request::query('end')))) }}">
                        @if ($row->image_url)
                        <img src="{{ $row->image_url }}" class="img-responsive" alt="">
                            {{-- @if (!empty($disable_lazyload))
                            @else
                                {!! get_image_tag($row->image_id, 'medium', ['class' => 'img-responsive', 'alt' => $translation->title]) !!}
                            @endif --}}
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
                    <div class="service-wishlist {{ $row->isWishList() }}" data-id="{{ $row->id }}"
                        data-type="{{ $row->type }}">
                        <i class="fa fa-heart"></i>
                    </div>
                </div>
                <a @if (!empty($blank)) target="_blank" @endif
                    href="{{ $row->getDetailUrl() . '?startCheck=' . implode('-', array_reverse(explode('/', Request::query('start')))) . '&' . 'endCheck=' . implode('-', array_reverse(explode('/', Request::query('end')))) }}">
                    <div class="item-title text-dark">
                        {{--            {{dd($row->getDetailUrl())}} --}}
                        @if ($row->is_instant)
                            <i class="fa fa-bolt d-none"></i>
                        @endif
                        {{ $translation->title }}

                        {{-- Discount Percent --}}
                        @if ($row->discount_percent1)
                            <div class="sale_info font-weight-bold text-center"
                                style="font-size: x-small;height: 70px; width: 70px; background-color:red;">
                                {{ $row->discount_percent1 }}
                            </div>
                        @endif
                    </div>
                    <div class="location">
                        @if (!empty($row->location->name))
                            @php $location =  $row->location->translate() @endphp
                            {{ $location->name ?? '' }}
                        @endif
                    </div>
                    @if ($row->discount_code)
                        <div class="info">
                            <div class="g-price">
                                <div class="prefix">
                                    <span class="fr_text">{{ __('Discount Code') }}: </span>
                                </div>
                                <div class="price">
                                    <span class="text-price" style="color: red;">
                                        {{ $row->discount_code }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                    @if (setting_item('hotel_enable_review'))
                        <?php
                        $reviewData = $row->getScoreReview();
                        $score_total = $reviewData['score_total'];
                        ?>
                        <div class="service-review d-none">
                            <!--<span class="rate">-->
                            <!--    @if ($reviewData['total_review'] > 0)
-->
                            <!--        {{ $score_total }}/5-->
                            <!--
@endif <span-->
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
                    <div class="info">
                        <div class="g-price">
                            <div class="prefix">
                                <span class="fr_text">{{ __('from11') }}</span>
                            </div>
                            <div class="price">
                                <span class="text-price">{{ $row->display_price }} <span
                                        class="unit">{{ __('/night11') }}</span></span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    @endforeach
@endif
