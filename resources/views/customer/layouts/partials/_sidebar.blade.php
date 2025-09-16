<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Side Menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">{{ __('custom.main') }}</li>
                <li>
                    <a href="{{ route('customer.dashboard') }}" class="">
                        <i class="flaticon-dashboard"></i><span> {{ __('custom.dashboard') }} </span>
                    </a>
                </li>
                @if (auth()->guard('customer')->user()->type == 'customer')
                    <li>
                        <a href="{{ route('customer.employee.index') }}" class="">
                            <i class="flaticon-conversation"></i><span> {{ __('custom.employee') }} </span>
                        </a>
                    </li>
                @endif
                <li class="{{ request()->is('customer/invoices/*') ? 'mm-active' : '' }}">
                    <a href="{{ route('customer.invoices.index') }}"
                        class="{{ request()->is('customer/invoices/*') ? 'mm-active' : '' }}">
                        <i class="flaticon-shopping-bag-1"></i><span> {{ __('custom.invoices') }} </span>
                    </a>
                </li>
                <li class="{{ request()->is('customer/draft-invoices/*') ? 'mm-active' : '' }}">
                    <a href="{{ route('customer.draft-invoices.index') }}"
                        class="{{ request()->is('customer/draft-invoices/*') ? 'mm-active' : '' }}">
                        <i class="flaticon-shopping-bag"></i><span> {{ __('custom.draft_invoices') }} </span>
                    </a>
                </li>
                <li>
                    <a href="#" class=""><i class="flaticon-expenses"></i><span>
                            {{ __('custom.product_return') }}
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span></a>
                    <ul class="submenu">
                        <li
                            class="{{ request()->is('customer/products-return/*') || request()->is('customer/products-return-request/*') ? 'mm-active' : '' }}">
                            <a
                                href="{{ route('customer.products-return-request.createable_list') }}">{{ __('custom.return_request') }}</a>
                        </li>
                        <li>
                            <a
                                href="{{ route('customer.products-return-request.index') }}">{{ __('custom.return_request_list') }}</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class=""><i class="flaticon-report"></i><span>
                            {{ __('custom.reports') }}
                            <span class="float-right menu-arrow">
                                <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="10 15 15 20 20 15"></polyline>
                                    <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                </svg>
                            </span>
                        </span></a>
                    <ul class="submenu">
                        <li>
                            <a href="{{ route('customer.reports.purchase') }}">{{ __('custom.purchases_report') }}</a>
                        </li>
                        <li>
                            <a href="{{ route('customer.reports.payments') }}">{{ __('custom.payments_report') }}</a>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
