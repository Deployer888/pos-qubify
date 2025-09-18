<!-- Top Bar Start -->
<div class="topbar">
    <!-- LOGO -->
    <div class="topbar-left">
        <a href="{{ route('admin.dashboard') }}" class="logo">
            <span>
                <img src={{ site_logo() }} class="ic-logo-height" alt="logo">
            </span>
            <i>
                <img src={{ site_logo() }} class="ic-logo-small" alt="logo">
            </i>
        </a>
        <div class="float-right">
            <button class="button-menu-mobile ic-collapsed-btn mobile-device-arrow open-left">
                <div class="ic-medi-menu">
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                    <div class="ic-bar"></div>
                </div>
            </button>
        </div>
    </div>
    <nav class="navbar-custom">
        <ul class="navbar-right d-flex list-inline float-right mb-0 align-items-center">
            <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#"
                   role="button" aria-haspopup="false" aria-expanded="false">
                   @if (app()->getLocale() == 'en')
                   <img src="{{ static_asset('admin/images/us_flag.jpg')}}" class="mr-2" height="12" alt=""/>
                   English <span class="mdi mdi-chevron-down"></span>
               @elseif (app()->getLocale() == 'es')
                   <img src="{{ static_asset('admin/images/es_flag.png')}}" class="mr-2" height="12" alt=""/>
                   Español <span class="mdi mdi-chevron-down"></span>
               @elseif (app()->getLocale() == 'fr')
                   <img src="{{ static_asset('admin/images/fr_flag.png')}}" class="mr-2" height="12" alt=""/>
                   Français <span class="mdi mdi-chevron-down"></span>
               @elseif (app()->getLocale() == 'de')
                   <img src="{{ static_asset('admin/images/de_flag.png')}}" class="mr-2" height="12" alt=""/>
                   Deutsch <span class="mdi mdi-chevron-down"></span>
               @elseif (app()->getLocale() == 'ar')
                   <img src="{{ static_asset('admin/images/ar_flag.png')}}" class="mr-2" height="12" alt=""/>
                   عربى <span class="mdi mdi-chevron-down"></span>
               @elseif (app()->getLocale() == 'bn')
                   <img src="{{ static_asset('admin/images/bd_flag.png')}}" class="mr-2" height="12" alt=""/>
                   বাংলা <span class="mdi mdi-chevron-down"></span>
               @endif

                </a>
                <div class="dropdown-menu dropdown-menu-right language-switch">
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=en">
                        <img src="{{ static_asset('admin/images/us_flag.jpg') }}" alt="en" height="16"/><span> English</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=es">
                        <img src="{{ static_asset('admin/images/es_flag.png') }}" alt="es" height="16"/><span> Español</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=fr">
                        <img src="{{ static_asset('admin/images/fr_flag.png') }}" alt="fr" height="16"/><span> Français</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=de">
                        <img src="{{ static_asset('admin/images/de_flag.png') }}" alt="de" height="16"/><span> Deutsch</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=ar">
                        <img src="{{ static_asset('admin/images/ar_flag.png') }}" alt="ar" height="16"/><span> عربى</span>
                    </a>
                    <a class="dropdown-item d-flex align-items-center justify-content-between flex-row-reverse"
                       href="{{ route('admin.set-lang') }}?lang=bn">
                        <img src="{{ static_asset('admin/images/bd_flag.png') }}" alt="bn" height="16"/><span> বাংলা</span>
                    </a>
                </div>

            </li>
            <li class="dropdown notification-list list-inline-item d-md-inline-block">
                {{-- <a class="btn btn-outline-primary ic-pos-button-header" href="{{ route('admin.invoices.create') }}">
                    <i class="mdi mdi-cart-outline"></i> {{ __('custom.pos') }}
                </a> --}}
                <a class="btn btn-outline-primary ic-pos-button-header" href="{{ route('admin.depot-pos') }}">
                    <i class="mdi mdi-cart-outline"></i> {{ __('custom.pos') }}
                </a>
            </li>
            <!-- notification -->
            {{-- <li class="dropdown notification-list list-inline-item">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i class="mdi mdi-cart-outline"></i>
                    <span
                        class="badge badge-pill badge-danger noti-icon-badge">{{ @$lowStockProduct['total_product'] }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                    <!-- item-->
                    <h6 class="dropdown-item-text">
                        {{ __('custom.low_notifications') }} ({{ @$lowStockProduct['total_product'] }})
                    </h6>
                    <div data-simplebar="init" class="notification-item-list" style="max-height: 230px;">
                        @isset($lowStockProduct['total_product'])
                            @foreach ($lowStockProduct['productStockList'] as $product)
                                <!-- item-->
                                <a href="{{ route('admin.low-stock-products') }}" class="dropdown-item notify-item active">
                                    <div class="notify-icon bg-success">
                                        <img src="{{ $product['image'] }}" class="img-fluid rounded-circle"
                                            alt="{{ $product['name'] }}">
                                    </div>
                                    <p class="notify-details">{{ $product['name'] }}
                                        <span
                                            class="text-muted">{{ __('custom.sku') . ': ' . $product['sku'] . ', ' . __('custom.price') . ': ' . $product['price'] }}</span>
                                    </p>
                                    <div class="ic-badge-base">
                                        <span class="badge badge-danger" title="{{ __('custom.stock') }}">
                                            {{ $product['stock'] }}
                                        </span>
                                    </div>
                                </a>
                            @endforeach
                        @endisset
                    </div>
                    <!-- All-->
                    <a href="{{ route('admin.low-stock-products') }}"
                        class="dropdown-item text-center notify-all text-primary">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li> --}}
            <!-- notification -->
            <li class="dropdown notification-list list-inline-item">
                <a class="nav-link dropdown-toggle arrow-none" data-toggle="dropdown" href="#" role="button"
                    aria-haspopup="false" aria-expanded="false">
                    <i class="ion ion-md-notifications noti-icon"></i>
                    <span
                        class="badge badge-pill badge-danger noti-icon-badge second-notification-count">{{ auth()->user()->unreadNotifications->count() }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg">
                    @isset(auth()->user()->unreadNotifications)
                        <h6 class="dropdown-item-text second-notification-left-count">
                            {{ __('custom.notification') }} ({{ auth()->user()->unreadNotifications->count() }})
                        </h6>
                        <div class="slimscroll notification-item-list second-notification-list" data-simplebar="init" style="max-height: 230px;">
                            @isset(auth()->user()->unreadNotifications)
                                @foreach (auth()->user()->unreadNotifications as $notification)
                                    <a href="{{ $notification->data['url'] }}" data-notification-id="{{ $notification->id }}"
                                        class="dropdown-item notify-item notification-item active">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $notification->data['title'] }}
                                            </h6>
                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">{{ $notification->data['message'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endisset
                            @isset(auth()->user()->readNotifications)
                                @foreach (auth()->user()->readNotifications as $notification)
                                    <a href="{{ $notification->data['url'] }}" data-notification-id="{{ $notification->id }}"
                                        class="dropdown-item notify-item notification-item ">
                                        <div>
                                            <h6 class="mb-1">
                                                {{ $notification->data['title'] }}
                                            </h6>

                                            <div class="font-size-12 text-muted">
                                                <p class="mb-1">{{ $notification->data['message'] }}</p>
                                            </div>
                                        </div>
                                    </a>
                                @endforeach
                            @endisset

                        </div>
                    @endisset
                    <!-- All-->
                    <a href="{{ route('admin.markasreadall') }}"
                        class="dropdown-item text-center notify-all text-primary">
                        View all <i class="fi-arrow-right"></i>
                    </a>
                </div>
            </li>
            <!-- sync-->
            {{-- <li class="dropdown notification-list list-inline-item d-none d-md-inline-block">
                <a class="nav-link" href="/change-layout">
                    <i class="fas fa-align-justify"></i>
                </a>
            </li> --}}
            <!-- full screen -->
            <li class="dropdown notification-list d-none d-md-block">
                <a class="nav-link" href="#" id="btn-fullscreen">
                    <i class="mdi mdi-fullscreen noti-icon"></i>
                </a>
            </li>
            <!-- Profile-->
            <li class="dropdown notification-list">
                <div class="dropdown notification-list nav-pro-img">
                    <a class="dropdown-toggle nav-link arrow-none nav-user" data-toggle="dropdown" href="#"
                        role="button" aria-haspopup="false" aria-expanded="false">
                        @if (Auth::user()->avatar)
                            <img src="{{ Auth::user()->avatar_url }}" alt="user" class="rounded-circle">
                        @else
                            <img src="{{ Auth::user()->avatar_url }}" alt="user" class="rounded-circle">
                        @endif
                    </a>
                    <div class="dropdown-menu dropdown-menu-right profile-dropdown ">

                        <a href="/admin/profile" class="dropdown-item">
                            {{ \Illuminate\Support\Str::limit(Auth::user()->name, 15) ?? '' }}<br>
                            <small>{{ Auth::user()->email ?? '' }}</small>
                        </a>

                        <a class="dropdown-item logout-btn" href="#">
                            <i class="mdi mdi-power text-danger"></i>
                            {{ __('logout') }}</a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                        </form>
                    </div>
                </div>
            </li>
        </ul>

        <ul class="list-inline menu-left mb-0 ic-left-content">
            <li class="float-left ic-larged-deviced">
                <button class="button-menu-mobile">
                    <i class="mdi mdi-arrow-right open-left ic-mobile-arrow"></i>
                    <div class="ic-medi-menu ic-humbarger-bar">
                        <div class="ic-bar"></div>
                        <div class="ic-bar"></div>
                        <div class="ic-bar"></div>
                    </div>
                </button>
            </li>
        </ul>
    </nav>
</div>
<!-- Top Bar End -->
