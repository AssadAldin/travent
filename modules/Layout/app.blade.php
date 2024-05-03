<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="{{ $html_class ?? '' }}">

<head>
    <!-- Begin Inspectlet Asynchronous Code -->
    <!--<script type="text/javascript">-->
    <!--    (function() {-->
    <!--        window.__insp = window._insp || [];-->
    <!--        __insp.push(['wid', 854488709]);-->
    <!--        var ldinsp = function() {-->
    <!--            if (typeof window._inspld != "undefined") return;-->
    <!--            window._inspld = 1;-->
    <!--            var insp = document.createElement('script');-->
    <!--            insp.type = 'text/javascript';-->
    <!--            insp.async = true;-->
    <!--            insp.id = "inspsync";-->
    <!--            insp.src = ('https:' == document.location.protocol ? 'https' : 'http') +-->
    <!--                '://cdn.inspectlet.com/inspectlet.js?wid=854488709&r=' + Math.floor(new Date().getTime() /-->
    <!--                    3600000);-->
    <!--            var x = document.getElementsByTagName('script')[0];-->
    <!--            x.parentNode.insertBefore(insp, x);-->
    <!--        };-->
    <!--        setTimeout(ldinsp, 0);-->
    <!--    })();-->
    <!--</script>-->
    <!-- End Inspectlet Asynchronous Code -->

    <!-- Travent in Github -->

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @php event(new \Modules\Layout\Events\LayoutBeginHead()); @endphp
    @php
        use App\Models\MobileNav;
        use App\Models\HideShowNav;
        use Illuminate\Support\Str;
        $favicon = setting_item('site_favicon');
        $main_color = setting_item('style_main_color', '#5191fa');
        $items = MobileNav::orderBy('order', 'asc')->get();
        $ar = Str::contains(Request::url(), '/ar');
        $w2n = Str::contains(request()->userAgent(), 'w2n');
        $hideShowNav = HideShowNav::find(1);
    @endphp
    @if ($favicon)
        @php
            $file = (new \Modules\Media\Models\MediaFile())->findById($favicon);
        @endphp
        @if (!empty($file))
            <link rel="icon" type="{{ $file['file_type'] }}"
                href="{{ asset('uploads/' . $file['file_path']) }}" />
        @else:
            <link rel="icon" type="image/png" href="{{ url('images/favicon.png') }}" />
        @endif
    @endif

    @include('Layout::parts.seo-meta')
    <link href="{{ asset('libs/bootstrap/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/font-awesome/css/font-awesome.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/ionicons/css/ionicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/icofont/icofont.min.css') }}" rel="stylesheet">
    <link href="{{ asset('libs/select2/css/select2.min.css') }}" rel="stylesheet">
    <link href="{{ asset('dist/frontend/css/notification.css') }}" rel="newest stylesheet">
    <link href="{{ asset('dist/frontend/css/app.css?_ver=' . config('app.asset_version')) }}" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;500&display=swap" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="{{ asset('libs/daterange/daterangepicker.css') }}">
    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link rel='stylesheet' id='google-font-css-css'
        href='https://fonts.googleapis.com/css?family=Poppins%3A300%2C400%2C500%2C600&display=swap' type='text/css'
        media='all' />
    {!! \App\Helpers\Assets::css() !!}
    {!! \App\Helpers\Assets::js() !!}
    @include('Layout::parts.global-script')
    <!-- Styles -->
    @stack('css')
    {{-- Custom Style --}}
    <link href="{{ route('core.style.customCss') }}" rel="stylesheet">
    <link href="{{ asset('libs/carousel-2/owl.carousel.css') }}" rel="stylesheet">
    @if (setting_item_with_lang('enable_rtl'))
        <link href="{{ asset('dist/frontend/css/rtl.css') }}" rel="stylesheet">
    @endif
    @if (!is_demo_mode())
        {!! setting_item('head_scripts') !!}
        {!! setting_item_with_lang_raw('head_scripts') !!}
    @endif

    <style>
        img {
            pointer-events: none;
        }

        /*body {*/
        /*    -webkit-user-select: none;*/
        /*    -khtml-user-select: none;*/
        /*    -moz-user-select: none;*/
        /*    -ms-user-select: none;*/
        /*    -o-user-select: none;*/
        /*    user-select: none;*/
        /*}*/

        .stk {
            position: fixed;
            bottom: 870px;
            right: 370px;
            z-index: 100;
        }

        /* body {
            background: linear-gradient(to right, #f12711, #f5af19);
        } */
    </style>



    <!-- Google Tag Manager -->
    <script>
        (function(w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
                j = d.createElement(s),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
        })(window, document, 'script', 'dataLayer', 'GTM-P5V2VMVL');
    </script>
    <!-- End Google Tag Manager -->
    
    @if (!$w2n)
        <!--AppsFlyerSdkObject-->
            <script>
                ! function(t, e, n, s, a, c, i, o, p) {
                    t.AppsFlyerSdkObject = a, t.AF = t.AF || function() {
                            (t.AF.q = t.AF.q || []).push([Date.now()].concat(Array.prototype.slice.call(arguments)))
                        }, t.AF.id = t.AF.id || i, t.AF.plugins = {}, o = e.createElement(n), p = e.getElementsByTagName(n)[0], o
                        .async = 1, o.src = "https://websdk.appsflyer.com?" + (c.length > 0 ? "st=" + c.split(",").sort().join(
                            ",") + "&" : "") + (i.length > 0 ? "af_id=" + i : ""), p.parentNode.insertBefore(o, p)
                }(window, document, "script", 0, "AF", "banners", {
                    banners: {
                        key: "86a21368-811e-46de-8224-65473a7ca141"
                    }
                });
                AF('banners', 'showBanner')
            </script>
        <!--AppsFlyerSdkObject-->
    @endif
    
    



    <meta name="author" content="Travent" />

    <meta name="msvalidate.01" content="7E5188F5DA32D5D873CCF0014255F40F" />

    <meta name="DC.title"
        content="Resorts in Dubai, Top Farmhouses in UAE, UAE Properties for Stays, UAE Farmhouses" />
    <meta name="geo.region" content="AE" />
    <meta name="geo.placename" content="Abu Dhabi" />
    <meta name="geo.position" content="24.453835;54.377401" />
    <meta name="ICBM" content="24.453835, 54.377401" />

    <meta name="google-site-verification" content="7S4V9Cu8fvyN5SXPodzwOJtG-y_01ehUjbAhe6aBozo" />

</head>
<!--oncopy="return false" oncut="return false"-->
<body 
    class="frontend-page {{ !empty($row->header_style) ? 'header-' . $row->header_style : 'header-normal' }} {{ $body_class ?? '' }} @if (setting_item_with_lang('enable_rtl')) is-rtl @endif @if (is_api()) is_api @endif"
    style="font-family: 'Cairo', sans-serif;">
    <!-- Google Tag Manager (noscript) -->
    {{-- {{dd($row)}} --}}
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P5V2VMVL" height="0" width="0"
            style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    @if (!is_demo_mode())
        {!! setting_item('body_scripts') !!}
        {!! setting_item_with_lang_raw('body_scripts') !!}
    @endif
    @php
        $main_color = setting_item('style_main_color', '#5191fa');
    @endphp
    <div class="bravo_wrap">
        {{-- @dd(Request::is('admin/*')) --}}
        @if (!is_api())
            <div class="bg-white sticky-top">
                @include('Layout::parts.topbar')
                @include('Layout::parts.header')
            </div>
            {{-- @if (!Request::is('/') && !Request::is('ar'))
                <div class="d-flex d-none d-sm-block d-md-none d-lg-none stk">
                    <button type="button" onclick="history.back()" class="btn rounded-circle text-center p-2"
                        style="background-color: rgba(255, 255, 255, 0.603);">
                        @if (setting_item_with_lang('enable_rtl'))
                            <img src="{{ asset('image/greaterThan.png') }}" style="width: 28px" class=""
                                alt="back">
                        @else
                            <img src="{{ asset('image/greaterThanLeft.png') }}" style="width: 28px" class=""
                                alt="back">
                        @endif
                    </button>
                </div>
            @endif --}}
        @endif

        @yield('content')

        @include('Layout::parts.footer')
    </div>
    @if (!is_demo_mode())
        {!! setting_item('footer_scripts') !!}
        {!! setting_item_with_lang_raw('footer_scripts') !!}
    @endif
    @if ($hideShowNav->status)
        @if (!$w2n)
            @if (\Auth::user()?->role_id == 5 or \Auth::user()?->role_id == 1 or \Auth::user()?->role_id == 2)
                <nav
                    class="navbar fixed-bottom navbar-light bg-light align-items-end py-0 d-sm-block d-md-none d-lg-none">
                    @if (!empty($items))
                        @foreach ($items as $item)
                            @if ($item->type == 'admin')
                                <a class="navbar-brand text-center" style="color:{{ $main_color }}"
                                    href="@if ($ar) /ar{{ $item->url }} @else {{ $item->url }} @endif "><i
                                        class="{{ $item->icon }} fa-{{ $item->size }}x"></i>
                                    <div class="h6 my-0 py-0 text-dark"><small>{{ __($item->text) }}</small></div>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </nav>
            @else
                <nav
                    class="navbar fixed-bottom navbar-light bg-light align-items-end py-0 d-sm-block d-md-none d-lg-none">
                    @if (!empty($items))
                        @foreach ($items as $item)
                            @if ($item->type == 'user')
                                <a class="navbar-brand text-center" style="color:{{ $main_color }}"
                                    href="@if ($ar) /ar{{ $item->url }} @else {{ $item->url }} @endif "><i
                                        class="{{ $item->icon }} fa-{{ $item->size }}x"></i>
                                    <div class="h6 my-0 py-0 text-dark"><small>{{ __($item->text) }}</small></div>
                                </a>
                            @endif
                        @endforeach
                    @endif
                </nav>
            @endif
        @endif
    @endif
</body>

</html>
