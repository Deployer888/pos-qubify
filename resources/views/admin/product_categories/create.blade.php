@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#"
                            class="ic-javascriptVoid">{{ __('custom.product_category') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('custom.add_product_category') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __('custom.add_product_category') }}</h4>
                    <form class="form-validate" action="{{ route('admin.product-categories.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row">


                            <div class="form-group col-sm-6">
                                <label for="">{{ __('custom.category_name') }} <span class="error">*</span></label>
                                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                                    required>
                                @error('name')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group col-sm-6">
                                <label for="">{{ __('custom.parent_category') }}</label>
                                <select name="parent_id" class="form-control select2">
                                    <option value="">{{ __('custom.select_category') }}</option>
                                    @foreach ($categories as $item)
                                        <option {{ old('parent_id') == $item->id ? 'selected' : '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                        @foreach ($item->subCategory as $subCategory)
                                            @include('admin.product_categories.child-categories', [
                                                'sub_category' => $subCategory,
                                            ])
                                        @endforeach
                                    @endforeach
                                </select>
                                @error('parent_id')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{ __('custom.desc') }}</label>
                                <input type="text" name="desc" class="form-control" value="{{ old('desc') }}">
                                @error('desc')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label for="">{{ __('custom.image') }}</label>
                                <small>{{ __('custom.image_support_message') }}</small>
                                <div class="form-group">
                                    <input type="file" id="uploadFile" class="f-input form-control" name="image"
                                        value="{{ old('image') }}">
                                </div>
                                @error('image')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group col-sm-6">
                                <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="status_yes"
                                        value="{{ \App\Models\ProductCategory::STATUS_ACTIVE }}" name="status"
                                        class="custom-control-input" checked="">
                                    <label class="custom-control-label" for="status_yes">{{ __('custom.active') }}</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="status_no"
                                        value="{{ \App\Models\ProductCategory::STATUS_INACTIVE }}" name="status"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="status_no">{{ __('custom.inactive') }}</label>
                                </div>

                                @error('status')
                                    <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <div>
                                <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                    <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                                </button>
                                <a class="btn btn-danger waves-effect"
                                    href="{{ route('admin.product-categories.index') }}">
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
