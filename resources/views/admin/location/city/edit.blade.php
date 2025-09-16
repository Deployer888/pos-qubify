@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">

            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.city') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_city') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.edit_city') }}</h4>
                <form class="form-validate" action="{{ route('admin.cities.update', $city->id) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.state')}} <span class="error">*</span></label>
                            <select name="state_id" class="form-control select2" required>
                                <option value="">{{ __('custom.select_state') }}</option>
                                @foreach ($states as $state)
                                    <option value="{{ $state->id }}" {{ old('state_id',$city->state_id) == $state->id ? 'selected' : '' }}>{{ $state->name }}</option>
                                @endforeach
                            </select>
                            @error('state_id')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-sm-6">
                            <label for="">{{__('custom.name')}} <span class="error">*</span></label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $city->name) }}" required>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div>
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.states.index') }}">
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
