@extends('admin.layouts.master')


@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.role') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.edit_role') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body m-t-10">
                <h4 class="mt-0 header-title">{{ __('custom.edit_role') }}</h4>
                <div class="col-sm-12">
                    <form action="{{ route('admin.roles.update', $role->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="form-group col-lg-6">
                                <label for="name" class="col-form-label">{{ __('custom.role_name') }}</label>
                                <div>
                                    <input type=" text" id="name" value="{{ $role->name }}" class="form-control"
                                        name="name" placeholder="Enter role name" autofocus required>
                                </div>
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
                        <div class="row ic_parent_permission my-2">
                            <div class="col-8 pt-1">
                                <div class="custom-control pl-0">
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
                        <div class="ic_div-show" id="ic_parent-{{$permission->id}}">
                            <div class="row">
                                @foreach ($permission->childs as $children)
                                <div class="col-8 pt-1">
                                    <div class="custom-control pl-0">
                                        <label for="customCheck-{{$children->id}}">{{$children->name}}</label>
                                    </div>
                                </div>
                                <div class="col-4 pt-1">
                                    <div class="custom-control custom-checkbox">
                                        <input type="checkbox" name="permissions[]"
                                            class="custom-control-input parent-identy-{{$permission->id}}"
                                            id="customCheck-{{$children->id}}" value="{{$children->id}}"
                                            {{in_array($children->id,$role_permission)?'checked':''}}
                                        >
                                        <label class="custom-control-label" for="customCheck-{{$children->id}}"></label>
                                    </div>
                                </div>
                                @endforeach
                            </div>

                        </div>
                        @endforeach

                        <div class="form-group mt-3">
                            <button class="btn btn-primary waves-effect waves-lightml-2" type="submit">
                                <i class="fa fa-save"></i> {{ __('custom.submit') }}
                            </button>
                            <a class="btn btn-danger waves-effect" href="{{ route('admin.roles.index') }}">
                                <i class="fa fa-times"></i> {{ __('custom.cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection



@push('script')
<script type="text/javascript">
    !(function ($) {
    "use strict";
        $("#customCheck-all").on('click', function () {
            $('input:checkbox').not(this).prop('checked', this.checked);
            $('div .ic_div-show').toggle();
        });

        $('.ic-parent-permission').on('click', function(){
            let parent_id = $(this).val();
            $(`#ic_parent-${parent_id}`).toggle();

            if ($(`#customCheck-${parent_id}`).is(':checked')){
                $(`.parent-identy-${parent_id}`).each(function(){
                    $(this).prop('checked', true);
                });
            }else{
                $(`.parent-identy-${parent_id}`).each(function(){
                    $(this).prop('checked', false);
                });
            }
        });
        @foreach($parents_id as $parent_id)
            $('#customCheck-{{$parent_id}}').prop('checked', true);
            $(`#ic_parent-{{$parent_id}}`).show();
        @endforeach
    })(jQuery);
</script>
@endpush

@push('style')

@endpush