@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <h4 class="page-title">{{ __('custom.role_details') }}</h4>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#" class="ic-javascriptVoid">{{ __('custom.roles') }}</a></li>
                <li class="breadcrumb-item active">{{ __('custom.role_details') }}</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body m-t-10">
                <a class="btn btn-secondary btn-sm mb-3" href="{{ route('admin.roles.index') }}"><i
                        class="fa fa-arrow-left"></i> {{ __('custom.back') }}</a>
                <table class="table table-bordered">
                    <tbody>
                        <tr>
                            <td>{{ __('custom.role_name') }}</td>
                            <td>{{ $role->name }}</td>
                        </tr>
                        <tr>
                            <td>{{ __('custom.permissions') }}</td>
                            <td>
                                <ul class="list-group">
                                    @foreach($role->permissions as $permission)
                                    <li class="list-group-item">{{ $permission->name }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection