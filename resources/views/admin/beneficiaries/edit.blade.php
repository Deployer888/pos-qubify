@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.beneficiaries.index') }}">Beneficiaries</a></li>
                <li class="breadcrumb-item active">Edit Beneficiary</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Edit Beneficiary</h4>
                <p class="text-muted mb-4">Update beneficiary information and depot assignment</p>

                <form action="{{ route('admin.beneficiaries.update', $beneficiary->id) }}" method="POST" class="mt-4">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <!-- Depot Selection -->
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="depot_id">Select Depot <span class="text-danger">*</span></label>
                                <select class="form-control @error('depot_id') is-invalid @enderror" 
                                        id="depot_id" 
                                        name="depot_id" 
                                        required>
                                    <option value="">Choose Depot...</option>
                                    @foreach($depots as $depot)
                                        <option value="{{ $depot->id }}" 
                                                {{ (old('depot_id', $beneficiary->depot_id) == $depot->id) ? 'selected' : '' }}>
                                            {{ $depot->depot_type }} - {{ $depot->city }}, {{ $depot->state }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('depot_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                @if($beneficiary->depot)
                                <small class="form-text text-muted">
                                    Current depot: {{ $beneficiary->depot->depot_type }} - {{ $beneficiary->depot->city }}, {{ $beneficiary->depot->state }}
                                </small>
                                @endif
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="family_id">Family ID <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('family_id') is-invalid @enderror" 
                                       id="family_id" 
                                       name="family_id" 
                                       value="{{ old('family_id', $beneficiary->family_id) }}" 
                                       required>
                                @error('family_id')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="adhaar_no">Aadhaar Number <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('adhaar_no') is-invalid @enderror" 
                                       id="adhaar_no" 
                                       name="adhaar_no" 
                                       value="{{ old('adhaar_no', $beneficiary->adhaar_no) }}"
                                       pattern="[0-9]{12}"
                                       maxlength="12"
                                       required>
                                @error('adhaar_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="ration_card_no">Ration Card Number <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('ration_card_no') is-invalid @enderror" 
                                       id="ration_card_no" 
                                       name="ration_card_no" 
                                       value="{{ old('ration_card_no', $beneficiary->ration_card_no) }}" 
                                       required>
                                @error('ration_card_no')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="card_range">Card Range <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('card_range') is-invalid @enderror" 
                                       id="card_range" 
                                       name="card_range" 
                                       value="{{ old('card_range', $beneficiary->card_range) }}" 
                                       required>
                                <small class="form-text text-muted">E.g., BPL, APL, etc.</small>
                                @error('card_range')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Full Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $beneficiary->name) }}" 
                                       required>
                                @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="mobile">Mobile Number</label>
                                <input type="text" 
                                       class="form-control @error('mobile') is-invalid @enderror" 
                                       id="mobile" 
                                       name="mobile" 
                                       value="{{ old('mobile', $beneficiary->mobile) }}"
                                       pattern="[0-9]{10}"
                                       maxlength="10">
                                @error('mobile')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="age">Age <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('age') is-invalid @enderror" 
                                       id="age" 
                                       name="age" 
                                       value="{{ old('age', $beneficiary->age) }}"
                                       min="0"
                                       max="150"
                                       required>
                                @error('age')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="is_family_head">Family Head?</label>
                                <div class="custom-control custom-switch mt-2">
                                    <input type="checkbox" 
                                           class="custom-control-input" 
                                           id="is_family_head" 
                                           name="is_family_head" 
                                           value="1"
                                           {{ old('is_family_head', $beneficiary->is_family_head) ? 'checked' : '' }}>
                                    <label class="custom-control-label" for="is_family_head">Yes</label>
                                </div>
                                @if($beneficiary->is_family_head)
                                <small class="form-text text-success">
                                    <i class="mdi mdi-crown"></i> Currently the family head
                                </small>
                                @endif
                                @error('is_family_head')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status <span class="text-danger">*</span></label>
                                <select class="form-control @error('status') is-invalid @enderror" 
                                        id="status" 
                                        name="status" 
                                        required>
                                    <option value="active" {{ old('status', $beneficiary->status) === 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status', $beneficiary->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                                @error('status')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="address">Address <span class="text-danger">*</span></label>
                                <textarea class="form-control @error('address') is-invalid @enderror" 
                                          id="address" 
                                          name="address" 
                                          rows="3" 
                                          required>{{ old('address', $beneficiary->address) }}</textarea>
                                @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            <i class="mdi mdi-content-save me-2"></i>Update Beneficiary
                        </button>
                        <a href="{{ route('admin.beneficiaries.index') }}" class="btn btn-secondary">
                            <i class="mdi mdi-arrow-left me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Store original depot ID for change detection
    const originalDepotId = '{{ $beneficiary->depot_id }}';
    
    // Depot change warning
    $('#depot_id').on('change', function() {
        const newDepotId = $(this).val();
        if (originalDepotId && newDepotId && originalDepotId !== newDepotId) {
            toastr.warning('Changing depot will affect family head relationships. Please review family head status.');
        }
    });
    
    // Form validation
    $('form').on('submit', function(e) {
        let isValid = true;
        
        // Check required fields
        $('input[required], select[required], textarea[required]').each(function() {
            if (!$(this).val()) {
                isValid = false;
                $(this).addClass('is-invalid');
            } else {
                $(this).removeClass('is-invalid');
            }
        });
        
        // Validate Aadhaar number
        const adhaarNo = $('#adhaar_no').val();
        if (adhaarNo && (adhaarNo.length !== 12 || !/^\d{12}$/.test(adhaarNo))) {
            $('#adhaar_no').addClass('is-invalid');
            isValid = false;
        }
        
        // Validate mobile number
        const mobile = $('#mobile').val();
        if (mobile && (mobile.length !== 10 || !/^\d{10}$/.test(mobile))) {
            $('#mobile').addClass('is-invalid');
            isValid = false;
        }
        
        if (!isValid) {
            e.preventDefault();
            toastr.error('Please fill all required fields correctly.');
        }
    });
    
    // Real-time validation
    $('#adhaar_no').on('input', function() {
        const value = $(this).val();
        if (value && (value.length !== 12 || !/^\d{12}$/.test(value))) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
    
    $('#mobile').on('input', function() {
        const value = $(this).val();
        if (value && (value.length !== 10 || !/^\d{10}$/.test(value))) {
            $(this).addClass('is-invalid');
        } else {
            $(this).removeClass('is-invalid');
        }
    });
});
</script>
@endsection