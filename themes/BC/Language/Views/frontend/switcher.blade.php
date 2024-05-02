{{-- new one click switcher --}}

@php
$languages = \Modules\Language\Models\Language::getActive();
$locale = session('website_locale',app()->getLocale());
@endphp
@if(!empty($languages) && setting_item('site_enable_multi_lang'))
<li class="dropdown p-0">
    @foreach($languages as $language)
    @if($locale != $language->locale)
<li class="d-none d-md-inline p-0 mx-2">
    <a href="{{get_lang_switcher_url($language->locale)}}" class="is_login">
        @if($language->flag)
        <span class="m-1 flag-icon flag-icon-{{$language->flag}}"></span>
        @endif
        {{$language->name}}
    </a>
</li>
@endif
@endforeach
</li>
@endif

{{-- old switcher --}}

{{--Multi Language--}}
{{-- @if(!empty($languages) && setting_item('site_enable_multi_lang'))
    <li class="dropdown">
        @foreach($languages as $language)
            @if($locale == $language->locale)
                <a href="#" data-toggle="dropdown" class="is_login">
                    @if($language->flag)
                        <span class="flag-icon flag-icon-{{$language->flag}}"></span>
@endif
{{$language->name}}
<i class="fa fa-angle-down"></i>
</a>
@endif
@endforeach
<ul class="dropdown-menu text-left">
    @foreach($languages as $language)
    @if($locale != $language->locale)
    <li>
        <a href="{{get_lang_switcher_url($language->locale)}}" class="is_login">
            @if($language->flag)
            <span class="flag-icon flag-icon-{{$language->flag}}"></span>
            @endif
            {{$language->name}}
        </a>
    </li>
    @endif
    @endforeach
</ul>
</li>
@endif --}}
{{--End Multi language--}}
