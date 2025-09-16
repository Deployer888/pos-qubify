@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.profile') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_profile') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="col-lg-12 p-0">
    <div class="card">
        <div class="card-body">
            <h4 class="header-title">{{ __('custom.edit_profile') }}</h4>
            <form action="{{ route('admin.user.profile.update', $user->id) }}" method="post"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.name') }} <span class="error">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="Name" required
                            value="{{ $user->name }}">

                        @error('name')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.email') }} <span class="error">*</span></label>
                        <input type="email" name="email" class="form-control" required placeholder="Enter email"
                            value="{{ $user->email }}">

                        @error('email')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.phone') }}</label>
                        <input type="tel" id="phone" value="{{ $user->phone ?? '+1' }}" name="phone"
                            class="form-control phone">

                        @error('phone')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.password') }} <span class="error">*</span></label>
                        <input type="password" name="password" class="form-control" placeholder="Enter password">

                        @error('password')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.confirm_password') }} <span class="error">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control"
                            placeholder="Type password again">

                        @error('password_confirmation')
                        <p class="error">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="form-group col-sm-12 col-md-6 col-xl-4">
                        <label>{{ __('custom.avatar') }}</label>
                        <small>{{ __('custom.image_support_message') }}</small>
                        <div class="row">
                            <div class="col-sm-8">
                                <div class="ic-form-group position-relative">
                                    <input type="file" id="uploadFile" class="f-input form-control" name="avatar">
                                </div>
                            </div>
                            <div class="col-sm-12 col-md-6 col-xl-4">
                                <img class="img-64 mt-2 mt-xl-0" src="{{ $user->avatar_url }}" alt="avatar" />
                            </div>
                        </div>
                        @error('avatar')
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