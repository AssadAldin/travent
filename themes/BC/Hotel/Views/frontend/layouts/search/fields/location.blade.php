<style>
    ::placeholder {
        color: #000033 !important;
        /* opacity: 1;  Firefox */
    }

    ::-ms-input-placeholder {
        /* Edge 12-18 */
        color: #000033 !important;
    }

    .form-content{
        padding: 2%!important;
    }
</style>
@php($location_search_style = setting_item('hotel_location_search_style'))


<div class="form-group w-100 h-100" style="background-color: #ffffff;  border-radius: 7px;">
    <div class="form-content d-flex align-items-center" style="">
        {{-- <label style="color:#1A2B50;font-size:16px;font-weight: 500;"> {{ $field['title'] }} </label> --}}
        <i class="fa fa-map-marker mx-2 fa-2x"></i>
        @if ($location_search_style == 'autocompletePlace')
            <div class="g-map-place">
                <input type="text" style="font-size:12px; color:#000000!important;" name="map_place"
                    placeholder="{{ __('Where are you going?') }}" value="{{ request()->input('map_place') }}"
                    class="form-control border-0">
                <div class="map d-none" id="map-{{ \Illuminate\Support\Str::random(10) }}"></div>
                <input type="hidden" name="map_lat" value="{{ request()->input('map_lat') }}">
                <input type="hidden" name="map_lgn" value="{{ request()->input('map_lgn') }}">
            </div>
        @else
            <?php
            $location_name = '';
            $list_json = [];
            $traverse = function ($locations, $prefix = '') use (&$traverse, &$list_json, &$location_name) {
                foreach ($locations as $location) {
                    $translate = $location->translate();
                    if (Request::query('location_id') == $location->id) {
                        $location_name = $translate->name;
                    }
                    $list_json[] = [
                        'id' => $location->id,
                        'title' => $prefix . ' ' . $translate->name,
                    ];
                    $traverse($location->children, $prefix . '-');
                }
            };
            $traverse($list_location);
            ?>
            <div class="smart-search w-100 h-100">
                <input type="text" class="smart-search-location parent_text form-control w-100 h-100"
                    {{ (empty(setting_item('hotel_location_search_style')) or setting_item('hotel_location_search_style') == 'normal') ? 'readonly' : '' }}
                    placeholder="{{ __('Where are you going?') }}" value="{{ $location_name }}"
                    data-onLoad="{{ __('Loading...') }}" data-default="{{ json_encode($list_json) }}"
                    style="font-size:12px; color:#000033;">
                <input type="hidden" class="child_id" name="location_id" value="{{ Request::query('location_id') }}">
            </div>
        @endif
    </div>
</div>
{{-- Edit the search bar and it include three other pages guest - location - date and the main layout form search --}}
