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
        <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.product_category')
            }}</a></li>
        <li class="breadcrumb-item active">{{ __('custom.product_category_list') }}</li>
      </ol>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-body">
        <h4 class="header-title">{{ __('custom.product_category_list') }}</h4>
        {!! $dataTable->table(['class' => 'nowrap']) !!}
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
      <form action="{{ route('admin.product-categories.import') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="modal-body">
          <div class="form-group">
            <label for="import_file">{{ __('custom.import') }} (Excel/CSV)</label>
            <input type="file" name="import_file" id="import_file" class="form-control" required accept=".xlsx,.csv">
          </div>
          <a href="{{ static_asset('sample_product_categories_import.xlsx') }}" class="btn btn-link">{{
            __('custom.download_sample') }}</a>

        </div>
        <div class="text-end ml-3">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('custom.cancel') }}</button>
          <button type="submit" class="btn btn-success">{{ __('custom.import') }}</button>
        </div>
      </form>
      <table class="table table-bordered table-sm mt-4">
        <tbody>
          <tr>
            <td>{{__('custom.name')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.desc')}}</td>
            <th><span class="border border-warning text-warning fs-6 fw-normal p-1">This Field is not required</span>
            </th>
          </tr>
          <tr>
            <td>{{__('custom.image')}}</td>
            <th><span class="border border-warning text-warning fs-6 fw-normal p-1">This Field is not required</span>
            </th>
          </tr>
          <tr>
            <td>{{__('custom.status')}}</td>
            <th><span class="border border-success text-success fs-6 fw-normal p-1">This Field is required</span></th>
          </tr>
          <tr>
            <td>{{__('custom.parent_category')}}</td>
            <th><span class="border border-warning text-warning fs-6 fw-normal p-1">This Field is not required</span>
            </th>
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

@endpush