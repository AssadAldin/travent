<?php if(auth()->guard()->check()): ?>

    <?php
    $dataUser = Auth::user();
    $menus = [
        'dashboard' => [
            'url' => route('vendor.dashboard'),
            'title' => __('Dashboard'),
            'icon' => 'fa fa-home',
            'permission' => 'dashboard_vendor_access',
            'position' => 10,
        ],
        'booking-history' => [
            'url' => route('user.booking_history'),
            'title' => __('Booking History'),
            'icon' => 'fa fa-clock-o',
            'position' => 20,
        ],
        'wishlist' => [
            'url' => route('user.wishList.index'),
            'title' => __('Wishlist'),
            'icon' => 'fa fa-heart-o',
            'position' => 21,
        ],
        'profile' => [
            'url' => route('user.profile.index'),
            'title' => __('My Profile'),
            'icon' => 'fa fa-cogs',
            'position' => 22,
        ],
        'password' => [
            'url' => route('user.change_password'),
            'title' => __('Change password'),
            'icon' => 'fa fa-lock',
            'position' => 100,
        ],
        'admin' => [
            'url' => route('admin.index'),
            'title' => __('Admin Dashboard'),
            'icon' => 'icon ion-ios-ribbon',
            'permission' => 'dashboard_access',
            'position' => 110,
        ],
    ];

    // Modules
    $custom_modules = \Modules\ServiceProvider::getModules();
    if (!empty($custom_modules)) {
        foreach ($custom_modules as $module) {
            $moduleClass = '\\Modules\\' . ucfirst($module) . '\\ModuleProvider';
            if (class_exists($moduleClass)) {
                $menuConfig = call_user_func([$moduleClass, 'getUserMenu']);
                if (!empty($menuConfig)) {
                    $menus = array_merge($menus, $menuConfig);
                }
                $menuSubMenu = call_user_func([$moduleClass, 'getUserSubMenu']);
                if (!empty($menuSubMenu)) {
                    foreach ($menuSubMenu as $k => $submenu) {
                        $submenu['id'] = $submenu['id'] ?? '_' . $k;
                        if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                            $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                            $menus[$submenu['parent']]['children'] = array_values(
                                \Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                                    return $value['position'] ?? 100;
                                }),
                            );
                        }
                    }
                }
            }
        }
    }

    // Plugins Menu
    $plugins_modules = \Plugins\ServiceProvider::getModules();
    if (!empty($plugins_modules)) {
        foreach ($plugins_modules as $module) {
            $moduleClass = '\\Plugins\\' . ucfirst($module) . '\\ModuleProvider';
            if (class_exists($moduleClass)) {
                $menuConfig = call_user_func([$moduleClass, 'getUserMenu']);
                if (!empty($menuConfig)) {
                    $menus = array_merge($menus, $menuConfig);
                }
                $menuSubMenu = call_user_func([$moduleClass, 'getUserSubMenu']);
                if (!empty($menuSubMenu)) {
                    foreach ($menuSubMenu as $k => $submenu) {
                        $submenu['id'] = $submenu['id'] ?? '_' . $k;
                        if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                            $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                            $menus[$submenu['parent']]['children'] = array_values(
                                \Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                                    return $value['position'] ?? 100;
                                }),
                            );
                        }
                    }
                }
            }
        }
    }

    // Custom Menu
    $custom_modules = \Custom\ServiceProvider::getModules();
    if (!empty($custom_modules)) {
        foreach ($custom_modules as $module) {
            $moduleClass = '\\Custom\\' . ucfirst($module) . '\\ModuleProvider';
            if (class_exists($moduleClass)) {
                $menuConfig = call_user_func([$moduleClass, 'getUserMenu']);
                if (!empty($menuConfig)) {
                    $menus = array_merge($menus, $menuConfig);
                }
                $menuSubMenu = call_user_func([$moduleClass, 'getUserSubMenu']);
                if (!empty($menuSubMenu)) {
                    foreach ($menuSubMenu as $k => $submenu) {
                        $submenu['id'] = $submenu['id'] ?? '_' . $k;
                        if (!empty($submenu['parent']) and isset($menus[$submenu['parent']])) {
                            $menus[$submenu['parent']]['children'][$submenu['id']] = $submenu;
                            $menus[$submenu['parent']]['children'] = array_values(
                                \Illuminate\Support\Arr::sort($menus[$submenu['parent']]['children'], function ($value) {
                                    return $value['position'] ?? 100;
                                }),
                            );
                        }
                    }
                }
            }
        }
    }

    $currentUrl = url(Illuminate\Support\Facades\Route::current()->uri());
    if (!empty($menus)) {
        $menus = array_values(
            \Illuminate\Support\Arr::sort($menus, function ($value) {
                return $value['position'] ?? 100;
            }),
        );
    }
    foreach ($menus as $k => $menuItem) {
        if (!empty($menuItem['permission']) and !Auth::user()->hasPermission($menuItem['permission'])) {
            unset($menus[$k]);
            continue;
        }
        $menus[$k]['class'] = $currentUrl == url($menuItem['url']) ? 'active' : '';
        if (!empty($menuItem['children'])) {
            $menus[$k]['class'] .= ' has-children';
            foreach ($menuItem['children'] as $k2 => $menuItem2) {
                if (!empty($menuItem2['permission']) and !Auth::user()->hasPermission($menuItem2['permission'])) {
                    unset($menus[$k]['children'][$k2]);
                    continue;
                }
                $menus[$k]['children'][$k2]['class'] = $currentUrl == url($menuItem2['url']) ? 'active active_child' : '';
            }
        }
    }
    ?>
<?php endif; ?>
<div class="bravo_header d-flex d-none d-sm-block d-md-none d-lg-none" style="height: 80px!important">
    <div class="<?php echo e($container_class ?? 'container'); ?>">
        <div class="content d-flex d-none d-sm-block d-md-none d-lg-none">
            
            <div class="header-left col-md-12" style="width:80%;">
                <?php if(!Request::is('/') && !Request::is('ar')): ?>
                    <div class="d-flex d-none d-sm-block d-md-none d-lg-none">
                        <button type="button" onclick="history.back()" class="btn rounded-circle text-center p-2"
                            style="background-color: #e2e2e2">
                            <?php if(setting_item_with_lang('enable_rtl')): ?>
                                <img src="<?php echo e(asset('image/greaterThan.png')); ?>" style="width: 28px" class=""
                                    alt="back">
                            <?php else: ?>
                                <img src="<?php echo e(asset('image/greaterThanLeft.png')); ?>" style="width: 28px" class=""
                                    alt="back">
                            <?php endif; ?>
                        </button>
                    </div>
                <?php else: ?>
                    
                    <a href="<?php echo e(url(app_get_locale(false, '/'))); ?>" class="bravo-logo">
                        <?php
                            $logo_id = setting_item('logo_id');
                            if (!empty($row->custom_logo)) {
                                $logo_id = $row->custom_logo;
                            }
                        ?>
                        <img src="<?php echo e(asset('image/nLogo.png')); ?>" style="width: 170px" alt="Travent Logo">
                    </a>
                    
                <?php endif; ?>
                <?php
                    $languages = \Modules\Language\Models\Language::getActive();
                    $locale = session('website_locale', app()->getLocale());
                ?>
                <div class="bravo-menu">
                    <?php generate_menu('primary'); ?>
                </div>
            </div>
            <div class="header-right">
                <?php if(!empty($header_right_menu)): ?>
                    <ul class="topbar-items">
                        <?php echo $__env->make('Core::frontend.currency-switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php echo $__env->make('Language::frontend.switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
                        <?php if(!Auth::check()): ?>
                            <li class="login-item">
                                <a href="#login" data-toggle="modal" data-target="#login"
                                    class="login"><?php echo e(__('Login')); ?></a>
                            </li>
                            <?php if(is_enable_registration()): ?>
                                <li class="signup-item">
                                    <a href="#register" data-toggle="modal" data-target="#register"
                                        class="signup"><?php echo e(__('Sign Up')); ?></a>
                                </li>
                            <?php endif; ?>
                        <?php else: ?>
                            <li class="login-item dropdown">
                                <a href="#" data-toggle="dropdown" class="is_login">
                                    <?php if($avatar_url = Auth::user()->getAvatarUrl()): ?>
                                        <img class="avatar" src="<?php echo e($avatar_url); ?>"
                                            alt="<?php echo e(Auth::user()->getDisplayName()); ?>">
                                    <?php else: ?>
                                        <span
                                            class="avatar-text"><?php echo e(ucfirst(Auth::user()->getDisplayName()[0])); ?></span>
                                    <?php endif; ?>
                                    <?php echo e(__('Hi, :Name', ['name' => Auth::user()->getDisplayName()])); ?>

                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu text-left">

                                    <?php if(Auth::user()->hasPermission('dashboard_vendor_access')): ?>
                                        <li><a href="<?php echo e(route('vendor.dashboard')); ?>"><i
                                                    class="icon ion-md-analytics"></i> <?php echo e(__('Vendor Dashboard')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="<?php if(Auth::user()->hasPermission('dashboard_vendor_access')): ?> menu-hr <?php endif; ?>">
                                        <a href="<?php echo e(route('user.profile.index')); ?>"><i
                                                class="icon ion-md-construct"></i> <?php echo e(__('My profile')); ?></a>
                                    </li>
                                    <?php if(setting_item('inbox_enable')): ?>
                                        <li class="menu-hr"><a href="<?php echo e(route('user.chat')); ?>"><i
                                                    class="fa fa-comments"></i> <?php echo e(__('Messages')); ?></a></li>
                                    <?php endif; ?>
                                    <li class="menu-hr"><a href="<?php echo e(route('user.booking_history')); ?>"><i
                                                class="fa fa-clock-o"></i> <?php echo e(__('Booking History')); ?></a></li>
                                    <li class="menu-hr"><a href="<?php echo e(route('user.change_password')); ?>"><i
                                                class="fa fa-lock"></i> <?php echo e(__('Change password')); ?></a></li>
                                    <?php if(Auth::user()->hasPermission('dashboard_access')): ?>
                                        <li class="menu-hr"><a href="<?php echo e(route('admin.index')); ?>"><i
                                                    class="icon ion-ios-ribbon"></i> <?php echo e(__('Admin Dashboard')); ?></a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="menu-hr">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="fa fa-sign-out"></i> <?php echo e(__('Logout')); ?></a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="<?php echo e(route('logout')); ?>" method="POST"
                                    style="display: none;">
                                    <?php echo e(csrf_field()); ?>

                                </form>
                            </li>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
                
                <div class="d-flex d-md-none align-items-center">
                    <div class="">
                        <?php if(!empty($languages) && setting_item('site_enable_multi_lang')): ?>
                            <?php $__currentLoopData = $languages; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $language): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <?php if($locale != $language->locale): ?>
                                    <div class="dropdown">
                                        <div class="">
                                            <a href="<?php echo e(get_lang_switcher_url($language->locale)); ?>"
                                                class="is_login text-decoration-none text-dark text-uppercase d-flex">
                                                <?php echo e($language->locale); ?>

                                                <?php if($language->flag): ?>
                                                    <span class="m-1 flag-icon flag-icon-<?php echo e($language->flag); ?>"></span>
                                                <?php endif; ?>
                                            </a>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="">
                        <button class="bravo-more-menu">
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="bravo-menu-mobile" style="display:none; z-index: 101;">
        <div class="user-profile">
            <div class="b-close"><i class="icofont-scroll-left"></i></div>
            <div class="avatar"></div>
            <ul>
                <?php if(!Auth::check()): ?>
                    <li>
                        <a href="#login" data-toggle="modal" data-target="#login"
                            class="login"><?php echo e(__('Login')); ?></a>
                    </li>
                    <?php if(is_enable_registration()): ?>
                        <li>
                            <a href="#register" data-toggle="modal" data-target="#register"
                                class="signup"><?php echo e(__('Sign Up')); ?></a>
                        </li>
                    <?php endif; ?>
                <?php else: ?>
                    <li>
                        <a href="<?php echo e(route('user.profile.index')); ?>">
                            <i class="icofont-user-suited"></i>
                            <?php echo e(__('Hi, :Name', ['name' => Auth::user()->getDisplayName()])); ?>

                        </a>
                    </li>
                    <?php if(Auth::user()->hasPermission('dashboard_vendor_access')): ?>
                        <li><a href="<?php echo e(route('vendor.dashboard')); ?>"><i class="icon ion-md-analytics"></i>
                                <?php echo e(__('Vendor Dashboard')); ?></a></li>
                    <?php endif; ?>
                    <?php if(Auth::user()->hasPermission('dashboard_access')): ?>
                        <li>
                            <a href="<?php echo e(route('admin.index')); ?>"><i class="icon ion-ios-ribbon"></i>
                                <?php echo e(__('Admin Dashboard')); ?></a>
                        </li>
                    <?php endif; ?>

                    <li>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fa fa-sign-out"></i> <?php echo e(__('Logout')); ?>

                        </a>
                        <form id="logout-form-mobile" action="<?php echo e(route('logout')); ?>" method="POST"
                            style="display: none;">
                            <?php echo e(csrf_field()); ?>

                        </form>
                    </li>
                <?php endif; ?>
                
                <li>
                    <a href="/page/become-a-Host"><i class="icon icofont-brand-slideshare"></i>
                        <?php echo e(__('Become a vendor')); ?></a>
                </li>
                <li>
                    <a href="<?php echo e(route('vendor.appointment')); ?>">
                        <i class="fa fa-history"></i> <?php echo e(__('Appointment')); ?>

                    </a>
                </li>
            </ul>
            <?php if(auth()->guard()->check()): ?>
                <ul class="main-menu">
                    <?php $__currentLoopData = $menus; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <li class="<?php echo e($menuItem['class']); ?>" position="<?php echo e($menuItem['position'] ?? ''); ?>">
                            <a href="<?php echo e(url($menuItem['url'])); ?>">
                                <?php if(!empty($menuItem['icon'])): ?>
                                    <span class="icon text-center"><i class="<?php echo e($menuItem['icon']); ?>"></i></span>
                                <?php endif; ?>
                                <?php echo clean($menuItem['title']); ?>

                            </a>
                            <?php if(!empty($menuItem['children'])): ?>
                                <i class="caret"></i>
                            <?php endif; ?>
                            <?php if(!empty($menuItem['children'])): ?>
                                <ul class="children">
                                    <?php $__currentLoopData = $menuItem['children']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $menuItem2): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="<?php echo e($menuItem2['class']); ?>"><a href="<?php echo e(url($menuItem2['url'])); ?>">
                                                <?php if(!empty($menuItem2['icon'])): ?>
                                                    <i class="<?php echo e($menuItem2['icon']); ?>"></i>
                                                <?php endif; ?>
                                                <?php echo clean($menuItem2['title']); ?>

                                            </a></li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </ul>
            <?php endif; ?>
            <ul class="multi-lang">
                <?php echo $__env->make('Core::frontend.currency-switcher', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
            </ul>

            

            
        </div>
        <div class="g-menu">
            <?php generate_menu('primary'); ?>
        </div>
    </div>
</div>
<?php /**PATH /home/leo/Projects/Laravel/travent1/modules/Layout/parts/header.blade.php ENDPATH**/ ?>