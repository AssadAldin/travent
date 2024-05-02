@auth

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
@endauth
<div class="bravo_header d-flex d-none d-sm-block d-md-none d-lg-none" style="height: 80px!important">
    <div class="{{ $container_class ?? 'container' }}">
        <div class="content d-flex d-none d-sm-block d-md-none d-lg-none">
            {{-- change the width and add col-md-12 to control the header width in mobile view --}}
            <div class="header-left col-md-12" style="width:80%;">
                @if (!Request::is('/') && !Request::is('ar'))
                    <div class="d-flex d-none d-sm-block d-md-none d-lg-none">
                        <button type="button" onclick="history.back()" class="btn rounded-circle text-center p-2"
                            style="background-color: #e2e2e2">
                            @if (setting_item_with_lang('enable_rtl'))
                                <img src="{{ asset('image/greaterThan.png') }}" style="width: 28px" class=""
                                    alt="back">
                            @else
                                <img src="{{ asset('image/greaterThanLeft.png') }}" style="width: 28px" class=""
                                    alt="back">
                            @endif
                        </button>
                    </div>
                @else
                    {{-- @if (Request::is('/') || Request::is('ar')) --}}
                    <a href="{{ url(app_get_locale(false, '/')) }}" class="bravo-logo">
                        @php
                            $logo_id = setting_item('logo_id');
                            if (!empty($row->custom_logo)) {
                                $logo_id = $row->custom_logo;
                            }
                        @endphp
                        <img src="{{ asset('image/nLogo.png') }}" style="width: 170px" alt="Travent Logo">
                    </a>
                    {{-- @endif --}}
                @endif
                @php
                    $languages = \Modules\Language\Models\Language::getActive();
                    $locale = session('website_locale', app()->getLocale());
                @endphp
                <div class="bravo-menu">
                    <?php generate_menu('primary'); ?>
                </div>
            </div>
            <div class="header-right">
                @if (!empty($header_right_menu))
                    <ul class="topbar-items">
                        @include('Core::frontend.currency-switcher')
                        @include('Language::frontend.switcher')
                        @if (!Auth::check())
                            <li class="login-item">
                                <a href="#login" data-toggle="modal" data-target="#login"
                                    class="login">{{ __('Login') }}</a>
                            </li>
                            @if (is_enable_registration())
                                <li class="signup-item">
                                    <a href="#register" data-toggle="modal" data-target="#register"
                                        class="signup">{{ __('Sign Up') }}</a>
                                </li>
                            @endif
                        @else
                            <li class="login-item dropdown">
                                <a href="#" data-toggle="dropdown" class="is_login">
                                    @if ($avatar_url = Auth::user()->getAvatarUrl())
                                        <img class="avatar" src="{{ $avatar_url }}"
                                            alt="{{ Auth::user()->getDisplayName() }}">
                                    @else
                                        <span
                                            class="avatar-text">{{ ucfirst(Auth::user()->getDisplayName()[0]) }}</span>
                                    @endif
                                    {{ __('Hi, :Name', ['name' => Auth::user()->getDisplayName()]) }}
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu text-left">

                                    @if (Auth::user()->hasPermission('dashboard_vendor_access'))
                                        <li><a href="{{ route('vendor.dashboard') }}"><i
                                                    class="icon ion-md-analytics"></i> {{ __('Vendor Dashboard') }}</a>
                                        </li>
                                    @endif
                                    <li class="@if (Auth::user()->hasPermission('dashboard_vendor_access')) menu-hr @endif">
                                        <a href="{{ route('user.profile.index') }}"><i
                                                class="icon ion-md-construct"></i> {{ __('My profile') }}</a>
                                    </li>
                                    @if (setting_item('inbox_enable'))
                                        <li class="menu-hr"><a href="{{ route('user.chat') }}"><i
                                                    class="fa fa-comments"></i> {{ __('Messages') }}</a></li>
                                    @endif
                                    <li class="menu-hr"><a href="{{ route('user.booking_history') }}"><i
                                                class="fa fa-clock-o"></i> {{ __('Booking History') }}</a></li>
                                    <li class="menu-hr"><a href="{{ route('user.change_password') }}"><i
                                                class="fa fa-lock"></i> {{ __('Change password') }}</a></li>
                                    @if (Auth::user()->hasPermission('dashboard_access'))
                                        <li class="menu-hr"><a href="{{ route('admin.index') }}"><i
                                                    class="icon ion-ios-ribbon"></i> {{ __('Admin Dashboard') }}</a>
                                        </li>
                                    @endif
                                    <li class="menu-hr">
                                        <a href="#"
                                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();"><i
                                                class="fa fa-sign-out"></i> {{ __('Logout') }}</a>
                                    </li>
                                </ul>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                    style="display: none;">
                                    {{ csrf_field() }}
                                </form>
                            </li>
                        @endif
                    </ul>
                @endif
                {{-- the new switcher behind the menu bar --}}
                <div class="d-flex d-md-none align-items-center">
                    <div class="">
                        @if (!empty($languages) && setting_item('site_enable_multi_lang'))
                            @foreach ($languages as $language)
                                @if ($locale != $language->locale)
                                    <div class="dropdown">
                                        <div class="">
                                            <a href="{{ get_lang_switcher_url($language->locale) }}"
                                                class="is_login text-decoration-none text-dark text-uppercase d-flex">
                                                {{ $language->locale }}
                                                @if ($language->flag)
                                                    <span class="m-1 flag-icon flag-icon-{{ $language->flag }}"></span>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        @endif
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
                @if (!Auth::check())
                    <li>
                        <a href="#login" data-toggle="modal" data-target="#login"
                            class="login">{{ __('Login') }}</a>
                    </li>
                    @if (is_enable_registration())
                        <li>
                            <a href="#register" data-toggle="modal" data-target="#register"
                                class="signup">{{ __('Sign Up') }}</a>
                        </li>
                    @endif
                @else
                    <li>
                        <a href="{{ route('user.profile.index') }}">
                            <i class="icofont-user-suited"></i>
                            {{ __('Hi, :Name', ['name' => Auth::user()->getDisplayName()]) }}
                        </a>
                    </li>
                    @if (Auth::user()->hasPermission('dashboard_vendor_access'))
                        <li><a href="{{ route('vendor.dashboard') }}"><i class="icon ion-md-analytics"></i>
                                {{ __('Vendor Dashboard') }}</a></li>
                    @endif
                    @if (Auth::user()->hasPermission('dashboard_access'))
                        <li>
                            <a href="{{ route('admin.index') }}"><i class="icon ion-ios-ribbon"></i>
                                {{ __('Admin Dashboard') }}</a>
                        </li>
                    @endif

                    <li>
                        <a href="#"
                            onclick="event.preventDefault(); document.getElementById('logout-form-mobile').submit();">
                            <i class="fa fa-sign-out"></i> {{ __('Logout') }}
                        </a>
                        <form id="logout-form-mobile" action="{{ route('logout') }}" method="POST"
                            style="display: none;">
                            {{ csrf_field() }}
                        </form>
                    </li>
                @endif
                {{-- Add become a host in mobile --}}
                <li>
                    <a href="/page/become-a-Host"><i class="icon icofont-brand-slideshare"></i>
                        {{ __('Become a vendor') }}</a>
                </li>
                <li>
                    <a href="{{ route('vendor.appointment') }}">
                        <i class="fa fa-history"></i> {{ __('Appointment') }}
                    </a>
                </li>
            </ul>
            @auth
                <ul class="main-menu">
                    @foreach ($menus as $menuItem)
                        <li class="{{ $menuItem['class'] }}" position="{{ $menuItem['position'] ?? '' }}">
                            <a href="{{ url($menuItem['url']) }}">
                                @if (!empty($menuItem['icon']))
                                    <span class="icon text-center"><i class="{{ $menuItem['icon'] }}"></i></span>
                                @endif
                                {!! clean($menuItem['title']) !!}
                            </a>
                            @if (!empty($menuItem['children']))
                                <i class="caret"></i>
                            @endif
                            @if (!empty($menuItem['children']))
                                <ul class="children">
                                    @foreach ($menuItem['children'] as $menuItem2)
                                        <li class="{{ $menuItem2['class'] }}"><a href="{{ url($menuItem2['url']) }}">
                                                @if (!empty($menuItem2['icon']))
                                                    <i class="{{ $menuItem2['icon'] }}"></i>
                                                @endif
                                                {!! clean($menuItem2['title']) !!}
                                            </a></li>
                                    @endforeach
                                </ul>
                            @endif
                        </li>
                    @endforeach
                </ul>
            @endauth
            <ul class="multi-lang">
                @include('Core::frontend.currency-switcher')
            </ul>

            {{-- remove the old switcher in mobile menu --}}

            {{-- <ul class="multi-lang">
                @include('Language::frontend.switcher')
            </ul> --}}
        </div>
        <div class="g-menu">
            <?php generate_menu('primary'); ?>
        </div>
    </div>
</div>
