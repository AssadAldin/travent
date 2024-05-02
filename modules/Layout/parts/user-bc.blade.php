<?php
[$notifications, $countUnread] = getNotify();

?>
@if (!empty($breadcrumbs))
    <div class="breadcrumb-page-bar" aria-label="breadcrumb">
        <div class="d-flex justify-content-between">
            <div>
                <ul class="page-breadcrumb">
                    <li class="">
                        <a href="{{ url('/') }}"><i class='fa fa-home'></i> {{ __('Home') }}</a>
                        <i class="fa fa-angle-right"></i>
                    </li>
                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class=" {{ $breadcrumb['class'] ?? '' }}">
                            @if (!empty($breadcrumb['url']))
                                <a href="{{ $breadcrumb['url'] }}">{{ $breadcrumb['name'] }}</a>
                                <i class="fa fa-angle-right"></i>
                            @else
                                {{ $breadcrumb['name'] }}
                            @endif
                        </li>
                    @endforeach
                </ul>
                @php
                    $languages = \Modules\Language\Models\Language::getActive();
                    $locale = session('website_locale', app()->getLocale());
                @endphp
            </div>
            <div class="mx-3 my-1">
                <div class="d-flex justify-content-between align-items-center">
                    {{-- Multi Language --}}
                    @if (!empty($languages) && setting_item('site_enable_multi_lang'))
                        <div class="dropdown language-dropdown mx-1 d-none d-md-block">
                            @foreach ($languages as $language)
                                @if ($locale != $language->locale)
                                    <div class="dropdown">
                                        <div class="">
                                            <a href="{{ get_lang_switcher_url($language->locale) }}"
                                                class="is_login text-decoration-none text-dark text-uppercase ">
                                                {{ $language->locale }}
                                                @if ($language->flag)
                                                    <span class="m-1 flag-icon flag-icon-{{ $language->flag }}"></span>
                                                @endif
                                            </a>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    @endif
                    <div class="dropdown dropdown-notifications float-right mx-1" style="min-width: 0">
                        <div>
                            <a data-toggle="dropdown" class="user-dropdown d-flex align-items-center"
                                aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bell m-1 p-1"></i>
                                <span class="badge badge-danger notification-icon">{{ $countUnread }}</span>
                            </a>
                            <div class="dropdown-menu overflow-auto notify-items dropdown-container dropdown-menu-right dropdown-large"
                                aria-labelledby="dropdownMenuButton">
                                <div class="dropdown-toolbar">
                                    <div class="dropdown-toolbar-actions">
                                        <a href="#" class="markAllAsRead">{{ __('Mark all as read') }}</a>
                                    </div>
                                    <h3 class="dropdown-toolbar-title">{{ __('Notifications') }} (<span
                                            class="notif-count">{{ $countUnread }}</span>)</h3>
                                </div>
                                <ul class="dropdown-list-items p-0">
                                    @if (count($notifications) > 0)
                                        @foreach ($notifications as $oneNotification)
                                            @php
                                                $active = $class = '';
                                                $data = json_decode($oneNotification['data']);

                                                $idNotification = @$data->id;
                                                $forAdmin = @$data->for_admin;
                                                $usingData = @$data->notification;

                                                $services = @$usingData->type;
                                                $idServices = @$usingData->id;
                                                $title = @$usingData->message;
                                                $name = @$usingData->name;
                                                $avatar = @$usingData->avatar;
                                                $link = @$usingData->link;

                                                if (empty($oneNotification->read_at)) {
                                                    $class = 'markAsRead';
                                                    $active = 'active';
                                                }

                                            @endphp
                                            <li class="notification {{ $active }} list-group-item">
                                                <div class="media">
                                                    <div class="media-left">
                                                        <div class="media-object">
                                                            @if ($avatar)
                                                                <img class="image-responsive" src="{{ $avatar }}"
                                                                    alt="{{ $name }}">
                                                            @else
                                                                <span
                                                                    class="avatar-text">{{ ucfirst($name[0]) }}</span>
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="media-body">
                                                        <a class="{{ $class }}" data-id="{{ $idNotification }}"
                                                            href="{{ $link }}">{!! $title !!}</a>
                                                        <div class="notification-meta">
                                                            <small
                                                                class="timestamp">{{ format_interval($oneNotification->created_at) }}</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach
                                    @endif
                                </ul>
                                <div class="dropdown-footer text-center">
                                    <a href="{{ route('core.notification.loadNotify') }}">{{ __('View More') }}</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
