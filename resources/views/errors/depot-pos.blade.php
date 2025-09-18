@extends('admin.layouts.master')

@section('title', 'Depot POS Error')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-exclamation-triangle"></i>
                        Depot POS System Error
                    </h4>
                </div>
                <div class="card-body text-center">
                    <div class="error-icon mb-4">
                        <i class="fas fa-cash-register fa-5x text-muted"></i>
                    </div>
                    
                    <h2 class="text-danger mb-3">Error {{ $code ?? 500 }}</h2>
                    
                    <p class="lead mb-4">
                        {{ $message ?? 'An unexpected error occurred in the POS system.' }}
                    </p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i>
                        If this error persists, please contact your system administrator or try the following:
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-redo text-primary"></i>
                                        Try Again
                                    </h5>
                                    <p class="card-text">Refresh the page or try your action again.</p>
                                    <a href="javascript:history.back()" class="btn btn-primary">
                                        <i class="fas fa-arrow-left"></i> Go Back
                                    </a>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <div class="card bg-light">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <i class="fas fa-home text-success"></i>
                                        Start Over
                                    </h5>
                                    <p class="card-text">Return to depot selection and start fresh.</p>
                                    <a href="{{ route('admin.depots.pos.select') }}" class="btn btn-success">
                                        <i class="fas fa-store"></i> Select Depot
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4">
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-tachometer-alt"></i> Return to Dashboard
                        </a>
                    </div>
                </div>
                
                <div class="card-footer text-muted text-center">
                    <small>
                        <i class="fas fa-clock"></i>
                        Error occurred at {{ now()->format('Y-m-d H:i:s') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.error-icon {
    opacity: 0.3;
}

.card {
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

.card-body .card {
    transition: transform 0.2s;
}

.card-body .card:hover {
    transform: translateY(-2px);
}
</style>
@endsection