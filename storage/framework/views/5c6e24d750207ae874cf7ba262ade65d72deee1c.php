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
<?php ($location_search_style = setting_item('hotel_location_search_style')); ?>


<div class="form-group w-100 h-100" style="background-color: #ffffff;  border-radius: 7px;">
    <div class="form-content d-flex align-items-center" style="">
        
        <i class="fa fa-map-marker mx-2 fa-2x"></i>
        <?php if($location_search_style == 'autocompletePlace'): ?>
            <div class="g-map-place">
                <input type="text" style="font-size:12px; color:#000000!important;" name="map_place"
                    placeholder="<?php echo e(__('Where are you going?')); ?>" value="<?php echo e(request()->input('map_place')); ?>"
                    class="form-control border-0">
                <div class="map d-none" id="map-<?php echo e(\Illuminate\Support\Str::random(10)); ?>"></div>
                <input type="hidden" name="map_lat" value="<?php echo e(request()->input('map_lat')); ?>">
                <input type="hidden" name="map_lgn" value="<?php echo e(request()->input('map_lgn')); ?>">
            </div>
        <?php else: ?>
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
                    <?php echo e((empty(setting_item('hotel_location_search_style')) or setting_item('hotel_location_search_style') == 'normal') ? 'readonly' : ''); ?>

                    placeholder="<?php echo e(__('Where are you going?')); ?>" value="<?php echo e($location_name); ?>"
                    data-onLoad="<?php echo e(__('Loading...')); ?>" data-default="<?php echo e(json_encode($list_json)); ?>"
                    style="font-size:12px; color:#000033;">
                <input type="hidden" class="child_id" name="location_id" value="<?php echo e(Request::query('location_id')); ?>">
            </div>
        <?php endif; ?>
    </div>
</div>

<?php /**PATH /home/leo/Projects/Laravel/travent1/themes/BC/Hotel/Views/frontend/layouts/search/fields/location.blade.php ENDPATH**/ ?>