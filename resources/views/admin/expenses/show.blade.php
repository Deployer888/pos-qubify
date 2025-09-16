@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.expenses') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.expenses') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">{{ __('custom.expenses') }}</h4>
                <div class="row">
                    <div class="col-sm-6">
                        <div class="form-group">
                            <p><strong>{{ __('custom.category') }}: </strong> {{ $expenses->category->name ?? '' }}</p>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                            <p><strong>{{ __('custom.date') }}: </strong> {{ $expenses->date }}</p>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <p><strong>{{ __('custom.title') }}: </strong> {{ $expenses->title }}</p>
                </div>


                <div class="form-group">
                    <label for="">{{ __('custom.expenses') }}</label>
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>{{ __('custom.item_name') }}</th>
                                <th>{{ __('custom.item_qty') }}</th>
                                <th>{{ __('custom.amount') }}({{currencySymbol()}})</th>
                                <th>{{ __('custom.note') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($expenses->items as $item)
                            <tr>
                                <td>
                                    {{ $item->item_name }}
                                </td>
                                <td>
                                    {{ $item->item_qty }}
                                </td>
                                <td>
                                    {{ make2decimal($item->amount) }}
                                </td>
                                <td>
                                    {{ $item->note }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <label for="">{{ __('custom.total') }}</label>
                                </td>
                                <td>
                                    <p>
                                        <b>{{currencySymbol()}}{{ make2decimal($expenses->total) }}</b>
                                    </p>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                <div class="form-group">
                    <label for="">{{ __('custom.files') }}</label>
                    <table class="table table-sm">
                        @foreach ($expenses->files as $item)
                        <tr>
                            <td>{{ \Str::limit($item->original_name, 100, '...') }}</td>
                            <td class="text-right">
                                <form action="{{ route('admin.expenses.deleteFile', $item->id) }}"
                                    id="delete-form-{{$item->id}}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <a href="{{ $item->full_path }}" class="btn btn-sm btn-info">
                                        <i class="fa fa-download" download></i></a>
                                    <button class="btn btn-sm btn-danger delete-list-data"
                                        data-from-id="{{ $item->id }}" type="button" title="Delete"><i
                                            class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

                <div class="form-group">
                    <label for="">{{ __('custom.notes') }}</label>
                    <p>{{ $expenses->notes ?? '' }}</p>
                </div>
                <div class="form-group">
                    <div>
                        <a class="btn btn-secondary waves-effect" href="{{ route('admin.expenses.index') }}">
                            <i class="fa fa-arrow-left"></i> <span>{{ __('custom.back') }}</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
@endpush

@push('script')
@endpush
