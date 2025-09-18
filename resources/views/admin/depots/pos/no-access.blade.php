@extends('admin.layouts.master')

@section('content')

<!-- Page Header -->
<div class="page-title-box mb-4">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-sm-6">
                <div class="page-title">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb bg-transparent p-0 mb-0">
                            <li class="breadcrumb-item">
                                <a href="#" class="text-muted">
                                    <i class="mdi mdi-home-outline"></i> Depots
                                </a>
                            </li>
                            <li class="breadcrumb-item active">POS Access</li>
                        </ol>
                    </nav>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="page-title-right d-flex justify-content-end">
                    <a href="{{ route('admin.depots.index') }}" 
                       class="btn btn-outline-secondary btn-rounded shadow-sm px-4 py-2">
                        <i class="mdi mdi-arrow-left me-2"></i> Back to Depots
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- No Access Content -->
<div class="row justify-content-center">
    <div class="col-lg-8 col-md-10">
        <div class="card border-0 shadow-lg">
            <div class="card-body text-center py-5 px-4">
                <!-- Icon Section -->
                <div class="no-access-icon mb-4">
                    <div class="avatar-xl mx-auto mb-3">
                        <div class="avatar-title rounded-circle bg-warning-subtle text-warning">
                            <i class="mdi mdi-warehouse-off font-size-48"></i>
                        </div>
                    </div>
                </div>
                
                <!-- Main Message -->
                <div class="no-access-content mb-4">
                    <h3 class="text-warning mb-3 font-weight-700">No Depot Access Available</h3>
                    <p class="text-muted font-size-16 mb-4 line-height-relaxed">
                        You are assigned as a Depot Manager, but currently have no depots assigned to your account. 
                        To access the POS system, you need to be assigned to at least one active depot.
                    </p>
                </div>
                
                <!-- Information Card -->
                <div class="info-card mb-4">
                    <div class="card bg-light border-0">
                        <div class="card-body p-4">
                            <div class="row align-items-center">
                                <div class="col-md-2 text-center mb-3 mb-md-0">
                                    <i class="mdi mdi-information-outline text-info font-size-32"></i>
                                </div>
                                <div class="col-md-10 text-md-start text-center">
                                    <h6 class="mb-2 font-weight-600 text-dark">What you can do:</h6>
                                    <ul class="list-unstyled mb-0 text-muted font-size-14">
                                        <li class="mb-1">
                                            <i class="mdi mdi-check-circle text-success me-2"></i>
                                            Contact your system administrator to assign you to a depot
                                        </li>
                                        <li class="mb-1">
                                            <i class="mdi mdi-check-circle text-success me-2"></i>
                                            Verify your role and permissions with the admin team
                                        </li>
                                        <li class="mb-0">
                                            <i class="mdi mdi-check-circle text-success me-2"></i>
                                            Check back later once depot assignment is completed
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Contact Information -->
                <div class="contact-section mb-4">
                    <div class="row">
                        <div class="col-md-6 mb-3 mb-md-0">
                            <div class="contact-card h-100">
                                <div class="card border-primary border-2 h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="contact-icon mb-3">
                                            <i class="mdi mdi-account-supervisor text-primary font-size-32"></i>
                                        </div>
                                        <h6 class="font-weight-600 text-primary mb-2">System Administrator</h6>
                                        <p class="text-muted font-size-13 mb-0">
                                            Contact your system admin for depot assignment and role verification
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="contact-card h-100">
                                <div class="card border-success border-2 h-100">
                                    <div class="card-body text-center p-4">
                                        <div class="contact-icon mb-3">
                                            <i class="mdi mdi-headset text-success font-size-32"></i>
                                        </div>
                                        <h6 class="font-weight-600 text-success mb-2">Technical Support</h6>
                                        <p class="text-muted font-size-13 mb-0">
                                            Get help with account setup and system access issues
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <div class="d-flex flex-column flex-sm-row gap-3 justify-content-center">
                        <a href="{{ route('admin.depots.index') }}" 
                           class="btn btn-outline-primary btn-lg px-4 py-2">
                            <i class="mdi mdi-view-list me-2"></i>
                            View All Depots
                        </a>
                        <button type="button" 
                                class="btn btn-primary btn-lg px-4 py-2"
                                onclick="refreshPage()">
                            <i class="mdi mdi-refresh me-2"></i>
                            Refresh Page
                        </button>
                    </div>
                </div>
                
                <!-- User Info -->
                <div class="user-info mt-4 pt-4 border-top">
                    <div class="d-flex align-items-center justify-content-center text-muted font-size-13">
                        <i class="mdi mdi-account-circle me-2"></i>
                        <span>Logged in as: <strong>{{ auth()->user()->name }}</strong></span>
                        <span class="mx-2">•</span>
                        <span>Role: <strong>{{ auth()->user()->getRoleNames()->first() }}</strong></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('style')
<style>
/* Professional Typography & Spacing */
.font-weight-600 { font-weight: 600; }
.font-weight-700 { font-weight: 700; }
.line-height-relaxed { line-height: 1.7; }

/* Enhanced Professional Cards */
.card {
    border-radius: 16px;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    border: 1px solid rgba(0,0,0,0.06);
    overflow: hidden;
}

.shadow-lg {
    box-shadow: 0 12px 28px rgba(0,0,0,0.15) !important;
}

/* Color Utilities */
.bg-warning-subtle { background-color: rgba(217, 119, 6, 0.1); }

/* Avatar Enhancements */
.avatar-xl { height: 5rem; width: 5rem; }

.avatar-title {
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100%;
    width: 100%;
    border-radius: 50%;
    font-weight: 500;
}

/* Page Header */
.page-title-box {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 12px;
    padding: 1.5rem;
    margin-bottom: 0;
}

.breadcrumb-item a {
    text-decoration: none;
    color: #64748b;
    transition: color 0.15s ease-in-out;
}

.breadcrumb-item a:hover {
    color: #667eea;
}

.breadcrumb-item.active {
    font-weight: 600;
    color: #334155;
}

/* No Access Specific Styles */
.no-access-icon {
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0%, 100% {
        transform: scale(1);
    }
    50% {
        transform: scale(1.05);
    }
}

.info-card {
    margin: 2rem 0;
}

.contact-card .card {
    transition: all 0.3s ease;
    cursor: pointer;
}

.contact-card .card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 20px rgba(0,0,0,0.1);
}

.contact-icon {
    transition: all 0.3s ease;
}

.contact-card:hover .contact-icon i {
    transform: scale(1.1);
}

/* Button Enhancements */
.btn {
    border-radius: 8px;
    font-weight: 600;
    transition: all 0.2s ease;
    text-transform: uppercase;
    letter-spacing: 0.25px;
}

.btn-lg {
    padding: 0.75rem 2rem;
    font-size: 0.9rem;
}

.btn-primary {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border: none;
    box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
}

.btn-primary:hover {
    background: linear-gradient(135deg, #5a67d8 0%, #6b46c1 100%);
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
}

.btn-outline-primary {
    border: 2px solid #667eea;
    color: #667eea;
}

.btn-outline-primary:hover {
    background: #667eea;
    border-color: #667eea;
    color: white;
    transform: translateY(-2px);
}

/* User Info Section */
.user-info {
    background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
    border-radius: 8px;
    padding: 1rem;
    margin-top: 2rem;
}

/* Responsive Enhancements */
@media (max-width: 768px) {
    .page-title-right {
        text-align: center;
        margin-top: 1rem;
    }
    
    .btn-lg {
        width: 100%;
        margin-bottom: 0.5rem;
    }
    
    .card-header .row > div:last-child {
        text-align: center !important;
    }
    
    .contact-section .row {
        gap: 1rem;
    }
    
    .d-flex.flex-sm-row {
        flex-direction: column;
    }
}

/* Animation Enhancements */
@keyframes fadeIn {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}

.card {
    animation: fadeIn 0.5s ease-out;
}

/* Loading States */
.btn.loading {
    position: relative;
    color: transparent;
}

.btn.loading::after {
    content: '';
    position: absolute;
    width: 16px;
    height: 16px;
    top: 50%;
    left: 50%;
    margin-left: -8px;
    margin-top: -8px;
    border: 2px solid transparent;
    border-top-color: currentColor;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>
@endpush

@push('script')
<script>
$(document).ready(function() {
    // Add hover effects to contact cards
    $('.contact-card .card').on('mouseenter', function() {
        $(this).addClass('shadow-lg');
    }).on('mouseleave', function() {
        $(this).removeClass('shadow-lg');
    });
    
    // Auto-refresh functionality
    let refreshInterval;
    
    // Check for depot assignment every 30 seconds
    function startAutoRefresh() {
        refreshInterval = setInterval(function() {
            // Check if user has been assigned to any depot
            $.ajax({
                url: '{{ route("admin.depots.pos.select") }}',
                method: 'GET',
                success: function(response) {
                    // If the response contains depot selection (not no-access page)
                    if (response.includes('depot-select') || response.includes('Access POS System')) {
                        // User now has depot access, redirect to selection page
                        window.location.href = '{{ route("admin.depots.pos.select") }}';
                    }
                },
                error: function() {
                    // Silently handle errors
                }
            });
        }, 30000); // Check every 30 seconds
    }
    
    // Start auto-refresh
    startAutoRefresh();
    
    // Stop auto-refresh when page is hidden
    document.addEventListener('visibilitychange', function() {
        if (document.hidden) {
            clearInterval(refreshInterval);
        } else {
            startAutoRefresh();
        }
    });
    
    // Clean up on page unload
    $(window).on('beforeunload', function() {
        clearInterval(refreshInterval);
    });
});

// Refresh page function
function refreshPage() {
    const refreshBtn = $('button[onclick="refreshPage()"]');
    const originalText = refreshBtn.html();
    
    refreshBtn.addClass('loading').prop('disabled', true);
    
    setTimeout(function() {
        window.location.reload();
    }, 1000);
}

// Keyboard shortcuts
$(document).on('keydown', function(e) {
    // F5 or Ctrl+R to refresh
    if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) {
        e.preventDefault();
        refreshPage();
    }
    
    // Escape key to go back to depots
    if (e.key === 'Escape') {
        window.location.href = '{{ route("admin.depots.index") }}';
    }
});
</script>
@endpush