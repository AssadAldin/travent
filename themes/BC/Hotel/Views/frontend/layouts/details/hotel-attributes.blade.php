@php
    $terms_ids = $row->terms->pluck('term_id');
    $attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
@endphp
@php
use Modules\Hotel\Models\Hotel;
use App\Models\HotelCancelationPolicy;
$terms_ids = $row->terms->pluck('term_id');
$attributes = \Modules\Core\Models\Terms::getTermsById($terms_ids);
$hotel_p = Hotel::find($translation->origin_id);
$policy_h = HotelCancelationPolicy::find($hotel_p->cancelation_id);
@endphp
@if(!empty($terms_ids) and !empty($attributes))
    @foreach($attributes as $key => $attribute )
        @php $translate_attribute = $attribute['parent']->translate() @endphp
        @if(empty($attribute['parent']['hide_in_single']))
            <div class="g-attributes {{$attribute['parent']->slug}} attr-{{$attribute['parent']->id}}">
                <h3>{{ $translate_attribute->name }}</h3>
                @php $terms = $attribute['child'] @endphp
                <div class="list-attributes row">
                    @foreach($terms as $term )
                        @php $translate_term = $term->translate() @endphp
                        {{-- <div class="item {{$term->slug}} term-{{$term->id}}">
                            @if(!empty($term->image_id))
                                @php $image_url = get_file_url($term->image_id, 'full'); @endphp
                                <img src="{{$image_url}}" class="img-responsive" alt="{{$translate_term->name}}">
                            @else
                                <i class="{{ $term->icon ?? "icofont-check-circled icon-default" }}"></i>
                            @endif
                            {{$translate_term->name}}
                        </div> --}}
                        <div class="{{$term->slug}} term-{{$term->id}} col-4 text-center mt-3">
                            @if(!empty($term->image_id))
                                @php $image_url = get_file_url($term->image_id, 'full'); @endphp
                                <img src="{{$image_url}}" class="img-responsive w-50" alt="{{$translate_term->name}}"><br>
                            @else
                                <i class="{{ $term->icon ?? "icofont-check-circled icon-default" }}"></i>
                            @endif
                            {{$translate_term->name}}
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    @endforeach
@endif

<div class="g-attributes" >
    <h3>{{__("Cancelation policy")}}</h3>
    <div class="list-attributes">
        <div class="item">
            <p class="text-justify">{{__($policy_h->item ?? "") }}.</p>
        </div>
    </div>
</div>
