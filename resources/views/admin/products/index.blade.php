@extends('admin.layouts.master')

@section('content')
@if ($errors->any())
<div class="alert alert-danger mt-2">
  <ul class="mb-0">
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>
@endif
<div class="page-title-box">
  <div class="row align-items-center">
    <div class="col-sm-6">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.products') }}</a>
        </li>
        <li class="breadcrumb-item active">{{ __('custom.products') }}</li>
      </ol>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <div class="row">
          <div class="col-lg-6 col-md-6 col-sm-6">
            <h4 class="header-title">{{ __('custom.product_list') }}</h4>
          </div>
          <div class="col-lg-6 col-md-6 col-sm-6 text-right">
            <a class="btn btn-sm btn-primary mb-4" href="javascript:void(0)" id="download_barcode">{{
              __('custom.download_all_barcode') }}</a>

            <form action="{{ route('admin.products.barcode.download.zip') }}" method="post" id="download_form"
              style="display: none">
              @csrf
              <input type="text" name="product_ids" id="product_ids">
            </form>
          </div>
        </div>

        {!! $dataTable->table() !!}
      </div>
    </div>
  </div>
</div>

<!-- Import Modal -->
<div class="modal fade" id="importModal" tabindex="-1" role="dialog" aria-labelledby="importModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="importModalLabel">{{ __('custom.import') }}</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form action="{{ route('admin.products.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="file">{{ __('custom.import') }} (Excel/CSV)</label>
            <input type="file" name="import_file" id="import_file" class="form-control" required accept=".xlsx,.csv">
          </div>
          <a href="{{ static_asset('sample_products_import.xlsx') }}" class="btn btn-link">{{
            __('custom.download_sample') }}</a>
        </div>
        <div class="text-end ml-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('custom.cancel') }}</button>
          <button type="submit" class="btn btn-primary">{{ __('custom.import') }}</button>
        </div>
      </form>
      <table class="table table-bordered table-sm mt-4">
        <tbody>
          <tr>
            <td>{{__('custom.name')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.sku')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.barcode')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.price')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.status')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.category')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>Others fields</td>
            <th><span class="border border-warning text-warning fs-6 fw-normal p-1">Others fields are not
                required</span></th>
          </tr>

        </tbody>
      </table>
      </form>
    </div>
  </div>
</div>
@endsection

@push('style')
@include('includes.styles.datatable')
@endpush

@push('script')
@include('includes.scripts.datatable')
<script src="{{ static_asset('admin/js/bulk_barcode_download.js') }}"></script>
@endpush