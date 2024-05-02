<?php
    $translation = $row->translate();
?>
<div class="item-loop <?php echo e($wrap_class ?? ''); ?>" style="border-radius: 25px;">
    <div class="thumb-image ">
        <a <?php if(!empty($blank)): ?> target="_blank" <?php endif; ?> href="<?php echo e($row->getDetailUrl()); ?>">
            <?php if($row->image_url): ?>
                <?php if(!empty($disable_lazyload)): ?>
                    <img src="<?php echo e($row->image_url); ?>" class="img-responsive" alt="">
                <?php else: ?>
                    <?php echo get_image_tag($row->image_id, 'medium', ['class' => 'img-responsive', 'alt' => $translation->title]); ?>

                <?php endif; ?>
            <?php endif; ?>
        </a>
        <?php if($row->star_rate): ?>
            <div class="star-rate">
                <div class="list-star">
                    <ul class="booking-item-rating-stars">
                        <?php for($star = 1; $star <= $row->star_rate; $star++): ?>
                            <li><i class="fa fa-star"></i></li>
                        <?php endfor; ?>
                    </ul>
                </div>
            </div>
        <?php endif; ?>
        <div class="service-wishlist <?php echo e($row->isWishList()); ?>" data-id="<?php echo e($row->id); ?>"
            data-type="<?php echo e($row->type); ?>">
            <i class="fa fa-heart"></i>
        </div>
    </div>
    <a <?php if(!empty($blank)): ?> target="_blank" <?php endif; ?> href="<?php echo e($row->getDetailUrl()); ?>">
        <div class="item-title text-dark">

            
            <?php if($row->is_instant): ?>
                <i class="fa fa-bolt d-none"></i>
            <?php endif; ?>
            <?php echo e($translation->title); ?>

            <?php if($row->discount_percent1): ?>
                <div class="sale_info font-weight-bold text-center" style="font-size: x-small;height: 70px; width: 70px; background-color:red;"><?php echo e($row->discount_percent1); ?></div>
            <?php endif; ?>
        </div>
        <div class="location">
            <?php if(!empty($row->location->name)): ?>
                <?php $location =  $row->location->translate() ?>
                <?php echo e($location->name ?? ''); ?>

            <?php endif; ?>
        </div>
        <?php if($row->discount_code): ?>
            <div class="info">
                <div class="g-price">
                    <div class="prefix">
                        <span class="fr_text"><?php echo e(__('Discount Code')); ?>: </span>
                    </div>
                    <div class="price">
                        <span class="text-price" style="color: red;"> <?php echo e($row->discount_code); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        <div class="info">
            <div class="g-price">
                <div class="prefix">
                    <span class="fr_text"><?php echo e(__('from11')); ?></span>
                </div>
                <div class="price">
                    <span class="text-price"><?php echo e($row->display_price); ?> <span
                            class="unit"><?php echo e(__('/night11')); ?></span></span>
                </div>
            </div>
        </div>
    </a>
</div>
<?php /**PATH /home/leo/Projects/Laravel/travent1/themes/BC/Hotel/Views/frontend/layouts/search/loop-grid.blade.php ENDPATH**/ ?>