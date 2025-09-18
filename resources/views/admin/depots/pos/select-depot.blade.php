@extends('admin.layouts.master')

@section('content')
<div class="page-container">
    <!-- Error Messages -->
    @if ($errors->any())
    <div class="alert alert-danger alert-modern mb-4" role="alert">
        <div class="alert-content">
            <div class="alert-icon">
                <i class="bi bi-exclamation-triangle-fill"></i>
            </div>
            <div class="alert-body">
                <h6 class="alert-title">Please fix the following errors:</h6>
                <ul class="error-list">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Page Header -->
    <div class="page-header">
        <div class="header-content">
            <div class="header-main">
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-house-door"></i>
                                <span>Dashboard</span>
                            </a>
                        </li>
                        <li class="breadcrumb-item">
                            <a href="{{ route('admin.depots.index') }}">Depots</a>
                        </li>
                        <li class="breadcrumb-item active">POS Access</li>
                    </ol>
                </nav>
                <div class="page-title">
                    <h1>Point of Sale Access</h1>
                    <p class="page-subtitle">Select a depot to access the POS system</p>
                </div>
            </div>
            <div class="header-actions">
                <a href="{{ route('admin.depots.index') }}" class="btn btn-secondary btn-modern">
                    <i class="bi bi-arrow-left"></i>
                    <span>Back to Depots</span>
                </a>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="content-container">
            
            @if(auth()->user()->hasRole(['Super Admin', 'Admin']))
                <!-- Super Admin/Admin View -->
                <div class="pos-card card-elevated">
                    <div class="card-header">
                        <div class="header-info">
                            <div class="header-text">
                                <h2 class="card-title">Select Depot</h2>
                                <p class="card-subtitle">Choose a depot to access Point of Sale functionality</p>
                            </div>
                            <div class="role-badge badge-primary">
                                <i class="bi bi-person-gear"></i>
                                <span>{{ auth()->user()->getRoleNames()->first() }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <form action="{{ route('admin.depots.pos.index', ['depot' => '__DEPOT_ID__']) }}" method="GET" id="depot-selection-form" class="selection-form">
                            @csrf
                            
                            <div class="form-section">
                                <label for="depot-select" class="form-label">
                                    <i class="bi bi-building"></i>
                                    <span>Available Depots</span>
                                </label>
                                <div class="select-wrapper">
                                    <select class="form-select select-modern" id="depot-select" name="depot_id" required>
                                        <option value="">Select a depot to continue...</option>
                                        @foreach($depots as $depot)
                                        <option value="{{ $depot->id }}" 
                                                data-type="{{ $depot->depot_type }}"
                                                data-city="{{ $depot->city }}"
                                                data-state="{{ $depot->statename->name ?? 'N/A' }}"
                                                data-manager="{{ $depot->user->name ?? 'No Manager' }}">
                                            {{ $depot->depot_type }} - {{ $depot->city }}, {{ $depot->statename->name ?? 'N/A' }}
                                        </option>
                                        @endforeach
                                    </select>
                                    <div class="select-icon">
                                        <i class="bi bi-chevron-down"></i>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Depot Preview -->
                            <div id="depot-preview" class="depot-preview" style="display: none;">
                                <div class="preview-card card-glass">
                                    <div class="preview-content">
                                        <div class="preview-icon">
                                            <div class="icon-circle icon-primary">
                                                <i class="bi bi-shop"></i>
                                            </div>
                                        </div>
                                        <div class="preview-info">
                                            <h3 id="preview-name" class="preview-title"></h3>
                                            <div class="preview-details">
                                                <div class="detail-item">
                                                    <i class="bi bi-geo-alt"></i>
                                                    <span id="preview-location"></span>
                                                </div>
                                                <div class="detail-item" id="manager-info">
                                                    <i class="bi bi-person"></i>
                                                    <span>Manager: <span id="preview-manager"></span></span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="preview-status">
                                            <div class="status-indicator status-active">
                                                <span class="status-dot"></span>
                                                <span>Available</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="submit" class="btn btn-primary btn-hero" id="access-pos-btn" disabled>
                                    <div class="btn-content">
                                        <i class="bi bi-cart3"></i>
                                        <span>Access POS System</span>
                                    </div>
                                    <div class="btn-ripple"></div>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

            @elseif(auth()->user()->hasRole('Depot Manager'))
                <!-- Depot Manager View -->
                <div class="pos-card card-elevated">
                    <div class="card-header">
                        <div class="header-info">
                            <div class="header-text">
                                <h2 class="card-title">Your Depot Access</h2>
                                <p class="card-subtitle">Access POS for your assigned depot(s)</p>
                            </div>
                            <div class="role-badge badge-success">
                                <i class="bi bi-person-check"></i>
                                <span>Depot Manager</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        @if($depots->count() > 1)
                            <!-- Multiple Depots -->
                            <form action="{{ route('admin.depots.pos.index', ['depot' => '__DEPOT_ID__']) }}" method="GET" id="depot-selection-form" class="selection-form">
                                @csrf
                                
                                <div class="form-section">
                                    <label for="depot-select" class="form-label">
                                        <i class="bi bi-building"></i>
                                        <span>Your Assigned Depots</span>
                                    </label>
                                    <div class="select-wrapper">
                                        <select class="form-select select-modern" id="depot-select" name="depot_id" required>
                                            <option value="">Select from your assigned depots...</option>
                                            @foreach($depots as $depot)
                                            <option value="{{ $depot->id }}" 
                                                    data-type="{{ $depot->depot_type }}"
                                                    data-city="{{ $depot->city }}"
                                                    data-state="{{ $depot->statename->name ?? 'N/A' }}">
                                                {{ $depot->depot_type }} - {{ $depot->city }}, {{ $depot->statename->name ?? 'N/A' }}
                                            </option>
                                            @endforeach
                                        </select>
                                        <div class="select-icon">
                                            <i class="bi bi-chevron-down"></i>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Depot Preview -->
                                <div id="depot-preview" class="depot-preview" style="display: none;">
                                    <div class="preview-card card-glass">
                                        <div class="preview-content">
                                            <div class="preview-icon">
                                                <div class="icon-circle icon-success">
                                                    <i class="bi bi-shop"></i>
                                                </div>
                                            </div>
                                            <div class="preview-info">
                                                <h3 id="preview-name" class="preview-title"></h3>
                                                <div class="preview-details">
                                                    <div class="detail-item">
                                                        <i class="bi bi-geo-alt"></i>
                                                        <span id="preview-location"></span>
                                                    </div>
                                                    <div class="detail-item">
                                                        <i class="bi bi-check-circle"></i>
                                                        <span>Your assigned depot</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="preview-status">
                                                <div class="status-indicator status-active">
                                                    <span class="status-dot"></span>
                                                    <span>Available</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <button type="submit" class="btn btn-success btn-hero" id="access-pos-btn" disabled>
                                        <div class="btn-content">
                                            <i class="bi bi-cart3"></i>
                                            <span>Access POS System</span>
                                        </div>
                                        <div class="btn-ripple"></div>
                                    </button>
                                </div>
                            </form>
                        @else
                            <!-- Single Depot -->
                            @php $depot = $depots->first(); @endphp
                            <div class="single-depot-access">
                                <div class="depot-showcase">
                                    <div class="showcase-icon">
                                        <div class="icon-circle icon-success icon-large">
                                            <i class="bi bi-shop"></i>
                                        </div>
                                    </div>
                                    <div class="showcase-info">
                                        <h3 class="showcase-title">{{ $depot->depot_type }}</h3>
                                        <div class="showcase-details">
                                            <div class="detail-item">
                                                <i class="bi bi-geo-alt"></i>
                                                <span>{{ $depot->city }}, {{ $depot->statename->name ?? 'N/A' }}</span>
                                            </div>
                                            <div class="detail-item">
                                                <i class="bi bi-check-circle"></i>
                                                <span>Your assigned depot</span>
                                            </div>
                                        </div>
                                        <div class="showcase-status">
                                            <div class="status-indicator status-active">
                                                <span class="status-dot"></span>
                                                <span>Ready for POS access</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-actions">
                                    <a href="{{ route('admin.depots.pos.index', $depot) }}" class="btn btn-success btn-hero">
                                        <div class="btn-content">
                                            <i class="bi bi-cart3"></i>
                                            <span>Access POS System</span>
                                        </div>
                                        <div class="btn-ripple"></div>
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>

            @else
                <!-- Unauthorized Access -->
                <div class="pos-card card-elevated">
                    <div class="card-body text-center">
                        <div class="access-denied">
                            <div class="denied-icon">
                                <div class="icon-circle icon-danger icon-large">
                                    <i class="bi bi-shield-exclamation"></i>
                                </div>
                            </div>
                            <div class="denied-content">
                                <h3 class="denied-title">Access Denied</h3>
                                <p class="denied-message">You do not have permission to access the POS system.</p>
                                <div class="denied-actions">
                                    <a href="{{ route('admin.depots.index') }}" class="btn btn-secondary btn-modern">
                                        <i class="bi bi-arrow-left"></i>
                                        <span>Back to Depots</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* Modern Design System */
:root {
    --primary: #4f46e5;
    --primary-light: #6366f1;
    --primary-dark: #4338ca;
    --success: #10b981;
    --success-light: #34d399;
    --success-dark: #059669;
    --danger: #ef4444;
    --warning: #f59e0b;
    --info: #3b82f6;
    
    --gray-50: #f9fafb;
    --gray-100: #f3f4f6;
    --gray-200: #e5e7eb;
    --gray-300: #d1d5db;
    --gray-400: #9ca3af;
    --gray-500: #6b7280;
    --gray-600: #4b5563;
    --gray-700: #374151;
    --gray-800: #1f2937;
    --gray-900: #111827;
    
    --white: #ffffff;
    --black: #000000;
    
    /* Shadows */
    --shadow-xs: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    --shadow-sm: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px -1px rgba(0, 0, 0, 0.1);
    --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -4px rgba(0, 0, 0, 0.1);
    --shadow-xl: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1);
    
    /* Border radius */
    --radius-sm: 6px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --radius-xl: 16px;
    --radius-2xl: 24px;
    
    /* Transitions */
    --transition-fast: 0.15s ease;
    --transition-base: 0.2s ease;
    --transition-slow: 0.3s ease;
    
    /* Gradients */
    --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    --gradient-success: linear-gradient(135deg, var(--success) 0%, var(--success-light) 100%);
    --gradient-glass: linear-gradient(135deg, rgba(255, 255, 255, 0.1) 0%, rgba(255, 255, 255, 0.05) 100%);
}

/* Base Styles */
body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
    line-height: 1.6;
    color: var(--gray-700);
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    min-height: 100vh;
}

/* Page Container */
.page-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem 1.5rem;
}

/* Page Header */
.page-header {
    margin-bottom: 3rem;
}

.header-content {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2rem;
}

.breadcrumb-nav .breadcrumb {
    background: none;
    padding: 0;
    margin: 0 0 1rem 0;
    font-size: 0.875rem;
}

.breadcrumb-item {
    display: flex;
    align-items: center;
}

.breadcrumb-item a {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-500);
    text-decoration: none;
    transition: var(--transition-base);
    padding: 0.25rem 0;
}

.breadcrumb-item a:hover {
    color: var(--primary);
}

.breadcrumb-item.active {
    color: var(--gray-700);
    font-weight: 500;
}

.page-title h1 {
    font-size: 2.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.5rem 0;
    line-height: 1.2;
}

.page-subtitle {
    font-size: 1.125rem;
    color: var(--gray-600);
    margin: 0;
}

/* Main Content */
.main-content {
    display: flex;
    justify-content: center;
}

.content-container {
    width: 100%;
    max-width: 600px;
}

/* Cards */
.pos-card {
    background: var(--white);
    border-radius: var(--radius-2xl);
    overflow: hidden;
    border: 1px solid var(--gray-200);
    transition: var(--transition-slow);
}

.card-elevated {
    box-shadow: var(--shadow-lg);
}

.card-elevated:hover {
    box-shadow: var(--shadow-xl);
    transform: translateY(-2px);
}

.card-header {
    padding: 2.5rem 2.5rem 0;
}

.header-info {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: 1.5rem;
}

.card-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.5rem 0;
}

.card-subtitle {
    color: var(--gray-600);
    margin: 0;
    font-size: 1rem;
}

.card-body {
    padding: 2.5rem;
}

/* Role Badges */
.role-badge {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.75rem 1rem;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 0.875rem;
    white-space: nowrap;
}

.badge-primary {
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-light) 100%);
    color: var(--white);
    box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
}

.badge-success {
    background: linear-gradient(135deg, var(--success) 0%, var(--success-light) 100%);
    color: var(--white);
    box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
}

/* Form Sections */
.selection-form {
    display: flex;
    flex-direction: column;
    gap: 2rem;
}

.form-section {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.form-label {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    font-weight: 600;
    font-size: 1rem;
    color: var(--gray-800);
    margin: 0;
}

.form-label i {
    color: var(--primary);
    font-size: 1.125rem;
}

/* Select Wrapper */
.select-wrapper {
    position: relative;
}

.select-modern {
    width: 100%;
    padding: 1rem 3rem 1rem 1rem;
    border: 2px solid var(--gray-300);
    border-radius: var(--radius-lg);
    font-size: 1rem;
    background: var(--white);
    color: var(--gray-700);
    transition: var(--transition-base);
    appearance: none;
    cursor: pointer;
}

.select-modern:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.1);
}

.select-icon {
    position: absolute;
    right: 1rem;
    top: 50%;
    transform: translateY(-50%);
    color: var(--gray-400);
    pointer-events: none;
    transition: var(--transition-base);
}

.select-wrapper:hover .select-icon {
    color: var(--gray-600);
}

/* Depot Preview */
.depot-preview {
    animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
}

@keyframes slideUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card-glass {
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(20px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-xl);
    box-shadow: var(--shadow-md);
}

.preview-content {
    padding: 2rem;
    display: flex;
    align-items: center;
    gap: 1.5rem;
}

.preview-icon {
    flex-shrink: 0;
}

.icon-circle {
    width: 64px;
    height: 64px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    box-shadow: var(--shadow-md);
}

.icon-circle.icon-large {
    width: 80px;
    height: 80px;
    font-size: 2rem;
}

.icon-primary {
    background: var(--gradient-primary);
    color: var(--white);
}

.icon-success {
    background: var(--gradient-success);
    color: var(--white);
}

.icon-danger {
    background: linear-gradient(135deg, var(--danger) 0%, #f87171 100%);
    color: var(--white);
}

.preview-info {
    flex: 1;
    min-width: 0;
}

.preview-title {
    font-size: 1.25rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 0.75rem 0;
}

.preview-details {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

.detail-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    font-size: 0.875rem;
}

.detail-item i {
    color: var(--gray-400);
    width: 16px;
    flex-shrink: 0;
}

.preview-status {
    flex-shrink: 0;
}

.status-indicator {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.5rem 0.75rem;
    border-radius: var(--radius-md);
    font-size: 0.875rem;
    font-weight: 500;
}

.status-active {
    background: rgba(16, 185, 129, 0.1);
    color: var(--success-dark);
}

.status-dot {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: currentColor;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        opacity: 1;
    }
    50% {
        opacity: 0.5;
    }
}

/* Single Depot Showcase */
.single-depot-access {
    display: flex;
    flex-direction: column;
    gap: 2rem;
    text-align: center;
}

.depot-showcase {
    background: var(--gradient-glass);
    border: 1px solid rgba(255, 255, 255, 0.2);
    border-radius: var(--radius-xl);
    padding: 3rem 2rem;
    backdrop-filter: blur(20px);
}

.showcase-icon {
    margin-bottom: 1.5rem;
}

.showcase-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--gray-900);
    margin: 0 0 1rem 0;
}

.showcase-details {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.75rem;
    margin-bottom: 1.5rem;
}

.showcase-details .detail-item {
    justify-content: center;
    font-size: 1rem;
    color: var(--gray-600);
}

.showcase-status {
    display: flex;
    justify-content: center;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 0.75rem;
    padding: 0.75rem 1.5rem;
    border: 2px solid transparent;
    border-radius: var(--radius-lg);
    font-weight: 600;
    font-size: 1rem;
    text-decoration: none;
    transition: var(--transition-base);
    cursor: pointer;
    position: relative;
    overflow: hidden;
}

.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
    transform: none !important;
}

.btn-modern {
    background: var(--white);
    color: var(--gray-700);
    border-color: var(--gray-300);
    box-shadow: var(--shadow-sm);
}

.btn-modern:hover:not(:disabled) {
    background: var(--gray-50);
    border-color: var(--gray-400);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-hero {
    padding: 1.25rem 2.5rem;
    font-size: 1.125rem;
    border-radius: var(--radius-xl);
    font-weight: 700;
    box-shadow: var(--shadow-lg);
    position: relative;
    overflow: hidden;
}

.btn-primary {
    background: var(--gradient-primary);
    color: var(--white);
    border: none;
}

.btn-primary:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--primary-dark) 0%, var(--primary) 100%);
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.3), var(--shadow-xl);
}

.btn-success {
    background: var(--gradient-success);
    color: var(--white);
    border: none;
}

.btn-success:hover:not(:disabled) {
    background: linear-gradient(135deg, var(--success-dark) 0%, var(--success) 100%);
    transform: translateY(-2px);
    box-shadow: 0 20px 25px -5px rgba(16, 185, 129, 0.3), var(--shadow-xl);
}

.btn-secondary {
    background: var(--gray-100);
    color: var(--gray-700);
    border-color: var(--gray-300);
}

.btn-secondary:hover:not(:disabled) {
    background: var(--gray-200);
    transform: translateY(-1px);
    box-shadow: var(--shadow-md);
}

.btn-content {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    z-index: 1;
    position: relative;
}

.btn-ripple {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.1);
    transform: scale(0);
    border-radius: inherit;
    transition: transform 0.5s ease;
}

.btn:active .btn-ripple {
    transform: scale(1);
}

.form-actions {
    display: flex;
    justify-content: center;
    margin-top: 1rem;
}

/* Loading State */
.btn.loading .btn-content {
    opacity: 0;
}

.btn.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    width: 20px;
    height: 20px;
    margin-left: -10px;
    margin-top: -10px;
    border: 2px solid transparent;
    border-top: 2px solid currentColor;
    border-radius: 50%;
    animation: spin 0.8s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

/* Alerts */
.alert-modern {
    border-radius: var(--radius-lg);
    border: 1px solid;
    box-shadow: var(--shadow-sm);
    overflow: hidden;
}

.alert-danger {
    background: linear-gradient(135deg, #fef2f2 0%, #fee2e2 100%);
    border-color: #fecaca;
    color: var(--danger);
}

.alert-content {
    padding: 1.5rem;
    display: flex;
    align-items: flex-start;
    gap: 1rem;
}

.alert-icon {
    flex-shrink: 0;
    font-size: 1.25rem;
}

.alert-body {
    flex: 1;
}

.alert-title {
    font-weight: 600;
    margin: 0 0 0.5rem 0;
    color: inherit;
}

.error-list {
    margin: 0;
    padding-left: 1.25rem;
}

.error-list li {
    color: var(--gray-600);
    font-size: 0.9rem;
}

/* Access Denied */
.access-denied {
    padding: 3rem 2rem;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 2rem;
}

.denied-content {
    text-align: center;
}

.denied-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: var(--danger);
    margin: 0 0 1rem 0;
}

.denied-message {
    color: var(--gray-600);
    margin: 0 0 2rem 0;
    font-size: 1.1rem;
}

/* Responsive Design */
@media (max-width: 768px) {
    .page-container {
        padding: 1.5rem 1rem;
    }
    
    .header-content {
        flex-direction: column;
        align-items: flex-start;
        gap: 1.5rem;
    }
    
    .header-info {
        flex-direction: column;
        align-items: flex-start;
        gap: 1rem;
    }
    
    .card-header,
    .card-body {
        padding: 2rem 1.5rem;
    }
    
    .preview-content {
        flex-direction: column;
        text-align: center;
        padding: 1.5rem;
    }
    
    .depot-showcase {
        padding: 2rem 1.5rem;
    }
    
    .btn-hero {
        width: 100%;
        padding: 1rem 2rem;
    }
    
    .page-title h1 {
        font-size: 1.875rem;
    }
    
    .showcase-details {
        align-items: flex-start;
        text-align: left;
    }
    
    .showcase-details .detail-item {
        justify-content: flex-start;
    }
}

@media (max-width: 480px) {
    .page-container {
        padding: 1rem 0.75rem;
    }
    
    .card-header,
    .card-body {
        padding: 1.5rem 1rem;
    }
    
    .role-badge {
        padding: 0.5rem 0.75rem;
        font-size: 0.75rem;
    }
    
    .btn-hero {
        padding: 0.875rem 1.5rem;
        font-size: 1rem;
    }
}

/* Focus States for Accessibility */
.select-modern:focus,
.btn:focus {
    outline: 2px solid var(--primary);
    outline-offset: 2px;
}

/* High contrast mode support */
@media (prefers-contrast: high) {
    .card-glass {
        background: var(--white);
        backdrop-filter: none;
    }
    
    .depot-showcase {
        background: var(--white);
        backdrop-filter: none;
        border-color: var(--gray-300);
    }
}

/* Reduced motion support */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    const $depotSelect = $('#depot-select');
    const $previewCard = $('#depot-preview');
    const $submitBtn = $('#access-pos-btn');
    const $form = $('#depot-selection-form');
    
    // Enhanced depot selection with smooth animations
    $depotSelect.on('change', function() {
        const selectedOption = $(this).find('option:selected');
        const depotId = selectedOption.val();
        
        if (depotId && selectedOption.data('type')) {
            // Update depot preview
            updateDepotPreview(selectedOption.data());
            
            // Show preview with animation
            $previewCard.stop(true, false).slideDown(400, 'swing');
            
            // Enable submit button with enhanced animation
            $submitBtn.removeClass('btn-secondary')
                     .addClass('btn-primary btn-success')
                     .prop('disabled', false);
            
            // Update form action
            updateFormAction(depotId);
            
            // Add success feedback to select
            $depotSelect.addClass('select-success');
            
        } else {
            // Hide preview and disable button
            $previewCard.slideUp(300);
            $submitBtn.addClass('btn-secondary')
                     .removeClass('btn-primary btn-success')
                     .prop('disabled', true);
            
            // Remove success feedback
            $depotSelect.removeClass('select-success');
        }
    });
    
    // Enhanced form submission with loading states
    $form.on('submit', function(e) {
        if (!$depotSelect.val()) {
            e.preventDefault();
            
            // Add error state to select
            $depotSelect.addClass('select-error');
            setTimeout(() => $depotSelect.removeClass('select-error'), 3000);
            
            $depotSelect.focus();
            return false;
        }
        
        // Show loading state
        $submitBtn.addClass('loading').prop('disabled', true);
        $depotSelect.prop('disabled', true);
        
        // Add loading overlay to prevent interaction
        $('<div class="loading-overlay"></div>').appendTo($form.parent());
    });
    
    // Update depot preview function
    function updateDepotPreview(data) {
        $('#preview-name').text(data.type);
        $('#preview-location').text(data.city + ', ' + data.state);
        
        @if(auth()->user()->hasRole(['Super Admin', 'Admin']))
        $('#preview-manager').text(data.manager || 'No Manager');
        $('#manager-info').show();
        @else
        $('#manager-info').hide();
        @endif
    }
    
    // Update form action
    function updateFormAction(depotId) {
        const baseAction = $form.data('base-action') || $form.attr('action');
        const newAction = baseAction.replace('__DEPOT_ID__', depotId);
        $form.attr('action', newAction);
        
        if (!$form.data('base-action')) {
            $form.data('base-action', baseAction);
        }
    }
    
    // Enhanced keyboard navigation
    $(document).on('keydown', function(e) {
        // Enter key to submit when depot is selected
        if (e.key === 'Enter' && $depotSelect.val() && !$submitBtn.prop('disabled')) {
            e.preventDefault();
            $form.submit();
        }
        
        // Escape key to clear selection
        if (e.key === 'Escape' && $depotSelect.val()) {
            $depotSelect.val('').trigger('change');
        }
    });
    
    // Auto-focus enhancement
    setTimeout(() => {
        if (!$depotSelect.val()) {
            $depotSelect.focus();
        }
    }, 300);
    
    // Button ripple effect
    $('.btn').on('click', function(e) {
        const $btn = $(this);
        const $ripple = $btn.find('.btn-ripple');
        
        $ripple.removeClass('animate');
        
        setTimeout(() => {
            $ripple.addClass('animate');
        }, 0);
        
        setTimeout(() => {
            $ripple.removeClass('animate');
        }, 500);
    });
    
    // Enhanced select interactions
    $depotSelect.on('focus', function() {
        $(this).parent().addClass('select-focused');
    }).on('blur', function() {
        $(this).parent().removeClass('select-focused');
    });
    
    // Smooth scroll to preview when shown
    $previewCard.on('slideDownComplete', function() {
        $('html, body').animate({
            scrollTop: $(this).offset().top - 100
        }, 500);
    });
});
</script>

<style>
/* Additional interactive styles */
.select-success {
    border-color: var(--success) !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1) !important;
}

.select-error {
    border-color: var(--danger) !important;
    box-shadow: 0 0 0 3px rgba(239, 68, 68, 0.1) !important;
    animation: shake 0.5s ease-in-out;
}

@keyframes shake {
    0%, 100% { transform: translateX(0); }
    10%, 30%, 50%, 70%, 90% { transform: translateX(-3px); }
    20%, 40%, 60%, 80% { transform: translateX(3px); }
}

.select-focused {
    transform: scale(1.02);
    transition: var(--transition-base);
}

.loading-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: rgba(255, 255, 255, 0.9);
    backdrop-filter: blur(4px);
    z-index: 9999;
    display: flex;
    align-items: center;
    justify-content: center;
}

.btn-ripple.animate {
    animation: ripple 0.5s ease-out;
}

@keyframes ripple {
    0% {
        transform: scale(0);
        opacity: 1;
    }
    100% {
        transform: scale(1);
        opacity: 0;
    }
}
</style>
@endpush