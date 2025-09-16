@extends('admin.layouts.master')

@section('content')

    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <h4 class="page-title">{{ __('custom.update_product_stock') }}</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"
                                                   class="ic-javascriptVoid">{{ __('custom.product_stock') }}</a>
                    </li>
                    <li class="breadcrumb-item active">{{ __('custom.update_product_stock') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    @include('includes.messages.validation')
                    <form class="form-validate" action="{{ route('admin.product-stocks.update', $product->id) }}"
                          method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            @if ($product->is_variant)
                                <input type="hidden" name="is_variant" value="1">

                                <variant-product-stock-update
                                    :product="{{ $product }}"
{{--                                    :sku_setting="{{ json_encode($skuSetting) }}"--}}
{{--                                    :barcode="{{ $barcode }}"--}}
                                    :warehouses="{{ $warehouses }}"
                                    :old_stocks="{{ $old_stocks }}">
                                </variant-product-stock-update>
                            @else
                                <input type="hidden" name="is_variant" value="0">
                                <normal-product-stock-update
                                    :product="{{ $product }}"
                                    :warehouses="{{ $warehouses }}"
                                    :old_stocks="{{ $old_stocks }}">
                                </normal-product-stock-update>
                            @endif
                        </div>
                        @if(blank($old_stocks))
                        <div class=" form-group">
                            <div>
                                <p class="text-info">{{__('custom.nb')}}:{{ __('custom.the_stock_is_not_created_yet_first_stock_will_be_added_in_purchase_list') }}</p>
                            </div>
                        </div>
                            <div class="form-group">
                                <div>
                                    <label for="select_supplier">{{ __('custom.select_supplier') }}</label>
                                    <select name="supplier_id" class="form-control" id="select_supplier" required>
                                        <option value="">{{ __('custom.select_supplier') }}</option>
                                        @foreach($suppliers as $supplier)
                                            <option value="{{ $supplier->id }}">{{ $supplier->first_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
                        <div class=" form-group">
                            <div>
                                <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                    <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                </button>
                                <a class="btn btn-danger waves-effect" href="{{ route('admin.products.index') }}">
                                    <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('style')
@endpush

@push('script')
@endpush
