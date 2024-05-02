

<?php
$languages = \Modules\Language\Models\Language::getActive();
$locale = session('website_locale',app()->getLocale());
?>
<?php if(!empty($languages) && setting_item('site_enable_multi_lang')): ?>
<li class="dropdown p-0">
    <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php if($locale != $language->locale): ?>
<li class="d-none d-md-inline p-0 mx-2">
    <a href="<?php echo e(get_lang_switcher_url($language->locale)); ?>" class="is_login">
        <?php if($language->flag): ?>
        <span class="m-1 flag-icon flag-icon-<?php echo e($language->flag); ?>"></span>
        <?php endif; ?>
        <?php echo e($language->name); ?>

    </a>
</li>
<?php endif; ?>
<?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</li>
<?php endif; ?>






<?php /**PATH /home/leo/Projects/Laravel/travent1/themes/BC/Language/Views/frontend/switcher.blade.php ENDPATH**/ ?>