<!-- MENU Start -->
<div class="navbar-custom">
    <div class="container-fluid">
        <div id="navigation">
            <!-- Navigation Menu-->
            <ul class="navigation-menu">
                @can('Dashboard')
                <li class="has-submenu">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="flaticon-dashboard"></i>{{ __('custom.dashboard') }}
                    </a>
                </li>
                @endcan
                @can('Warehouse')
                <li class="has-submenu">
                    <a href="{{ route('admin.warehouses.index') }}">
                        <i class="ti-home"></i>{{__('custom.warehouse')}}
                    </a>
                    @endcan
                    @canany(['User', 'Role'])
                <li class="has-submenu">
                    <a href="#" class="ic-javascriptVoid"><i class="flaticon-working"></i>{{
                        __('custom.administration') }} <i class="fas fa-chevron-down ic-down-icon"></i></a>
                    <ul class="submenu">
                        @can('User')
                        <li><a href="{{ route('admin.users.index') }}">{{ __('custom.users') }}</a></li>
                        @endcan
                        @can('Role')
                        <li><a href="{{ route('admin.roles.index') }}">{{ __('custom.roles') }}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany
                @canany(['Product', 'Product Category', 'Brand', 'Manufacturer'])
                <li class="has-submenu">
                    <a href="#" class="ic-javascriptVoid"><i class="flaticon-new-product"></i> {{ __('custom.product')
                        }} <i class="fas fa-chevron-down ic-down-icon"></i></a>
                    <ul class="submenu">
                        @can('Product')
                        <li><a href="{{ route('admin.products.index') }}">{{__('custom.product')}}</a></li>
                        @endcan
                        @can('Product Category')
                        <li><a
                                href="{{ route('admin.product-categories.index') }}">{{__('custom.product_category')}}</a>
                        </li>
                        @endcan
                        @can('Brand')
                        <li><a href="{{ route('admin.brands.index') }}">{{__('custom.brand')}}</a></li>
                        @endcan
                        @can('Manufacturer')
                        <li><a href="{{ route('admin.manufacturers.index') }}">{{__('custom.manufacturer')}}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany(['Weight Unit', 'Measurement Unti', 'Attribute'])
                <li class="has-submenu">
                    <a href="#" class="ic-javascriptVoid"><i class="flaticon-pamphlet"></i>{{ __('custom.catalog') }} <i
                            class="fas fa-chevron-down ic-down-icon"></i></a>
                    <ul class="submenu">
                        @can('Weight Unit')
                        <li><a href="{{ route('admin.weight-units.index') }}">{{__('custom.weight_unit')}}</a></li>
                        @endcan
                        @can('Measurement Unit')
                        <li><a href="{{ route('admin.measurement-units.index') }}">{{__('custom.measurement_unit')}}</a>
                        </li>
                        @endcan
                        @can('Attribute')
                        <li><a href="{{ route('admin.attributes.index') }}">{{__('custom.attribute')}}</a></li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @canany('Purchase')
                <li class="has-submenu">
                    <a href="#" class="ic-javascriptVoid"><i class="flaticon-shopping-bag-1"></i> {{ __t('purchases') }}
                        <i class="fas fa-chevron-down ic-down-icon"></i></a>
                    <ul class="submenu">
                        @can('Purchase')
                        <li><a href="{{ route('admin.purchases.index') }}">{{ __t('purchases') }}</a></li>
                        @endcan
                        @can('Purchase Receive List')
                        <li><a href="{{ route('admin.purchases.receive-list') }}">{{ __t('purchase_receive_list') }}</a>
                        </li>
                        @endcan
                        @can('Purchase Return List')
                        <li><a href="{{ route('admin.purchases.return.list') }}">{{ __t('purchase_return_list') }}</a>
                        </li>
                        @endcan
                    </ul>
                </li>
                @endcanany

                @can('Customer')
                <li class="has-submenu">
                    <a href="{{ route('admin.customers.index') }}">
                        <i class="flaticon-conversation"></i> {{ __('custom.customers') }}
                    </a>
                </li>
                @endcan
                @can('Supplier')
                <li class="has-submenu">
                    <a href="{{ route('admin.suppliers.index') }}">
                        <i class="flaticon-inventory"></i>{{ __('custom.suppliers') }}
                    </a>
                </li>
                @endcan

                <li class="has-submenu">
                    <a href="#" class="ic-javascriptVoid">
                        {{__('custom.more')}} <i class="fas fa-chevron-down ic-down-icon"></i>
                    </a>
                    <ul class="submenu">
                        @canany(['Expenses Category', 'Expenses'])
                        <li class="has-submenu">
                            <a href="#" class="ic-javascriptVoid">{{ __('custom.expenses') }}</a>
                            <ul class="submenu">
                                @can('Expenses Category')
                                <li>
                                    <a
                                        href="{{ route('admin.expenses-categories.index') }}">{{__('custom.expenses_category')}}</a>
                                </li>
                                @endcan
                                @can('Expenses')
                                <li>
                                    <a href="{{ route('admin.expenses.index') }}">{{__('custom.expenses')}}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcanany
                        @can('Invoice')
                        <li>
                            <a href="{{ route('admin.invoices.index') }}">
                                {{ __('custom.invoice_manage') }}
                            </a>
                        </li>
                        @endcan

                        @can('Sale Return')
                        <li class="has-submenu">
                            <a href="#" class="ic-javascriptVoid">{{ __('custom.sale_return') }}</a>
                            <ul class="submenu">
                                @can('Return Sale Return')
                                <li>
                                    <a
                                        href="{{ route('admin.sales-return.createable_list') }}">{{__('custom.sale_return')}}</a>
                                </li>
                                @endcan
                                @can('Sale Return List')
                                <li>
                                    <a
                                        href="{{ route('admin.sales-return.index') }}">{{__('custom.sale_return_list')}}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        @can('Reports')
                        <li class="has-submenu">
                            <a href="#" class="ic-javascriptVoid">{{ __('custom.reports') }}</a>
                            <ul class="submenu">
                                @can('Expenses Report')
                                <li>
                                    <a href="{{ route('admin.reports.expenses') }}">{{__('custom.expenses_report')}}</a>
                                </li>
                                @endcan
                                @can('Sales Report')
                                <li>
                                    <a href="{{ route('admin.reports.sales') }}">{{__('custom.sales_report')}}</a>
                                </li>
                                @endcan
                                @can('Purchases Report')
                                <li>
                                    <a
                                        href="{{ route('admin.reports.purchases') }}">{{__('custom.purchases_report')}}</a>
                                </li>
                                @endcan
                                @can('Payments Report')
                                <li>
                                    <a href="{{ route('admin.reports.payments') }}">{{__('custom.payments_report')}}</a>
                                </li>
                                @endcan
                            </ul>
                        </li>
                        @endcan

                        @can('Settings')
                        <li>
                            <a href="{{ route('admin.system-settings.edit') }}" class="">{{ __('custom.settings') }}
                            </a>
                        </li>
                        @endcan

                    </ul>
                </li>
            </ul>
            <!-- End navigation menu -->
        </div> <!-- end #navigation -->
    </div> <!-- end container -->
</div> <!-- end navbar-custom -->
