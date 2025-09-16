@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.product') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('custom.import_products') ?? 'Import Products' }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="header-title">{{ __('custom.import_products') ?? 'Import Products' }}</h4>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    <form class="edit-font" action="{{ route('products.import') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label for="file">{{ __('custom.excel_file') ?? 'Excel File (.xlsx, .csv, .xls)' }} <span class="error">*</span></label>
                            <input type="file" name="file" class="form-control" required accept=".xlsx,.csv,.xls">
                            @error('file')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group mt-2">
                            <a href="{{ asset('sample/product_import_template.xlsx') }}" class="btn btn-link">
                                <i class="fa fa-download"></i> {{ __('custom.download_sample') ?? 'Download Sample Template' }}
                            </a>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">
                            <i class="fa fa-upload"></i> {{ __('custom.import') ?? 'Import' }}
                        </button>
                        <a class="btn btn-danger mt-2" href="{{ route('admin.products.index') }}">
                            <i class="fa fa-times"></i> <span>{{ __('custom.cancel') }}</span>
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
