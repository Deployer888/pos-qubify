@extends('admin.layouts.master')

@section('content')

<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.role') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.add_role') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">{{ __('custom.add_role') }}</h4>
                <form class="forms-sample" id="ic_permission_add" action="{{route('admin.roles.store')}}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="form-group col-sm-6">
                            <label for="name" class="col-form-label">{{ __('custom.role_name') }}</label>
                            <div>
                                <input type=" text" id="name" value="{{ old('name') }}" class="form-control" name="name"
                                    placeholder="Enter role name" required>
                            </div>
                            @error('name')
                            <p class="error">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="row my-2">
                        <div class="col-8 pt-1">
                            <div class="custom-control pl-0">
                                <label for="customCheck-all">{{ __('custom.all_permission') }}</label>
                            </div>
                        </div>
                        <div class="col-4 pt-1">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" name="parent_id" class="custom-control-input"
                                    id="customCheck-all" value="all">
                                <label class="custom-control-label" for="customCheck-all"></label>
                            </div>
                        </div>
                    </div>

                    @foreach ($permissions as $i => $permission)
                    <div class="ic_parent_permission">
                        <div class="row my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-{{$permission->id}}"><strong>{{$permission->name}}
                                        </strong></label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                                        class="custom-control-input ic-parent-permission"
                                        id="customCheck-{{$permission->id}}" ref="{{$permission->id}}">
                                    <label class="custom-control-label" for="customCheck-{{$permission->id}}"></label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ic_div-show" id="ic_parent-{{$permission->id}}">
                        <div class="row">
                            @foreach ($permission->childs as $children)
                            <div class="col-8 pt-1">
                                <div class="custom-control">
                                    <label for="customCheck-{{$children->id}}">{{$children->name}}</label>
                                </div>
                            </div>
                            <div class="col-4 pt-1">
                                <div class="custom-control custom-checkbox">
                                    <input type="checkbox" name="permissions[]"
                                        class="custom-control-input parent-identy-{{$permission->id}}"
                                        id="customCheck-{{$children->id}}" value="{{$children->id}}">
                                    <label class="custom-control-label" for="customCheck-{{$children->id}}"></label>
                                </div>
                            </div>
                            @endforeach
                        </div>

                    </div>
                    @endforeach

                    @error('permissions')
                    <p class="error">{{ $message }}</p>
                    @enderror

                    <div class="form-group mt-4">
                        <div>
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> <span>{{ __('custom.submit') }}</span>
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.roles.index') }}">
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


@push('script')

@endpush

@push('style')

@endpush