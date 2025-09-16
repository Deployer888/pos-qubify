@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.depots.index') }}">Depots</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.depots.customers.index', $depot) }}">Customers</a></li>
                <li class="breadcrumb-item active">Import Customers</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">Import Customers - {{ $depot->depot_type }}</h4>

                <div class="row mt-4">
                    <div class="col-md-6">
                        <div class="alert alert-info">
                            <h5 class="alert-heading">Import Instructions</h5>
                            <p>Please ensure your Excel/CSV file has the following columns:</p>
                            <ul class="mb-0">
                                <li>family_id (required)</li>
                                <li>adhaar_no (required, 12 digits)</li>
                                <li>ration_card_no (required)</li>
                                <li>card_range (required)</li>
                                <li>name (required)</li>
                                <li>mobile (optional)</li>
                                <li>age (required)</li>
                                <li>is_family_head (optional, use 1 for yes, 0 for no)</li>
                                <li>address (required)</li>
                                <li>status (optional, use 'active' or 'inactive')</li>
                            </ul>
                        </div>

                        <form action="{{ route('admin.depots.customers.import', $depot) }}" 
                              method="POST" 
                              enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file">Select File <span class="text-danger">*</span></label>
                                <input type="file" 
                                       class="form-control-file @error('file') is-invalid @enderror" 
                                       id="file" 
                                       name="file"
                                       accept=".xlsx,.csv"
                                       required>
                                @error('file')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                                <small class="form-text text-muted">
                                    Accepted formats: .xlsx, .csv
                                </small>
                            </div>

                            <div class="form-group mt-4">
                                <button type="submit" class="btn btn-primary">
                                    <i class="mdi mdi-upload mr-1"></i> Import Customers
                                </button>
                                <a href="{{ route('admin.depots.customers.index', $depot) }}" class="btn btn-secondary">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>

                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title">Sample Format</h5>
                                <p>Download a sample file to see the required format:</p>
                                <table class="table table-sm table-bordered">
                                    <thead>
                                        <tr>
                                            <th>family_id</th>
                                            <th>name</th>
                                            <th>adhaar_no</th>
                                            <th>...</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>F001</td>
                                            <td>John Doe</td>
                                            <td>123456789012</td>
                                            <td>...</td>
                                        </tr>
                                        <tr>
                                            <td>F001</td>
                                            <td>Jane Doe</td>
                                            <td>987654321012</td>
                                            <td>...</td>
                                        </tr>
                                    </tbody>
                                </table>
                                <a href="#" class="btn btn-sm btn-success mt-2">
                                    <i class="mdi mdi-download mr-1"></i> Download Sample
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
