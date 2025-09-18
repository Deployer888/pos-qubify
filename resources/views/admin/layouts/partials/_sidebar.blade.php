<!-- ========== Left Sidebar Start ========== -->
<div class="left side-menu">
    <div class="slimscroll-menu" id="remove-scroll">
        <!--- Side Menu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu" id="side-menu">
                <li class="menu-title">{{ __('custom.main') }}</li>
                @can('Dashboard')
                    <li>
                        <a href="{{ route('admin.depot-dashboard.index') }}" class="">
                            <i class="flaticon-dashboard"></i><span> {{ __('custom.dashboard') }} </span>
                        </a>
                    </li>
                @endcan
                <li class="menu-title">{{ __('custom.components') }}</li>
                @if (auth()->user()->hasRole('Super Admin') ||
                        auth()->user()->hasAnyPermission([
                                'Show Depot List',
                                'Add Depot',
                                'Manage Depot Stock',
                                'Manage Depot Customers',
                                'Access Depot POS',
                            ]))
                    <li>
                        <a href="#" class="">
                            <i class="flaticon-shop"></i>
                            <span>
                                Depot Management
                                <span class="float-right menu-arrow">
                                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="10 15 15 20 20 15"></polyline>
                                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                    </svg>
                                </span>
                            </span>
                        </a>
                        <ul class="submenu">
                            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->hasRole('Depot Manager'))
                                <li>
                                    <a href="{{ route('admin.depot-dashboard.index') }}">
                                        <i class="mdi mdi-circle-medium"></i>Dashboard
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->can('Show Depot List'))
                                <li>
                                    <a href="{{ route('admin.depots.index') }}">
                                        <i class="mdi mdi-circle-medium"></i>Depot List
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->can('Add Depot'))
                                <li>
                                    <a href="{{ route('admin.depots.create') }}">
                                        <i class="mdi mdi-circle-medium"></i>Add New Depot
                                    </a>
                                </li>
                            @endif

                            @if (auth()->user()->hasRole('Super Admin') || auth()->user()->can('Access Depot POS'))
                                <li>
                                    <a href="{{ route('admin.depots.invoices.index') }}">
                                        <i class="mdi mdi-circle-medium"></i>Invoices
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </li>
                @endif
                @canany(['User', 'Role'])
                    <li>
                        <a href="#" class=""><i class="flaticon-working"></i><span>
                                {{ __('custom.administration') }}
                                <span class="float-right menu-arrow">
                                    <svg class="svg-icon iq-arrow-right arrow-active" width="20" height="20"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                        stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round">
                                        <polyline points="10 15 15 20 20 15"></polyline>
                                        <path d="M4 4h7a4 4 0 0 1 4 4v12"></path>
                                    </svg>
                                </span>
                            </span>
                        </a>
                        <ul class="submenu">
                            @can('User')
                                <li class="{{ request()->is('admin/users/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.users.index') }}">{{ __('custom.users') }}</a>
                                </li>
                            @endcan
                            @can('Role')
                                <li class="{{ request()->is('admin/roles/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.roles.index') }}">{{ __('custom.roles') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                {{-- @can('Warehouse')
                    <li class="{{ request()->is('admin/warehouses/*') ? 'mm-active' : '' }}">
                        <a href="{{ route('admin.warehouses.index') }}"
                            class="{{ request()->is('admin/warehouses/*') ? 'mm-active' : '' }}">
                            <i class="ti-home"></i><span> {{ __('custom.warehouse') }} </span>
                        </a>
                    </li>
                @endcan

                @canany(['Product', 'Product Category', 'Brand', 'Manufacturer'])
                    <li>
                        <a href="#" class=""><i class="flaticon-new-product"></i><span>
                                {{ __('custom.product') }}
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
                            @can('Product')
                                <li class="{{ request()->is('admin/products/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.products.index') }}">{{ __('custom.product') }}</a></li>
                            @endcan
                            @can('Product Category')
                                <li class="{{ request()->is('admin/product-categories/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.product-categories.index') }}">{{ __('custom.product_category') }}</a>
                                </li>
                            @endcan
                            @can('Brand')
                                <li class="{{ request()->is('admin/brands/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.brands.index') }}">{{ __('custom.brand') }}</a></li>
                            @endcan
                            @can('Manufacturer')
                                <li class="{{ request()->is('admin/manufacturers/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.manufacturers.index') }}">{{ __('custom.manufacturer') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @canany(['Weight Unit', 'Measurement Unti', 'Attribute'])
                    <li>
                        <a href="#" class=""><i class="flaticon-pamphlet"></i><span>
                                {{ __('custom.catalog') }}
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

                            @can('Weight Unit')
                                <li class="{{ request()->is('admin/weight-units/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.weight-units.index') }}">{{ __('custom.weight_unit') }}</a>
                                </li>
                            @endcan
                            @can('Measurement Unit')
                                <li class="{{ request()->is('admin/measurement-units/*') ? 'mm-active' : '' }}">
                                    <a
                                        href="{{ route('admin.measurement-units.index') }}">{{ __('custom.measurement_unit') }}</a>
                                </li>
                            @endcan
                            @can('Attribute')
                                <li class="{{ request()->is('admin/attributes/*') ? 'mm-active' : '' }}"><a
                                        href="{{ route('admin.attributes.index') }}">{{ __('custom.attribute') }}</a></li>
                            @endcan

                        </ul>
                    </li>
                @endcanany
                @can('Invoice')
                    <li class="{{ request()->is('admin/invoices/*') ? 'mm-active' : '' }}">
                        <a class="{{ request()->is('admin/invoices/*') ? 'mm-active' : '' }}"
                            href="{{ route('admin.invoices.index') }}" class="">
                            <i class="flaticon-bill"></i><span> {{ __('custom.invoice_manage') }} </span>
                        </a>
                    </li>
                @endcan


                @can('Sale Return')
                    <li>
                        <a href="#" class=""><i class="flaticon-expenses"></i><span>
                                {{ __('custom.sale_return') }}
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
                            @can('Return Sale Return')
                                <li>
                                    <a
                                        href="{{ route('admin.sales-return.createable_list') }}">{{ __('custom.sale_return') }}</a>
                                </li>
                            @endcan
                            @can('Sale Return List')
                                <li>
                                    <a href="{{ route('admin.sales-return.index') }}">{{ __('custom.sale_return_list') }}</a>
                                </li>
                            @endcan
                            @can('Sale Return Request List')
                                <li class="{{ request()->is('admin/products-return-request/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin.sales-return.requests') }}">{{ __('custom.return_requests') }}
                                        ({{ @$total_pending_req }})</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcan


                @canany('Purchase')
                    <li>
                        <a href="#" class=""><i class="flaticon-shopping-bag-1"></i><span>
                                {{ __t('purchases') }}
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
                            @can('Purchase')
                                <li><a href="{{ route('admin.purchases.index') }}">{{ __t('purchases') }}</a></li>
                            @endcan
                            @can('Purchase Receive List')
                                <li><a
                                        href="{{ route('admin.purchases.receive-list') }}">{{ __t('purchase_receive_list') }}</a>
                                </li>
                            @endcan
                            @can('Purchase Return List')
                                <li><a
                                        href="{{ route('admin.purchases.return.list') }}">{{ __t('purchase_return_list') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany
                @canany(['Coupon', 'Add Coupon', 'Edit Coupon', 'Delete Coupon'])
                    <li>
                        <a href="#" class=""><i class="mdi mdi-percent"></i><span>
                                {{ __t('Marketing') }}
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
                            @can('Coupon')
                                <li
                                    class="{{ request()->is('admin/coupons/*') || request()->is('admin/coupon-products/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin.coupons.index') }}">{{ __t('coupon') }}</a></li>
                            @endcan
                        </ul>
                    </li>
                @endcanany

                @can('Customer')
                    <li class="{{ request()->is('admin/customers/*') ? 'mm-active' : '' }}">
                        <a class="{{ request()->is('admin/customers/*') ? 'mm-active' : '' }}"
                            href="{{ route('admin.customers.index') }}" class="">
                            <i class="flaticon-conversation"></i><span> {{ __('custom.customers') }} </span>
                        </a>
                    </li>
                @endcan
                @can('Supplier')
                    <li class="{{ request()->is('admin/suppliers/*') ? 'mm-active' : '' }}">
                        <a class="{{ request()->is('admin/suppliers/*') ? 'mm-active' : '' }}"
                            href="{{ route('admin.suppliers.index') }}" class="">
                            <i class="flaticon-conversation"></i><span> {{ __('custom.suppliers') }} </span>
                        </a>
                    </li>
                @endcan

                @canany(['Expenses Category', 'Expenses'])
                    <li>
                        <a href="#" class=""><i class="flaticon-expenses"></i><span>
                                {{ __('custom.expenses') }}
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
                            @can('Expenses Category')
                                <li class="{{ request()->is('admin/expenses-categories/*') ? 'mm-active' : '' }}">
                                    <a
                                        href="{{ route('admin.expenses-categories.index') }}">{{ __('custom.expenses_category') }}</a>
                                </li>
                            @endcan
                            @can('Expenses')
                                <li class="{{ request()->is('admin/expenses/*') ? 'mm-active' : '' }}">
                                    <a href="{{ route('admin.expenses.index') }}">{{ __('custom.expenses') }}</a>
                                </li>
                            @endcan
                        </ul>
                    </li>
                @endcanany



                @can('Reports')
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
                            @can('Expenses Report')
                                <li>
                                    <a href="{{ route('admin.reports.expenses') }}">{{ __('custom.expenses_report') }}</a>
                                </li>
                            @endcan
                            @can('Sales Report')
                                <li>
                                    <a href="{{ route('admin.reports.sales') }}">{{ __('custom.sales_report') }}</a>
                                </li>
                            @endcan
                            @can('Expired Products Report')
                                <li>
                                    <a
                                        href="{{ route('admin.report.expired-products') }}">{{ __('custom.expired_product_report') }}</a>
                                </li>
                            @endcan
                            @can('Purchases Report')
                                <li>
                                    <a href="{{ route('admin.reports.purchases') }}">{{ __('custom.purchases_report') }}</a>
                                </li>
                            @endcan
                            @can('Payments Report')
                                <li>
                                    <a href="{{ route('admin.reports.payments') }}">{{ __('custom.payments_report') }}</a>
                                </li>
                            @endcan
                            @can('Payments Report')
                                <li>
                                    <a
                                        href="{{ route('admin.report.warehouse-stock') }}">{{ __('custom.warehouse_stock_report') }}</a>
                                </li>
                            @endcan
                            @can('Warehouse Selling Price Report')
                                <li>
                                    <a
                                        href="{{ route('admin.report.warehouse-price') }}">{{ __('custom.warehouse_selling_price_report') }}</a>
                                </li>
                            @endcan

                            @can('Payments Report')
                                <li>
                                    <a
                                        href="{{ route('admin.report.loss-profit') }}">{{ __('custom.loss_profit_report') }}</a>
                                </li>
                            @endcan

                        </ul>
                    </li>
                @endcan --}}

                @can('Settings')
                    {{--                <li> --}}
                    {{--                    <a href="{{ route('admin.system-settings.edit') }}" class=""> --}}
                    {{--                        <i class="ti-settings"></i><span> {{ __('custom.settings') }} </span> --}}
                    {{--                    </a> --}}
                    {{--                </li> --}}
                    <li>
                        <a href="#" class=""><i class="ti-settings"></i><span>
                                {{ __('custom.settings') }}
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
                            @can('Settings')
                                <li>
                                    <a href="{{ route('admin.system-settings.edit') }}">{{ __('custom.settings') }}</a>
                                </li>
                            @endcan
                            <li>
                                <a href="{{ route('admin.countries.index') }}">{{ __('custom.country') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.states.index') }}">{{ __('custom.state') }}</a>
                            </li>
                            <li>
                                <a href="{{ route('admin.cities.index') }}">{{ __('custom.city') }}</a>
                            </li>

                        </ul>
                    </li>
                @endcan

            </ul>
        </div>
        <!-- Sidebar -->
        <div class="clearfix"></div>

    </div>
    <!-- Sidebar -left -->

</div>
<!-- Left Sidebar End -->
