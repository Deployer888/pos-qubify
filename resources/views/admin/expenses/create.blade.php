@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.expenses') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.add_expenses') }}</li>
            </ol>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">
                <h4 class="page-title">{{ __('custom.add_expenses') }}</h4>
                <form class="form-validate" action="{{ route('admin.expenses.store') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">{{ __('custom.category') }} <span class="error">*</span></label>
                                <select name="category" class="form-control" required>
                                    <option value="">{{ __('custom.select_category') }}</option>

                                    @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>

                                @error('category')
                                <p class="error">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label for="">{{ __('custom.date') }} <span class="error">*</span></label>
                                <input type="text" name="date" class="form-control datepicker-autoclose"
                                    autocomplete="off" value="{{ old('date') }}" required>
                                @error('date')
                                <p class="error">{{ $message }}</p>
                                @enderror

                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('custom.title') }} <span class="error">*</span></label>
                        <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
                        @error('title')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('custom.expenses') }}</label>
                        <expenses-add currency_symbol="{{currencySymbol()}}"></expenses-add>

                        @error('data')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('custom.files') }}</label>
                        <div class="form-group">
                            <input type="file" id="uploadFile" class="f-input form-control" name="files[]" multiple
                                autocomplete="off" value="{{ old('files') }}">
                        </div>
                        @error('files')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="">{{ __('custom.notes') }} <small>{{ __('custom.max_50_char') }}</small></label>
                        <textarea rows="5" class="form-control" maxlength="500"
                            name="notes">{{ old('notes') ?? '' }}</textarea>
                        @error('notes')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label for="expense_user">{{ __('custom.expense_user') }} </label>
                        <select name="expense_user" id="expense_user" class="form-control">
                            <option value=""  selected>- {{ __('custom.select') .' '. __('custom.expense_user')}} -</option>

                            @foreach($users as $key => $user)
                                <option {{ old('expense_user') == $key ? 'selected' : '' }} value="{{ $key }}">{{ $user }}</option>
                            @endforeach
                        </select>
                        @error('expense_user')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.expenses.index') }}">
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
