@extends('admin.layouts.master')

@section('content')
<div class="page-title-box">
    <div class="row align-items-center">
        <div class="col-sm-6">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.depots.index') }}">Depots</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.depots.stocks.index', $depot) }}">Stocks</a></li>
                <li class="breadcrumb-item active">{{ $stock ? 'Update' : 'Add' }} Stock</li>
            </ol>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">
                <h4 class="mt-0 header-title">{{ $stock ? 'Update' : 'Add' }} Stock - {{ $depot->depot_type }}</h4>

                <form action="{{ route('admin.depots.stocks.update', $depot) }}" method="POST" class="mt-4">
                    @csrf
                    <input type="hidden" name="depot_id" value="{{ $depot->id }}">
                    @if($stock)
                        <input type="hidden" name="stock_id" value="{{ $stock->id }}">
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="product_name">Product Name <span class="text-danger">*</span></label>
                                <input type="text" 
                                       class="form-control @error('product_name') is-invalid @enderror" 
                                       id="product_name" 
                                       name="product_name" 
                                       value="{{ old('product_name', $stock->product_name ?? '') }}"
                                       {{ $stock ? 'readonly' : '' }}
                                       required>
                                @error('product_name')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="measurement_unit">Measurement Unit <span class="text-danger">*</span></label>
                                <select class="form-control @error('measurement_unit') is-invalid @enderror" 
                                        id="measurement_unit" 
                                        name="measurement_unit"
                                        {{ $stock ? 'readonly' : '' }}
                                        required>
                                    @foreach(['Kg', 'Ltr', 'Piece'] as $unit)
                                        <option value="{{ $unit }}" 
                                            {{ old('measurement_unit', $stock->measurement_unit ?? '') === $unit ? 'selected' : '' }}>
                                            {{ $unit }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('measurement_unit')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if($stock)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Current Stock</label>
                                    <input type="text" 
                                           class="form-control" 
                                           value="{{ number_format($stock->current_stock, 2) }} {{ $stock->measurement_unit }}"
                                           readonly>
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quantity">Quantity to {{ $stock ? 'Adjust' : 'Add' }} <span class="text-danger">*</span></label>
                                <input type="number" 
                                       class="form-control @error('quantity') is-invalid @enderror" 
                                       id="quantity" 
                                       name="quantity" 
                                       value="{{ old('quantity') }}"
                                       step="0.01"
                                       min="0"
                                       placeholder="Enter quantity in {{ $stock ? $stock->measurement_unit : 'selected unit' }}"
                                       required>
                                <small class="form-text text-muted">
                                    @if($stock)
                                        For subtraction, enter the amount you want to remove
                                    @else
                                        Enter the initial stock quantity
                                    @endif
                                </small>
                                @error('quantity')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        @if($stock)
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="adjustment_type">Adjustment Type <span class="text-danger">*</span></label>
                                    <select class="form-control @error('adjustment_type') is-invalid @enderror" 
                                            id="adjustment_type" 
                                            name="adjustment_type"
                                            required>
                                        <option value="">Select adjustment type</option>
                                        <option value="Add" {{ old('adjustment_type', 'Add') === 'Add' ? 'selected' : '' }}>Add to Stock</option>
                                        <option value="Subtract" {{ old('adjustment_type') === 'Subtract' ? 'selected' : '' }}>Remove from Stock</option>
                                    </select>
                                    <small class="form-text text-muted">
                                        Choose 'Add to Stock' to increase quantity or 'Remove from Stock' to decrease
                                    </small>
                                    @error('adjustment_type')
                                    <span class="invalid-feedback">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="price">Wholesale Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₹</span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $stock->price ?? '') }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="Enter wholesale price"
                                           required>
                                </div>
                                <small class="form-text text-muted">Price for bulk/wholesale purchases</small>
                                @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="customer_price">Retail Price <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">₹</span>
                                    </div>
                                    <input type="number" 
                                           class="form-control @error('customer_price') is-invalid @enderror" 
                                           id="customer_price" 
                                           name="customer_price" 
                                           value="{{ old('customer_price', $stock->customer_price ?? '') }}"
                                           step="0.01"
                                           min="0"
                                           placeholder="Enter retail price"
                                           required>
                                </div>
                                <small class="form-text text-muted">Price for individual/retail customers</small>
                                @error('customer_price')
                                <span class="invalid-feedback">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">
                            {{ $stock ? 'Update' : 'Add' }} Stock
                        </button>
                        <a href="{{ route('admin.depots.stocks.index', $depot) }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
