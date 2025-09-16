@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.depots.index') }}">Depots</a></li>
                <li class="breadcrumb-item active">Create Depot</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.depots.store') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="depot_type">Depot Type <span class="text-danger">*</span></label>
                                <select class="form-control @error('depot_type') is-invalid @enderror" 
                                        id="depot_type" 
                                        name="depot_type" 
                                        required>
                                    <option value="">Select Type</option>
                                    <option value="Ward" {{ old('depot_type') === 'Ward' ? 'selected' : '' }}>
                                        Ward
                                    </option>
                                    <option value="Panchayat" {{ old('depot_type') === 'Panchayat' ? 'selected' : '' }}>
                                        Panchayat
                                    </option>
                                </select>
                                @error('depot_type')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="user_id">Depot Manager <span class="text-danger">*</span></label>
                                <select class="form-control @error('user_id') is-invalid @enderror" 
                                        id="user_id" 
                                        name="user_id" 
                                        required>
                                    <option value="">Select Manager</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                            {{ $user->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="active" {{ old('status') === 'active' ? 'selected' : '' }}>
                                        Active
                                    </option>
                                    <option value="inactive" {{ old('status') === 'inactive' ? 'selected' : '' }}>
                                        Inactive
                                    </option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="state">State <span class="text-danger">*</span></label>
                                <select class="form-control @error('state') is-invalid @enderror" 
                                        id="state" 
                                        name="state" 
                                        required>
                                    <option value="">Select State</option>
                                    @foreach($states as $state)
                                        <option value="{{ $state->id }}" {{ old('state') == $state->id ? 'selected' : '' }}>
                                            {{ $state->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('state')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="city">City <span class="text-danger">*</span></label>
                                <select class="form-control @error('city') is-invalid @enderror" 
                                        id="city" 
                                        name="city" 
                                        required>
                                    <option value="">Select City</option>
                                </select>
                                @error('city')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        
@push('script')
<script>
$(document).ready(function() {
    $('#state').on('change', function() {
        var stateId = $(this).val();
        if (stateId) {
            $.ajax({
                url: '{{ route("admin.depots.get-cities", "") }}/' + stateId,
                type: 'GET',
                dataType: 'json',
                success: function(data) {
                    $('#city').empty();
                    $('#city').append('<option value="">Select City</option>');
                    $.each(data, function(key, value) {
                        $('#city').append('<option value="' + value.name + '">' + value.name + '</option>');
                    });
                }
            });
        } else {
            $('#city').empty();
            $('#city').append('<option value="">Select City</option>');
        }
    });
});
</script>
@endpush

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          required>{{ old('address') }}</textarea>
                                @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            Create Depot
                        </button>
                        <a href="{{ route('admin.depots.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
