@extends('admin.layouts.master')

@section('content')
    <div class="page-title-box">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.users') }}</a></li>
                    <li class="breadcrumb-item active">{{ __('custom.edit_user') }}</li>
                </ol>
            </div>
        </div>
    </div>

    <div class="col-lg-12 p-0">
        <div class="card">
            <div class="card-body">
                <h4 class="header-title">{{ __('custom.edit_user') }}</h4>
                <form action="{{ route('admin.users.update', $user->id) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.name') }} <span class="error">*</span></label>
                            <input type="text" name="name" class="form-control" placeholder="Name" required
                                value="{{ $user->name }}">

                            @error('name')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.email') }} <span class="error">*</span></label>
                            <input type="email" name="email" class="form-control" required placeholder="Enter email"
                                value="{{ $user->email }}">

                            @error('email')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.phone') }}</label>
                            <input type="tel" id="phone" value="{{ $user->phone ?? '+1' }}" name="phone"
                                class="form-control phone">

                            @error('phone')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.password') }} <span class="error">*</span></label>
                            <input type="password" name="password" class="form-control" placeholder="Enter password">

                            @error('password')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.confirm_password') }} <span class="error">*</span></label>
                            <input type="password" name="password_confirmation" class="form-control"
                                placeholder="Type password again">

                            @error('password_confirmation')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.avatar') }}</label>
                            <small>{{ __('custom.image_support_message') }}</small>
                            <div class="row">
                                <div class="col-lg-8 col-md-12 col">
                                    <div class="ic-form-group position-relative">
                                        <input type="file" id="uploadFile" class="f-input form-control image_pick"
                                            data-image-for="avatar" name="avatar">
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-6">
                                    <img class="img-64 mt-3 mt-md-3 default-image-size" src="{{ $user->avatar_url }}"
                                        id="img_avatar" alt="avatar" />
                                </div>
                            </div>
                            @error('avatar')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-group col-lg-4 col-md-6">
                            <label>{{ __('custom.role') }} <span class="error">*</span></label>
                            <div>
                                @if ($roles)
                                    <select name="role" class="form-control">
                                        <option value="">{{ __('custom.select_role') }}</option>
                                        @foreach ($roles as $role)
                                            <option value="{{ $role->id }}"
                                                {{ count($user->roles) && $user->roles[0]->id == $role->id ? 'selected' : '' }}>
                                                {{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            @error('role')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-xl-4 col-lg-6 d-none">
                            <label>{{ __('custom.warehouses') }} <span class="error">*</span></label>
                            <div>
                                @if ($warehouses)
                                    <select name="warehouses[]" class="form-control select2" multiple="multiple">
                                        @foreach ($warehouses as $warehouse)
                                            <option value=" {{ $warehouse->id }}"
                                                {{ $user->userWarehouses->contains('warehouse_id', $warehouse->id) ? 'selected' : '' }}>
                                                {{ $warehouse->name }}</option>
                                        @endforeach
                                    </select>
                                @endif
                            </div>
                            @error('warehouses')
                                <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                        <div class="form-group col-lg-4 col-md-6">
                            <label class="d-block mb-3">{{ __('custom.status') }} <span class="error">*</span></label>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_yes" value="{{ \App\Models\User::STATUS_ACTIVE }}"
                                    name="status" class="custom-control-input" checked="">
                                <label class="custom-control-label" for="status_yes">{{ __('custom.active') }}</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="status_no" value="{{ \App\Models\User::STATUS_INACTIVE }}"
                                    name="status" class="custom-control-input">
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
                                <i class="fa fa-save"></i> {{ __('custom.submit') }}
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.users.index') }}">
                                <i class="fa fa-times"></i> {{ __('custom.cancel') }}
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div> <!-- end col -->
@endsection


@push('style')
@endpush

@push('script')
@endpush
