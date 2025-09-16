@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.product') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_product') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.edit_product') }}</h4>
                <form class="form-validate edit-font" action="{{ route('admin.products.update', $product->id) }}"
                    method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-sm-12">
                            <label for="">{{__('custom.name')}} <span class="error">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.sku')}} <span class="error">*</span></label>
                            <input type="text" name="sku" class="form-control" value="{{ $product->sku }}" required>
                            @error('sku')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.barcode')}}</label>

                            <div class="row">
                                <div class="col-sm-8">
                                    <input type="text" id="barcode" name="barcode" class="form-control"
                                           placeholder="Product Barcode" value="{{ $barcode }}">
                                    @error('barcode')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div class="col-sm-4">
                                    <img class="img-fluid max-width-50p barcode-image barcode-max-height"
                                         id="b-image-show" alt="barcode">
                                    <input id="barcode-value" type="hidden" name="barcode_image">
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.category')}} <span class="error">*</span></label>
                            <select name="category_id" class="form-control select2" required>
                                <option value="">{{ __('custom.select_category') }}</option>
                                @foreach ($categories as $item)
                                <option value="{{ $item->id }}" @if($item->id == $product->category_id) selected
                                    @endif>{{ $item->name }}</option>
                                    @foreach ($item->subCategory as $subCategory)
                                        @include('admin.product_categories.child-categories', ['sub_category' => $subCategory])
                                    @endforeach
                                @endforeach
                            </select>
                            @error('category_id')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>


                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.brand')}}</label>
                            <select name="brand_id" class="form-control select2">
                                <option value="">{{ __('custom.select_brand') }}</option>
                                @foreach ($brands as $item)
                                <option value="{{ $item->id }}" @if($item->id == $product->brand_id) selected
                                    @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('brand_id')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.manufacturer')}}</label>
                            <select name="manufacturer_id" class="form-control select2">
                                <option value="">{{ __('custom.select_manufacturer') }}</option>
                                @foreach ($manufacturers as $item)
                                <option value="{{ $item->id }}" @if($item->id == $product->manufacturer_id) selected
                                    @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('manufacturer_id')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.model')}}</label>
                            <input type="text" name="model" class="form-control" value="{{ $product->model }}">
                            @error('model')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.price')}}({{currencySymbol()}}) <span
                                    class="error">*</span></label>
                            <input type="number" name="price" class="form-control" value="{{ $product->price }}"
                                step="any">
                            @error('price')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.customer_buying_price')}}({{currencySymbol()}}) <small
                                    class="text-muted">({{ __('custom.if_blank_then_actual_price_will_be_buying_price') }})</small></label>
                            <input type="number" name="customer_buying_price" class="form-control" value="{{ old('customer_buying_price',$product->customer_buying_price) }}"
                                   step="any">
                            @error('customer_buying_price')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.weight')}}</label>
                            <input type="number" name="weight" class="form-control" value="{{ $product->weight }}"
                                min="0" step="any">
                            @error('weight')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.weight_unit')}}</label>
                            <select name="weight_unit_id" class="form-control select2">
                                <option value="">{{ __('custom.select_weight_unit') }}</option>
                                @foreach ($weight_units as $item)
                                <option value="{{ $item->id }}" @if($item->id == $product->weight_unit_id) selected
                                    @endif>{{ $item->name }}</option>
                                @endforeach
                            </select>
                            @error('weight_unit_id')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="col-sm-12">
                            <label for="">{{ __('custom.dimension') }}</label>
                            <div class="row">
                                <div class="form-group col-sm-3">
                                    <label for="" class="text-muted">{{__('custom.length')}}</label>
                                    <input type="number" name="dimension_l" class="form-control"
                                        value="{{ $product->dimension_l }}" min="0" step="any">
                                    @error('dimension_l')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="" class="text-muted">{{__('custom.width')}}</label>
                                    <input type="number" name="dimension_w" class="form-control"
                                        value="{{ $product->dimension_w }}" min="0" step="any">
                                    @error('dimension_w')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="" class="text-muted">{{__('custom.depth')}}</label>
                                    <input type="number" name="dimension_d" class="form-control"
                                        value="{{ $product->dimension_d }}" min="0" step="any">
                                    @error('dimension_d')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="form-group col-sm-3">
                                    <label for="" class="text-muted">{{__('custom.measurement_unit')}}</label>
                                    <select name="measurement_unit_id" class="form-control select2">
                                        <option value="">{{ __('custom.select_weight_unit') }}</option>
                                        @foreach ($measurement_units as $item)
                                        <option value="{{ $item->id }}" @if($item->id == $product->measurement_unit_id)
                                            selected
                                            @endif>{{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('measurement_unit_id')
                                    <p class="error">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <div class="form-group col-sm-6">
                            <label class="d-block mb-3">{{ __('custom.tax') }}</label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tax_include" value="{{ \App\Models\Product::TAX_INCLUDED }}"
                                    name="tax_status" class="custom-control-input" @if($product->tax_status ==
                                \App\Models\Product::TAX_INCLUDED) checked="" @endif>
                                <label class="custom-control-label" for="tax_include">{{__('custom.include')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="tax_exclude" value="{{ \App\Models\Product::TAX_EXCLUDED }}"
                                    name="tax_status" class="custom-control-input" @if($product->tax_status ==
                                \App\Models\Product::TAX_EXCLUDED) checked="" @endif>
                                <label class="custom-control-label" for="tax_exclude">{{__('custom.exclude')}}</label>
                            </div>

                            @error('tax_exclude')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="custom-tax" class="form-group col-sm-6">
                            <label for="">{{__('custom.custom_tax_amount')}} (%)</label>
                            <input type="number" name="custom_tax" class="form-control"
                                value="{{ $product->custom_tax }}" min="0" step="any">
                            @error('custom_tax')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="">{{__('custom.notes')}}</label>
                            <input type="text" name="notes" class="form-control" value="{{ $product->notes }}">
                            @error('notes')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-sm-12">
                            <label for="">{{__('custom.image')}}</label>
                            <small class="font-12">{{ __('custom.image_support_message') }}</small>
                            <div class="form-group">
                                <input type="file" id="uploadFile" class="f-input form-control image_pick" data-image-for="thumb_image" name="thumb"
                                       value="{{ old('thumb') }}">
                            </div>
                            <div class="mb-4">
                                <img class="img-64" src="{{ $product->thumb_url }}" id="img_thumb_image" alt="images" />
                            </div>
                            @error('thumb')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group ic col-sm-12">
                            <label for="">{{__('custom.desc')}}</label>
                            <textarea class="form-control summernote" name="desc">{{ $product->desc }}</textarea>
                            @error('desc')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="col-sm-12">
                            <label for="">{{ __('custom.attributes') }}</label>
                            <product-attribute-edit :attributes="{{ $attributes }}" :product="{{ $product }}"
                                :old_attribute_data="{{ $old_attribute_data }}">
                            </product-attribute-edit>
                        </div>
                        <div class="form-group col-sm-6 mt-3">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div
                                class="custom-control custom-control custom-checkbox custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" {{ old('status',$product->status) == \App\Models\Product::STATUS_ACTIVE ? 'checked' : '' }}  value="{{ \App\Models\Product::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input" checked="">
                                <label class="custom-control-label" for="status_yes">{{__('custom.active')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" {{ old('status',$product->status) == \App\Models\Product::STATUS_INACTIVE ? 'checked' : '' }} value="{{ \App\Models\Product::STATUS_INACTIVE }}"
                                    name="status" class="custom-control-input">
                                <label class="custom-control-label" for="status_no">{{__('custom.inactive')}}</label>
                            </div>
                            <label class="d-block mb-3 mt-3">{{ __('custom.available_for') }} <span class="error">*</span></label>

                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="available_for_customer" value="{{ \App\Models\Product::SALE_AVAILABLE_FOR['customer'] }}"
                                       name="available_for" class="custom-control-input" @if($product->available_for == \App\Models\Product::SALE_AVAILABLE_FOR['customer'] ) checked="" @endif>
                                <label class="custom-control-label" for="available_for_customer">{{__('custom.customer')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="available_for_warehouse" value="{{ \App\Models\Product::SALE_AVAILABLE_FOR['warehouse'] }}"
                                       name="available_for" class="custom-control-input" @if($product->available_for == \App\Models\Product::SALE_AVAILABLE_FOR['warehouse'] ) checked="" @endif>
                                <label class="custom-control-label" for="available_for_warehouse">{{__('custom.warehouse')}}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="available_for_all" value="{{ \App\Models\Product::SALE_AVAILABLE_FOR['all'] }}"
                                       name="available_for" class="custom-control-input" @if($product->available_for == \App\Models\Product::SALE_AVAILABLE_FOR['all'] ) checked="" @endif>
                                <label class="custom-control-label" for="available_for_all">{{__('custom.both')}}</label>
                            </div>

                            @error('available_for')
                            <p class="error">{{ $message }}</p>
                            @enderror


                            {{-- <input type="hidden" name="is_variant" value="0"> --}}
                            <div class="custom-control custom-checkbox d-none">
                                <label for="" class=" "></label><br>
                                <input class="form-check-input custom-control-input" {{ $product->is_variant ? 'checked' : '' }} type="checkbox" value="1"
                                       id="isVariant" name="is_variant">
                                <label class="form-check-label custom-control-label checkbox-label" for="isVariant">
                                    {{ __('custom.is_variant_product')}}
                                </label>
                            </div>

                            @error('is_variant')
                            <p class="error">{{ $message }}</p>
                            @enderror

                            {{-- <input type="hidden" name="is_batch_product" value="0"> --}}
                            {{-- <div class="custom-control custom-checkbox">
                                <label for="" class=" "></label><br>
                                <input class="form-check-input custom-control-input" {{ $product->is_batch_product ? 'checked' : '' }} type="checkbox" value="1"
                                       id="isBatch" name="is_batch_product">
                                <label class="form-check-label custom-control-label checkbox-label" for="isBatch">
                                    {{ __('custom.is_batch_product')}}
                                </label>
                            </div>

                            @error('is_batch_product')
                            <p class="error">{{ $message }}</p>
                            @enderror --}}
                            <div class="custom-control custom-checkbox">
                                <label for="" class=" "></label><br>
                                <input class="form-check-input custom-control-input" type="checkbox" {{ $product->split_sale ? 'checked' : '' }} value="1"
                                       id="split_sale" name="split_sale">
                                <label class="form-check-label custom-control-label checkbox-label" for="split_sale">
                                    {{ __('custom.is_split_sale')}}
                                </label>
                            </div>

                            <div class="form-group mt-2">
                                <div>
                                    <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                        <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                    </button>
                                    <a class="btn btn-danger waves-effect" href="{{ route('admin.products.index') }}">
                                        <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                                    </a>
                                </div>
                            </div>
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
