<style>
    .g-form-control {
        background-color: #f6a459;
        border-radius: 20px;
        margin-top:0px;
    }
    .nav-tabs{
        display: none;
    }

</style>
@php $main_color = setting_item('style_main_color','#5191fa');
$style_typo = json_decode(setting_item_with_lang('style_typo',false,"{}"),true);
@endphp
<form action="{{ route("hotel.search") }}" class="form bravo_form mt-0" style="background-color: {{$main_color}};border-radius: 7px;" method="get">
    <div class="col-md-10" style="background-color: {{$main_color}};  border-radius: 7px;">
        <div class="row">
            @php $hotel_search_fields = setting_item_array('hotel_search_fields');
            $hotel_search_fields = array_values(\Illuminate\Support\Arr::sort($hotel_search_fields, function ($value) {
            return $value['position'] ?? 0;
            }));
            @endphp
            @if(!empty($hotel_search_fields))
            @foreach($hotel_search_fields as $field)
            @php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" @endphp
            <div class="col-md-{{ $field['size'] ?? "6" }} p-1" style="">
                @switch($field['field'])
                @case ('service_name')
                @include('Hotel::frontend.layouts.search.fields.service_name')
                @break
                @case ('location')
                @include('Hotel::frontend.layouts.search.fields.location')
                @break
                @case ('date')
                @include('Hotel::frontend.layouts.search.fields.date')
                @break
                @case ('attr')
                @include('Hotel::frontend.layouts.search.fields.attr')
                @break
                @case ('guests')
                @include('Hotel::frontend.layouts.search.fields.guests')
                @break
                @endswitch
            </div>
            @endforeach
            @endif
        </div>
    </div>
    <div class="col-md-2 col-sm-12 p-1">
        <button class="btn btn-primary w-100 h-100" style="background-color:#264653;border-radius: 7px;font-size:12px;" type="submit">
            {{__("Search")}}
        </button>
    </div>
</form>
{{-- Edit the search bar and it include three other pages guest - location - date and the main layout form search --}}
