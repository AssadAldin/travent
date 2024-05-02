<div class="form-group w-full" style="background-color: #ffffff; border-radius: 7px;">

    <div class="form-content" style="">
        <div class="form-date-search-hotel">
            <div class="date-wrapper">
                <div class="check-in-wrapper d-flex align-items-center">
                    <i class="fa fa-calendar fa-2x mx-2"></i>
                    <div style="font-size:13px;color:#1A2B50;" class="mx-2"><?php echo e(__('Date')); ?></div>
                    <div style="font-size:12px;color:#1A2B50;" class="render check-in-render"><?php echo e(Request::query('start',display_date(strtotime("today")))); ?></div>
                    <span style="font-size:12px;color:#1A2B50;" class="mx-2"> _ </span>
                    <div style="font-size:12px;color:#1A2B50;" class="render check-out-render"><?php echo e(Request::query('end',display_date(strtotime("+1 day")))); ?></div>
                    
                    <!--<div class="render check-in-render" style="font-size:12px;color:#1A2B50;">-->
                    <!--    <?php if(!Request::query('start',display_date(strtotime("today")))): ?>-->
                    <!--    <?php echo e(Request::query('start',display_date(strtotime("today")))); ?>-->
                    <!--    <?php else: ?>-->
                    <!--    <?php echo e(__('Check In')); ?>-->
                    <!--    <?php endif; ?>-->
                    <!--</div>-->
                    <!--<span style="font-size:12px;color:#1A2B50;"> - </span>-->
                    <!--<div class="render check-out-render" style="font-size:12px;color:#1A2B50;">-->
                    <!--    <?php if(!Request::query('end',display_date(strtotime("+1 day")))): ?>-->
                    <!--    <?php echo e(Request::query('end',display_date(strtotime("+1 day")))); ?>-->
                    <!--    <?php else: ?>-->
                    <!--    <?php echo e(__('Check Out')); ?>-->
                    <!--    <?php endif; ?>-->
                    <!--</div>-->
                </div>
            </div>
            <input type="hidden" class="check-in-input" value="<?php echo e(Request::query('start',display_date(strtotime("today")))); ?>" name="start">
            <input type="hidden" class="check-out-input" value="<?php echo e(Request::query('end',display_date(strtotime("+1 day")))); ?>" name="end">
            <input type="text" class="check-in-out" value="<?php echo e(Request::query('date',date("Y-m-d")." - ".date("Y-m-d",strtotime("+1 day")))); ?>">
        </div>
    </div>
</div>

<?php /**PATH /home/leo/Projects/Laravel/travent1/themes/BC/Hotel/Views/frontend/layouts/search/fields/date.blade.php ENDPATH**/ ?>