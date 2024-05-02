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
<?php $main_color = setting_item('style_main_color','#5191fa');
$style_typo = json_decode(setting_item_with_lang('style_typo',false,"{}"),true);
?>
<form action="<?php echo e(route("hotel.search")); ?>" class="form bravo_form mt-0" style="background-color: <?php echo e($main_color); ?>;border-radius: 7px;" method="get">
    <div class="col-md-10" style="background-color: <?php echo e($main_color); ?>;  border-radius: 7px;">
        <div class="row">
            <?php $hotel_search_fields = setting_item_array('hotel_search_fields');
            $hotel_search_fields = array_values(\Illuminate\Support\Arr::sort($hotel_search_fields, function ($value) {
            return $value['position'] ?? 0;
            }));
            ?>
            <?php if(!empty($hotel_search_fields)): ?>
            <?php $__currentLoopData = $hotel_search_fields; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $field): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php $field['title'] = $field['title_'.app()->getLocale()] ?? $field['title'] ?? "" ?>
            <div class="col-md-<?php echo e($field['size'] ?? "6"); ?> p-1" style="">
                <?php switch($field['field']):
                case ('service_name'): ?>
                <?php echo $__env->make('Hotel::frontend.layouts.search.fields.service_name', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php break; ?>
                <?php case ('location'): ?>
                <?php echo $__env->make('Hotel::frontend.layouts.search.fields.location', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php break; ?>
                <?php case ('date'): ?>
                <?php echo $__env->make('Hotel::frontend.layouts.search.fields.date', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php break; ?>
                <?php case ('attr'): ?>
                <?php echo $__env->make('Hotel::frontend.layouts.search.fields.attr', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php break; ?>
                <?php case ('guests'): ?>
                <?php echo $__env->make('Hotel::frontend.layouts.search.fields.guests', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                <?php break; ?>
                <?php endswitch; ?>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php endif; ?>
        </div>
    </div>
    <div class="col-md-2 col-sm-12 p-1">
        <button class="btn btn-primary w-100 h-100" style="background-color:#264653;border-radius: 7px;font-size:12px;" type="submit">
            <?php echo e(__("Search")); ?>

        </button>
    </div>
</form>

<?php /**PATH /home/leo/Projects/Laravel/travent1/themes/BC/Hotel/Views/frontend/layouts/search/form-search.blade.php ENDPATH**/ ?>