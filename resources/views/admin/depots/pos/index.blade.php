@extends('admin.layouts.master')

@push('style')
<style>
    :root {
        --primary-saffron: #FF9933;
        --primary-green: #138808;
        --primary-blue: #000080;
        --neutral-white: #FFFFFF;
        --neutral-light: #F8F9FA;
        --neutral-dark: #495057;
        --border-light: #E2E8F0;
        --shadow-sm: 0 1px 3px rgba(0,0,0,0.1);
        --shadow-md: 0 4px 6px rgba(0,0,0,0.1);
        --success-green: #059669;
        --warning-orange: #D97706;
        --danger-red: #DC2626;
    }

    * { 
        box-sizing: border-box; 
        margin: 0;
        padding: 0;
    }

    body { 
        background: var(--neutral-light); 
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
        font-size: 14px;
        line-height: 1.5;
    }
    
    /* Header Styles */
    .pos-header {
        background: linear-gradient(135deg, var(--primary-saffron) 0%, var(--primary-green) 100%);
        color: white;
        padding: 1rem 1.5rem;
        box-shadow: var(--shadow-md);
        position: sticky;
        top: 0;
        z-index: 1000;
    }

    .header-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .depot-info h4 {
        font-size: 1.25rem;
        font-weight: 700;
        margin: 0 0 0.25rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .depot-info .address {
        font-size: 0.875rem;
        opacity: 0.9;
        font-weight: 500;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .header-btn {
        padding: 0.625rem 1rem;
        border: 1px solid rgba(255,255,255,0.3);
        background: rgba(255,255,255,0.1);
        color: white;
        border-radius: 8px;
        font-weight: 600;
        font-size: 0.875rem;
        cursor: pointer;
        transition: all 0.2s ease;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .header-btn:hover {
        background: rgba(255,255,255,0.2);
        color: white;
        text-decoration: none;
        transform: translateY(-1px);
    }

    .btn-scan { 
        border-color: #10B981; 
        background: rgba(16, 185, 129, 0.2);
    }
    .btn-scan:hover {
        background: rgba(16, 185, 129, 0.3);
        border-color: #10B981;
    }
    .btn-reset { border-color: #FCA5A5; }
    .btn-pay { 
        background: rgba(255,255,255,0.9);
        color: var(--primary-green);
        border-color: white;
    }
    .btn-pay:hover {
        background: white;
        color: var(--primary-green);
    }
    .btn-pay:disabled {
        background: rgba(255,255,255,0.3);
        color: rgba(255,255,255,0.7);
        cursor: not-allowed;
        transform: none;
    }
    
    /* Main Container */
    .pos-container { 
        height: calc(100vh - 80px); 
        display: flex;
        gap: 0;
        margin: 0;
        padding: 0;
        background: var(--neutral-light);
    }
    
    /* Left Panel - Cart */
    .pos-left { 
        width: 420px;
        background: var(--neutral-white);
        border-right: 1px solid var(--border-light);
        display: flex;
        flex-direction: column;
        box-shadow: var(--shadow-sm);
    }
    
    /* Right Panel - Products */
    .pos-right { 
        flex: 1;
        background: var(--neutral-light);
        overflow-y: auto;
        padding: 1.5rem;
    }
    
    /* Customer Section */
    .customer-section {
        padding: 1.5rem;
        border-bottom: 1px solid var(--border-light);
        background: var(--neutral-white);
    }
    
    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1rem;
        color: var(--primary-blue);
        font-weight: 700;
        font-size: 1rem;
    }

    .section-header i {
        font-size: 1.25rem;
        color: var(--primary-saffron);
    }
    
    .customer-search {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1rem;
    }
    
    .search-input {
        flex: 1;
        padding: 0.75rem 1rem;
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease;
        background: var(--neutral-white);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary-saffron);
        box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.1);
    }
    
    .search-btn {
        padding: 0.75rem 1rem;
        background: var(--primary-saffron);
        color: white;
        border: none;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 600;
        transition: all 0.2s ease;
        min-width: 50px;
    }

    .search-btn:hover {
        background: #E6830A;
        transform: translateY(-1px);
    }
    
    .customer-info {
        background: linear-gradient(135deg, #EBF8FF 0%, #E0F2FE 100%);
        border: 2px solid #0EA5E9;
        border-radius: 10px;
        padding: 1rem;
        margin-top: 0.75rem;
    }
    
    .customer-name { 
        font-weight: 700; 
        color: var(--primary-blue);
        font-size: 1rem;
        margin-bottom: 0.25rem;
    }
    
    .customer-details { 
        font-size: 0.8rem; 
        color: #0369A1;
        font-weight: 500;
    }

    .family-members {
        margin-top: 0.75rem;
    }

    .members-header {
        font-size: 0.8rem;
        color: var(--neutral-dark);
        margin-bottom: 0.5rem;
        font-weight: 600;
    }

    .member-card {
        background: var(--neutral-white);
        border: 2px solid var(--border-light);
        border-radius: 8px;
        padding: 0.75rem;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .member-card:hover {
        border-color: var(--primary-saffron);
        background: #FFFBF5;
    }

    .member-name {
        font-weight: 600;
        font-size: 0.875rem;
        color: var(--primary-blue);
        margin-bottom: 0.25rem;
    }

    .member-details {
        font-size: 0.75rem;
        color: var(--neutral-dark);
    }

    .family-head-badge {
        background: #DBEAFE;
        color: #1D4ED8;
        padding: 0.125rem 0.5rem;
        border-radius: 12px;
        font-size: 0.7rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-left: 0.5rem;
    }
    
    /* Cart Section */
    .cart-section {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: var(--neutral-white);
    }
    
    .cart-header {
        padding: 1.5rem 1.5rem 1rem;
        border-bottom: 1px solid var(--border-light);
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: var(--neutral-white);
    }
    
    .cart-title {
        font-weight: 700;
        color: var(--primary-blue);
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 1rem;
    }

    .cart-title i {
        font-size: 1.25rem;
        color: var(--primary-saffron);
    }
    
    .cart-count {
        background: var(--primary-saffron);
        color: white;
        border-radius: 12px;
        padding: 0.25rem 0.75rem;
        font-size: 0.75rem;
        font-weight: 700;
        min-width: 24px;
        text-align: center;
    }
    
    .cart-items {
        flex: 1;
        overflow-y: auto;
        padding: 1rem 1.5rem;
        background: #FAFBFC;
    }
    
    .cart-item {
        background: var(--neutral-white);
        border: 1px solid var(--border-light);
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .cart-item:hover {
        box-shadow: var(--shadow-md);
        border-color: var(--primary-saffron);
    }
    
    .cart-item-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0.75rem;
    }
    
    .cart-item-info h6 {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin: 0 0 0.25rem 0;
        line-height: 1.3;
    }
    
    .cart-item-meta {
        font-size: 0.75rem;
        color: var(--neutral-dark);
        font-weight: 500;
    }
    
    .cart-item-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    
    .qty-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--neutral-light);
        border-radius: 8px;
        padding: 0.25rem;
    }
    
    .qty-btn {
        width: 32px;
        height: 32px;
        border: 1px solid var(--border-light);
        background: var(--neutral-white);
        border-radius: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        font-size: 0.875rem;
        font-weight: 600;
        color: var(--neutral-dark);
        transition: all 0.2s ease;
    }
    
    .qty-btn:hover { 
        background: var(--primary-saffron);
        color: white;
        border-color: var(--primary-saffron);
    }
    
    .qty-input {
        width: 60px;
        text-align: center;
        border: 1px solid var(--border-light);
        border-radius: 6px;
        padding: 0.375rem 0.25rem;
        font-size: 0.875rem;
        font-weight: 600;
        background: var(--neutral-white);
    }

    .qty-input:focus {
        outline: none;
        border-color: var(--primary-saffron);
    }
    
    .item-total {
        font-weight: 700;
        color: var(--success-green);
        font-size: 0.875rem;
    }
    
    .remove-btn {
        background: #FEE2E2;
        border: 1px solid #FECACA;
        color: var(--danger-red);
        border-radius: 6px;
        padding: 0.375rem 0.5rem;
        cursor: pointer;
        font-size: 0.75rem;
        font-weight: 600;
        transition: all 0.2s ease;
    }

    .remove-btn:hover {
        background: var(--danger-red);
        color: white;
        border-color: var(--danger-red);
    }
    
    .empty-cart {
        text-align: center;
        padding: 3rem 1.5rem;
        color: #94A3B8;
    }
    
    .empty-cart i {
        font-size: 3rem;
        opacity: 0.4;
        margin-bottom: 1rem;
        display: block;
        color: var(--primary-saffron);
    }

    .empty-cart-title {
        font-size: 1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .empty-cart-subtitle {
        font-size: 0.875rem;
        opacity: 0.8;
    }
    
    /* Cart Summary */
    .cart-summary {
        background: var(--neutral-white);
        border-top: 1px solid var(--border-light);
        padding: 1.5rem;
    }
    
    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        font-weight: 500;
    }
    
    .summary-row.total {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--success-green);
        border-top: 2px solid var(--border-light);
        padding-top: 0.75rem;
        margin-top: 0.75rem;
    }
    
    .pay-button {
        width: 100%;
        padding: 1rem;
        background: var(--success-green);
        color: white;
        border: none;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        margin-top: 1rem;
        cursor: pointer;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .pay-button:hover:not(:disabled) {
        background: #047857;
        transform: translateY(-1px);
        box-shadow: var(--shadow-md);
    }
    
    .pay-button:disabled {
        background: #D1D5DB;
        color: #9CA3AF;
        cursor: not-allowed;
        transform: none;
    }
    
    /* Product Search */
    .search-section {
        margin-bottom: 1.5rem;
    }
    
    .product-search-bar {
        position: relative;
        max-width: 500px;
    }
    
    .product-search-input {
        width: 100%;
        padding: 1rem 3rem 1rem 1.25rem;
        border: 2px solid var(--border-light);
        border-radius: 12px;
        font-size: 0.875rem;
        background: var(--neutral-white);
        box-shadow: var(--shadow-sm);
        transition: all 0.2s ease;
    }

    .product-search-input:focus {
        outline: none;
        border-color: var(--primary-saffron);
        box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.1);
    }
    
    .product-search-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #94A3B8;
        font-size: 1.25rem;
    }
    
    /* Measurement Unit Filters */
    .filters-section {
        margin-bottom: 1.5rem;
        background: var(--neutral-white);
        border-radius: 12px;
        padding: 1.5rem;
        box-shadow: var(--shadow-sm);
    }

    .filters-header {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 1rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .filters-header i {
        color: var(--primary-saffron);
    }
    
    .measurement-filters {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .measurement-pill {
        padding: 0.75rem 1.25rem;
        background: var(--neutral-light);
        border: 2px solid var(--border-light);
        border-radius: 25px;
        font-size: 0.875rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--neutral-dark);
        text-transform: uppercase;
        letter-spacing: 0.25px;
        position: relative;
    }
    
    .measurement-pill.active {
        background: var(--primary-saffron);
        color: white;
        border-color: var(--primary-saffron);
        box-shadow: 0 4px 8px rgba(255, 153, 51, 0.3);
    }
    
    .measurement-pill:hover:not(.active) {
        background: var(--neutral-white);
        border-color: var(--primary-saffron);
        color: var(--primary-saffron);
        transform: translateY(-1px);
    }

    .measurement-pill .count {
        background: rgba(255,255,255,0.9);
        color: var(--primary-blue);
        padding: 0.125rem 0.5rem;
        border-radius: 10px;
        font-size: 0.7rem;
        font-weight: 700;
        margin-left: 0.5rem;
    }

    .measurement-pill.active .count {
        background: rgba(255,255,255,0.3);
        color: white;
    }
    
    /* Products Grid */
    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1.25rem;
    }
    
    .product-card {
        background: var(--neutral-white);
        border: 2px solid var(--border-light);
        border-radius: 12px;
        overflow: hidden;
        cursor: pointer;
        transition: all 0.3s ease;
        position: relative;
        box-shadow: var(--shadow-sm);
    }
    
    .product-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
        border-color: var(--primary-saffron);
    }
    
    .product-image {
        width: 100%;
        height: 140px;
        background: linear-gradient(135deg, var(--primary-saffron) 0%, var(--primary-green) 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        position: relative;
    }
    
    .product-content {
        padding: 1rem;
    }
    
    .product-name {
        font-size: 0.875rem;
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 0.5rem;
        line-height: 1.3;
        min-height: 2.6rem;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    
    .product-code {
        font-size: 0.75rem;
        color: #94A3B8;
        font-family: 'Monaco', 'Courier New', monospace;
        margin-bottom: 0.5rem;
        background: var(--neutral-light);
        padding: 0.25rem 0.5rem;
        border-radius: 4px;
        font-weight: 500;
    }
    
    .product-stock {
        font-size: 0.8rem;
        color: var(--neutral-dark);
        margin-bottom: 0.75rem;
        font-weight: 500;
    }
    
    .stock-amount {
        font-weight: 700;
        color: var(--success-green);
    }

    .low-stock {
        color: var(--danger-red);
    }
    
    .product-price {
        font-size: 1.1rem;
        font-weight: 800;
        color: var(--success-green);
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .customer-price {
        color: var(--primary-blue);
        font-size: 0.75rem;
        font-weight: 600;
    }
    
    .stock-badge {
        position: absolute;
        top: 0.75rem;
        right: 0.75rem;
        background: rgba(255,255,255,0.95);
        color: var(--success-green);
        padding: 0.375rem 0.75rem;
        border-radius: 20px;
        font-size: 0.75rem;
        font-weight: 700;
        box-shadow: var(--shadow-sm);
    }

    .stock-badge.low {
        background: #FEE2E2;
        color: var(--danger-red);
    }

    /* Barcode Scanner Styles */
    .scanner-modal .modal-dialog {
        max-width: 800px;
    }

    .scanner-container {
        position: relative;
        width: 100%;
        height: 400px;
        background: #000;
        border-radius: 12px;
        overflow: hidden;
        margin-bottom: 1rem;
    }

    #scanner-video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .scanner-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        height: 200px;
        border: 2px solid var(--primary-saffron);
        border-radius: 8px;
        pointer-events: none;
    }

    .scanner-overlay::before {
        content: '';
        position: absolute;
        top: -2px;
        left: 50%;
        transform: translateX(-50%);
        width: 80%;
        height: 2px;
        background: linear-gradient(90deg, transparent, var(--primary-saffron), transparent);
        animation: scan-line 2s linear infinite;
    }

    @keyframes scan-line {
        0%, 100% { top: -2px; }
        50% { top: calc(100% - 2px); }
    }

    .scanner-status {
        text-align: center;
        padding: 1rem;
        font-weight: 600;
        color: var(--primary-blue);
    }

    .scanner-status.success {
        color: var(--success-green);
        background: #F0FDF4;
        border-radius: 8px;
    }

    .scanner-status.error {
        color: var(--danger-red);
        background: #FEF2F2;
        border-radius: 8px;
    }

    .scanner-controls {
        display: flex;
        gap: 1rem;
        justify-content: center;
        margin-top: 1rem;
    }

    .scanner-btn {
        padding: 0.75rem 1.5rem;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s ease;
    }

    .scanner-btn.primary {
        background: var(--primary-saffron);
        color: white;
    }

    .scanner-btn.primary:hover {
        background: #E6830A;
    }

    .scanner-btn.secondary {
        background: #6B7280;
        color: white;
    }

    .scanner-btn.secondary:hover {
        background: #4B5563;
    }
    
    /* Modals */
    .modal-content {
        border-radius: 16px;
        border: none;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }
    
    .modal-header {
        background: linear-gradient(135deg, var(--primary-saffron) 0%, var(--primary-green) 100%);
        color: white;
        border-radius: 16px 16px 0 0;
        padding: 1.5rem 2rem;
        border-bottom: none;
    }

    .modal-title {
        font-weight: 700;
        font-size: 1.25rem;
    }

    .modal-header .close {
        color: white;
        opacity: 0.8;
        font-size: 1.5rem;
        font-weight: 300;
    }

    .modal-header .close:hover {
        opacity: 1;
    }
    
    .modal-body {
        padding: 2rem;
    }

    .modal-footer {
        padding: 1.5rem 2rem;
        border-top: 1px solid var(--border-light);
    }
    
    .form-group label {
        font-weight: 700;
        color: var(--primary-blue);
        margin-bottom: 0.5rem;
        font-size: 0.875rem;
        text-transform: uppercase;
        letter-spacing: 0.25px;
    }
    
    .form-control {
        border: 2px solid var(--border-light);
        border-radius: 8px;
        font-size: 0.875rem;
        transition: border-color 0.2s ease;
    }
    
    .form-control:focus {
        border-color: var(--primary-saffron);
        box-shadow: 0 0 0 3px rgba(255, 153, 51, 0.1);
        outline: none;
    }
    
    .btn {
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        font-weight: 700;
        font-size: 0.875rem;
        transition: all 0.2s ease;
        text-transform: uppercase;
        letter-spacing: 0.25px;
    }

    .btn-primary {
        background: var(--primary-saffron);
        border-color: var(--primary-saffron);
    }

    .btn-primary:hover {
        background: #E6830A;
        border-color: #E6830A;
    }
    
    .btn-secondary {
        background: #6B7280;
        border-color: #6B7280;
    }

    .btn-success {
        background: var(--success-green);
        border-color: var(--success-green);
    }

    .btn-info {
        background: var(--primary-blue);
        border-color: var(--primary-blue);
    }

    /* Payment Modal Specific */
    .order-summary {
        background: linear-gradient(135deg, #F8FAFC 0%, #E2E8F0 100%);
        border-radius: 12px;
        padding: 1.5rem;
        border: 1px solid var(--border-light);
    }

    .order-summary h6 {
        color: var(--primary-blue);
        font-weight: 700;
        margin-bottom: 1rem;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .order-summary .d-flex {
        margin-bottom: 0.75rem;
        font-weight: 500;
    }

    .order-summary hr {
        border-color: var(--border-light);
        margin: 1rem 0;
    }

    .order-summary .text-success {
        color: var(--success-green) !important;
        font-weight: 800;
    }
    
    /* Responsive Design */
    @media (max-width: 1200px) {
        .pos-container {
            flex-direction: column;
            height: auto;
        }
        
        .pos-left {
            width: 100%;
            max-height: 500px;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        }
        
        .header-actions {
            position: relative;
            top: auto;
            right: auto;
        }
    }

    @media (max-width: 768px) {
        .pos-header {
            padding: 1rem;
        }

        .header-content {
            flex-direction: column;
            gap: 1rem;
            align-items: flex-start;
        }

        .header-actions {
            width: 100%;
            justify-content: space-between;
        }

        .measurement-filters {
            justify-content: center;
        }

        .measurement-pill {
            padding: 0.5rem 1rem;
            font-size: 0.75rem;
        }

        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            gap: 1rem;
        }

        .scanner-container {
            height: 300px;
        }

        .scanner-overlay {
            width: 250px;
            height: 150px;
        }
    }
</style>
@endpush

@section('content')
<!-- Professional Header -->
<div class="pos-header">
    <div class="header-content">
        <div class="depot-info">
            <h4><i class="mdi mdi-store"></i>{{ $depot->depot_type }}</h4>
            <div class="address">{{ $depot->address }}, {{ $depot->city }}, {{ $depot->state }}</div>
        </div>
        <div class="header-actions">
            <button class="header-btn btn-scan" onclick="openBarcodeScanner()">
                <i class="mdi mdi-barcode-scan"></i> POS Scanner
            </button>
            <button class="header-btn btn-reset" onclick="resetCart()">
                <i class="mdi mdi-refresh"></i> Reset Cart
            </button>
            <button class="header-btn btn-pay" id="main-pay-btn" onclick="openPayment()" disabled>
                <i class="mdi mdi-credit-card"></i> Pay Now
            </button>
        </div>
    </div>
</div>

<div class="pos-container">
    <!-- Left Panel - Customer & Cart -->
    <div class="pos-left">
        <!-- Customer Section -->
        <div class="customer-section">
            <div class="section-header">
                <i class="mdi mdi-account-circle"></i>
                <span>Customer Selection</span>
            </div>
            <div class="customer-search">
                <input type="text" class="search-input" id="family-id-input" placeholder="Enter Family ID or Card Number">
                <button class="search-btn" onclick="searchFamily()">
                    <i class="mdi mdi-magnify"></i>
                </button>
            </div>
            
            <!-- Family Members -->
            <div id="family-members" class="family-members" style="display: none;">
                <div class="members-header">Select Family Member:</div>
                <div id="members-list"></div>
            </div>
            
            <!-- Selected Customer -->
            <div id="selected-customer" class="customer-info" style="display: none;">
                <div class="customer-name" id="customer-name"></div>
                <div class="customer-details" id="customer-details"></div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="cart-section">
            <div class="cart-header">
                <div class="cart-title">
                    <i class="mdi mdi-cart"></i>
                    <span>Shopping Cart</span>
                    <span class="cart-count" id="cart-count">0</span>
                </div>
            </div>
            
            <div class="cart-items" id="cart-items">
                <div class="empty-cart" id="empty-cart">
                    <i class="mdi mdi-cart-outline"></i>
                    <div class="empty-cart-title">Your cart is empty</div>
                    <div class="empty-cart-subtitle">Add products to get started</div>
                </div>
            </div>
            
            <!-- Cart Summary -->
            <div class="cart-summary">
                <div class="summary-row">
                    <span>Total Items</span>
                    <span id="total-items">0</span>
                </div>
                <div class="summary-row">
                    <span>Sub Total</span>
                    <span id="subtotal">₹0.00</span>
                </div>
                <div class="summary-row">
                    <span>Tax (0%)</span>
                    <span id="tax-amount">₹0.00</span>
                </div>
                <div class="summary-row">
                    <span>Discount</span>
                    <span id="discount-amount">₹0.00</span>
                </div>
                <div class="summary-row total">
                    <span>Grand Total</span>
                    <span id="grand-total">₹0.00</span>
                </div>
                <button class="pay-button" id="cart-pay-btn" onclick="openPayment()" disabled>
                    <i class="mdi mdi-credit-card"></i> Process Payment
                </button>
            </div>
        </div>
    </div>

    <!-- Right Panel - Products -->
    <div class="pos-right">
        <!-- Product Search -->
        <div class="search-section">
            <div class="product-search-bar">
                <input type="text" class="product-search-input" id="product-search" placeholder="Search products by name, code, or scan barcode">
                <i class="mdi mdi-magnify product-search-icon"></i>
            </div>
        </div>
        
        <!-- Measurement Unit Filters -->
        <div class="filters-section">
            <div class="filters-header">
                <i class="mdi mdi-filter-variant"></i>
                <span>Filter by Measurement Unit</span>
            </div>
            <div class="measurement-filters">
                <button class="measurement-pill active" data-unit="all">
                    All Products <span class="count" id="count-all">{{ count($stocks) }}</span>
                </button>
                @php
                    $unitCounts = [];
                    foreach($stocks as $stock) {
                        $unit = $stock['measurement_unit'];
                        $unitCounts[$unit] = ($unitCounts[$unit] ?? 0) + 1;
                    }
                @endphp
                @foreach($unitCounts as $unit => $count)
                <button class="measurement-pill" data-unit="{{ strtolower($unit) }}">
                    {{ $unit }} <span class="count">{{ $count }}</span>
                </button>
                @endforeach
            </div>
        </div>
        
        <!-- Products Grid -->
        <div class="products-grid" id="products-container">
            @foreach($stocks as $stock)
            <div class="product-card" 
                 data-stock-id="{{ $stock['id'] }}" 
                 data-barcode="{{ $stock['barcode'] ?? '' }}"
                 data-unit="{{ strtolower($stock['measurement_unit']) }}" 
                 onclick="addToCart({{ $stock['id'] }})">
                <div class="product-image">
                    @if($stock['measurement_unit'] === 'Kg')
                        <i class="mdi mdi-apple"></i>
                    @elseif($stock['measurement_unit'] === 'Ltr')
                        <i class="mdi mdi-cup-water"></i>
                    @elseif($stock['measurement_unit'] === 'Piece' || $stock['measurement_unit'] === 'Pcs')
                        <i class="mdi mdi-package-variant"></i>
                    @else
                        <i class="mdi mdi-cube-outline"></i>
                    @endif
                    <div class="stock-badge {{ $stock['current_stock'] < 10 ? 'low' : '' }}">
                        {{ number_format($stock['current_stock'], 0) }} {{ $stock['measurement_unit'] }}
                    </div>
                </div>
                <div class="product-content">
                    <div class="product-name">{{ $stock['product_name'] }}</div>
                    @if(isset($stock['barcode']) && $stock['barcode'])
                    <div class="product-code">{{ $stock['barcode'] }}</div>
                    @endif
                    <div class="product-stock">
                        Available: <span class="stock-amount {{ $stock['current_stock'] < 10 ? 'low-stock' : '' }}">
                            {{ number_format($stock['current_stock'], 2) }} {{ $stock['measurement_unit'] }}
                        </span>
                    </div>
                    <div class="product-price">
                        <span>₹{{ number_format($stock['customer_price'], 2) }}</span>
                        @if($stock['customer_price'] != $stock['price'])
                        <span class="customer-price">MRP: ₹{{ number_format($stock['price'], 2) }}</span>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Barcode Scanner Modal -->
<div class="modal fade scanner-modal" id="barcodeScanner" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-barcode-scan mr-2"></i>POS Barcode Scanner</h5>
                <button type="button" class="close" onclick="closeBarcodeScanner()">&times;</button>
            </div>
            <div class="modal-body">
                <div class="scanner-container">
                    <video id="scanner-video" autoplay muted playsinline></video>
                    <div class="scanner-overlay"></div>
                </div>
                <div class="scanner-status" id="scanner-status">
                    Position the barcode within the scanning area
                </div>
                <div class="scanner-controls">
                    <button class="scanner-btn secondary" onclick="closeBarcodeScanner()">
                        <i class="mdi mdi-close mr-1"></i> Cancel
                    </button>
                    <button class="scanner-btn primary" onclick="toggleFlashlight()" id="flashlight-btn" style="display: none;">
                        <i class="mdi mdi-flashlight mr-1"></i> Flashlight
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quantity Modal -->
<div class="modal fade" id="quantityModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-cart-plus mr-2"></i>Add to Cart</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body text-center">
                <div class="product-info mb-4">
                    <h6 id="modal-product-name" class="font-weight-bold mb-2"></h6>
                    <p class="text-muted mb-0">Available Stock: <span id="modal-available-stock" class="font-weight-bold"></span></p>
                    <p class="text-success mb-0">Customer Price: <span id="modal-customer-price" class="font-weight-bold"></span></p>
                </div>
                <div class="form-group">
                    <label>Enter Quantity</label>
                    <div class="d-flex justify-content-center align-items-center">
                        <button type="button" class="qty-btn" onclick="decreaseModalQty()">
                            <i class="mdi mdi-minus"></i>
                        </button>
                        <input type="number" id="modal-quantity" class="form-control mx-3" 
                               style="width: 120px; text-align: center;" 
                               min="0.01" step="0.01" value="1">
                        <button type="button" class="qty-btn" onclick="increaseModalQty()">
                            <i class="mdi mdi-plus"></i>
                        </button>
                    </div>
                    <small class="text-muted mt-2">Total: ₹<span id="modal-item-total">0.00</span></small>
                </div>
                <input type="hidden" id="modal-stock-id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmAddToCart()">
                    <i class="mdi mdi-cart-plus mr-1"></i>Add to Cart
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modal -->
<div class="modal fade" id="paymentModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="mdi mdi-credit-card mr-2"></i>Process Payment</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-8">
                        <div class="form-group">
                            <label>Amount Received <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number" class="form-control" id="received-amount" 
                                       step="0.01" placeholder="0.00" oninput="calculateChange()">
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Change to Return</label>
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">₹</span>
                                </div>
                                <input type="number" class="form-control" id="change-return" readonly>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>Payment Method</label>
                            <select class="form-control" id="payment-type">
                                <option value="Cash">Cash Payment</option>
                                <option value="Card">Card Payment</option>
                                <option value="UPI">UPI Payment</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Additional Notes</label>
                            <textarea class="form-control" id="payment-note" rows="3" 
                                      placeholder="Add any additional notes (optional)"></textarea>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="order-summary">
                            <h6><i class="mdi mdi-receipt mr-2"></i>Order Summary</h6>
                            <div class="d-flex justify-content-between">
                                <span>Total Products:</span>
                                <strong id="modal-total-products">0</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Sub Total:</span>
                                <strong id="modal-subtotal">₹0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Tax (0%):</span>
                                <strong id="modal-tax">₹0.00</strong>
                            </div>
                            <div class="d-flex justify-content-between">
                                <span>Discount:</span>
                                <strong id="modal-discount">₹0.00</strong>
                            </div>
                            <hr>
                            <div class="d-flex justify-content-between">
                                <span><strong>Grand Total:</strong></span>
                                <strong class="text-success" id="modal-grand-total">₹0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="mdi mdi-close mr-1"></i>Cancel
                </button>
                <button type="button" class="btn btn-info" onclick="processPayment(false)">
                    <i class="mdi mdi-content-save mr-1"></i>Save Transaction
                </button>
                <button type="button" class="btn btn-success" onclick="processPayment(true)">
                    <i class="mdi mdi-printer mr-1"></i>Save & Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<!-- Include QuaggaJS for barcode scanning -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

<script>
// Global variables
let cart = [];
let selectedCustomer = null;
let stocks = @json($stocks);
let scannerStream = null;
let scannerActive = false;

// CSRF setup
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
    }
});

// Initialize
$(document).ready(function() {
    updateCartDisplay();
    
    // Enter key handlers
    $('#family-id-input').on('keypress', function(e) {
        if (e.which === 13) searchFamily();
    });
    
    $('#modal-quantity').on('keypress', function(e) {
        if (e.which === 13) confirmAddToCart();
    });

    // Update total when quantity changes in modal
    $('#modal-quantity').on('input', function() {
        updateModalTotal();
    });
    
    // Product search with improved filtering
    $('#product-search').on('input', function() {
        const query = $(this).val().toLowerCase().trim();
        filterProducts(query);
    });
});

// Barcode Scanner Functions
function openBarcodeScanner() {
    $('#barcodeScanner').modal('show');
    startCamera();
}

function closeBarcodeScanner() {
    stopCamera();
    $('#barcodeScanner').modal('hide');
}

async function startCamera() {
    try {
        const constraints = {
            video: {
                facingMode: 'environment', // Use back camera
                width: { ideal: 1280 },
                height: { ideal: 720 }
            }
        };
        
        scannerStream = await navigator.mediaDevices.getUserMedia(constraints);
        const video = document.getElementById('scanner-video');
        video.srcObject = scannerStream;
        
        // Check if flashlight is supported
        const track = scannerStream.getVideoTracks()[0];
        const capabilities = track.getCapabilities();
        
        if (capabilities.torch) {
            $('#flashlight-btn').show();
        }
        
        // Initialize Quagga scanner
        video.addEventListener('loadedmetadata', () => {
            initQuagga();
        });
        
    } catch (error) {
        console.error('Error accessing camera:', error);
        updateScannerStatus('Camera access denied. Please allow camera permissions.', 'error');
    }
}

function stopCamera() {
    if (scannerStream) {
        scannerStream.getTracks().forEach(track => track.stop());
        scannerStream = null;
    }
    
    if (scannerActive) {
        Quagga.stop();
        scannerActive = false;
    }
    
    updateScannerStatus('Scanner stopped', 'error');
}

function initQuagga() {
    Quagga.init({
        inputStream: {
            name: "Live",
            type: "LiveStream",
            target: document.querySelector('#scanner-video'),
            constraints: {
                width: 640,
                height: 480,
                facingMode: "environment"
            },
        },
        decoder: {
            readers: [
                "code_128_reader",
                "ean_reader",
                "ean_8_reader",
                "code_39_reader",
                "code_39_vin_reader",
                "codabar_reader",
                "upc_reader",
                "upc_e_reader",
                "i2of5_reader"
            ]
        },
        locate: true,
        locator: {
            halfSample: true,
            patchSize: "medium",
        },
    }, function(err) {
        if (err) {
            console.error('Quagga initialization error:', err);
            updateScannerStatus('Scanner initialization failed', 'error');
            return;
        }
        
        Quagga.start();
        scannerActive = true;
        updateScannerStatus('Scanner ready - Position barcode in the frame', 'success');
    });

    // Listen for successful barcode detection
    Quagga.onDetected(function(data) {
        const barcode = data.codeResult.code;
        handleBarcodeDetection(barcode);
    });
}

function handleBarcodeDetection(barcode) {
    // Find product by barcode
    const product = stocks.find(stock => stock.barcode === barcode);
    
    if (product) {
        updateScannerStatus(`Product found: ${product.product_name}`, 'success');
        
        // Close scanner and add to cart
        setTimeout(() => {
            closeBarcodeScanner();
            addToCartDirectly(product.id, 1); // Add 1 quantity by default
        }, 1500);
        
    } else {
        updateScannerStatus(`Product not found for barcode: ${barcode}`, 'error');
        
        // Continue scanning after 2 seconds
        setTimeout(() => {
            updateScannerStatus('Continue scanning...', 'success');
        }, 2000);
    }
}

function addToCartDirectly(stockId, quantity = 1) {
    const stock = stocks.find(s => s.id === stockId);
    if (!stock || quantity <= 0 || quantity > stock.current_stock) {
        alert('Invalid product or quantity');
        return;
    }
    
    const price = stock.customer_price;
    const existingItem = cart.find(item => item.stock_id === stockId);
    
    if (existingItem) {
        const newQuantity = existingItem.quantity + quantity;
        if (newQuantity <= stock.current_stock) {
            existingItem.quantity = newQuantity;
            existingItem.total = newQuantity * price;
        } else {
            alert(`Cannot add more. Maximum available stock: ${stock.current_stock}`);
            return;
        }
    } else {
        cart.push({
            stock_id: stockId,
            name: stock.product_name,
            quantity: quantity,
            price: price,
            unit: stock.measurement_unit,
            total: quantity * price,
            max_stock: stock.current_stock
        });
    }
    
    updateCartDisplay();
    
    // Show success notification
    showNotification(`${stock.product_name} added to cart!`, 'success');
}

function toggleFlashlight() {
    if (scannerStream) {
        const track = scannerStream.getVideoTracks()[0];
        const capabilities = track.getCapabilities();
        
        if (capabilities.torch) {
            const settings = track.getSettings();
            track.applyConstraints({
                advanced: [{ torch: !settings.torch }]
            }).then(() => {
                const btn = $('#flashlight-btn');
                if (settings.torch) {
                    btn.html('<i class="mdi mdi-flashlight mr-1"></i> Flashlight');
                } else {
                    btn.html('<i class="mdi mdi-flashlight-off mr-1"></i> Flashlight Off');
                }
            });
        }
    }
}

function updateScannerStatus(message, type = 'default') {
    const statusElement = $('#scanner-status');
    statusElement.text(message);
    statusElement.removeClass('success error');
    
    if (type === 'success') {
        statusElement.addClass('success');
    } else if (type === 'error') {
        statusElement.addClass('error');
    }
}

function showNotification(message, type = 'info') {
    // Create a simple notification (you can replace this with a better notification library)
    const notification = $(`
        <div class="alert alert-${type === 'success' ? 'success' : 'info'} alert-dismissible fade show" 
             style="position: fixed; top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <strong>${type === 'success' ? 'Success!' : 'Info'}</strong> ${message}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    `);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.alert('close');
    }, 3000);
}

// Improved product filtering
function filterProducts(searchQuery = '') {
    const activeUnit = $('.measurement-pill.active').data('unit');
    
    $('.product-card').each(function() {
        const $card = $(this);
        const productName = $card.find('.product-name').text().toLowerCase();
        const productCode = $card.find('.product-code').text().toLowerCase();
        const barcode = $card.data('barcode').toString().toLowerCase();
        const cardUnit = $card.data('unit');
        
        let showBySearch = true;
        let showByUnit = true;
        
        // Search filter (including barcode)
        if (searchQuery) {
            showBySearch = productName.includes(searchQuery) || 
                          productCode.includes(searchQuery) || 
                          barcode.includes(searchQuery);
        }
        
        // Unit filter
        if (activeUnit !== 'all') {
            showByUnit = cardUnit === activeUnit;
        }
        
        $card.toggle(showBySearch && showByUnit);
    });
}

// Measurement unit filtering
$(document).on('click', '.measurement-pill', function() {
    $('.measurement-pill').removeClass('active');
    $(this).addClass('active');
    
    const searchQuery = $('#product-search').val().toLowerCase().trim();
    filterProducts(searchQuery);
});

// Family search
function searchFamily() {
    const familyId = $('#family-id-input').val().trim();
    if (!familyId) {
        alert('Please enter Family ID or Card Number');
        return;
    }
    
    $.get(`{{ route('admin.depots.pos.family-members', $depot) }}`, { family_id: familyId })
        .done(function(members) {
            if (members.length === 0) {
                alert('No family members found for this ID');
                return;
            }
            
            let membersHtml = '';
            members.forEach(member => {
                membersHtml += `
                    <div class="member-card" onclick="selectCustomer(${JSON.stringify(member).replace(/"/g, '&quot;')})">
                        <div class="member-name">${member.name}
                            ${member.is_family_head ? '<span class="family-head-badge">Family Head</span>' : ''}
                        </div>
                        <div class="member-details">
                            Card: ${member.card_range || 'Not Assigned'} | 
                            Mobile: ${member.mobile || 'Not Available'}
                        </div>
                    </div>
                `;
            });
            
            $('#members-list').html(membersHtml);
            $('#family-members').show();
        })
        .fail(function() {
            alert('Error searching family members. Please try again.');
        });
}

function selectCustomer(customer) {
    selectedCustomer = customer;
    $('#customer-name').text(customer.name);
    $('#customer-details').text(`Family ID: ${customer.family_id} | Card: ${customer.card_range || 'Not Assigned'} | Mobile: ${customer.mobile || 'N/A'}`);
    $('#selected-customer').show();
    $('#family-members').hide();
    $('#family-id-input').val('');
    updatePayButtons();
}

// Cart management
function addToCart(stockId) {
    const stock = stocks.find(s => s.id === stockId);
    if (!stock) return;
    
    if (stock.current_stock <= 0) {
        alert('This product is out of stock!');
        return;
    }
    
    $('#modal-stock-id').val(stockId);
    $('#modal-product-name').text(stock.product_name);
    $('#modal-available-stock').text(`${stock.current_stock} ${stock.measurement_unit}`);
    $('#modal-customer-price').text(`₹${stock.customer_price.toFixed(2)}`);
    $('#modal-quantity').val(1).attr('max', stock.current_stock).attr('step', stock.measurement_unit === 'Kg' || stock.measurement_unit === 'Ltr' ? '0.01' : '1');
    
    updateModalTotal();
    $('#quantityModal').modal('show');
}

function updateModalTotal() {
    const stockId = parseInt($('#modal-stock-id').val());
    const quantity = parseFloat($('#modal-quantity').val()) || 0;
    const stock = stocks.find(s => s.id === stockId);
    
    if (stock) {
        const total = quantity * stock.customer_price;
        $('#modal-item-total').text(total.toFixed(2));
    }
}

function increaseModalQty() {
    const input = $('#modal-quantity');
    const max = parseFloat(input.attr('max'));
    const step = parseFloat(input.attr('step'));
    const current = parseFloat(input.val()) || 0;
    
    if (current + step <= max) {
        input.val((current + step).toFixed(2));
        updateModalTotal();
    }
}

function decreaseModalQty() {
    const input = $('#modal-quantity');
    const step = parseFloat(input.attr('step'));
    const current = parseFloat(input.val()) || 0;
    
    if (current - step >= step) {
        input.val((current - step).toFixed(2));
        updateModalTotal();
    }
}

function confirmAddToCart() {
    const stockId = parseInt($('#modal-stock-id').val());
    const quantity = parseFloat($('#modal-quantity').val());
    const stock = stocks.find(s => s.id === stockId);
    
    if (!stock || quantity <= 0 || quantity > stock.current_stock) {
        alert('Invalid quantity. Please check the available stock.');
        return;
    }
    
    // Always use customer_price for calculations
    const price = stock.customer_price;
    const existingItem = cart.find(item => item.stock_id === stockId);
    
    if (existingItem) {
        existingItem.quantity = quantity;
        existingItem.total = quantity * price;
    } else {
        cart.push({
            stock_id: stockId,
            name: stock.product_name,
            quantity: quantity,
            price: price,
            unit: stock.measurement_unit,
            total: quantity * price,
            max_stock: stock.current_stock
        });
    }
    
    updateCartDisplay();
    $('#quantityModal').modal('hide');
}

function removeFromCart(stockId) {
    cart = cart.filter(item => item.stock_id !== stockId);
    updateCartDisplay();
}

function updateItemQuantity(stockId, newQuantity) {
    const item = cart.find(item => item.stock_id === stockId);
    
    if (!item || newQuantity <= 0 || newQuantity > item.max_stock) return;
    
    item.quantity = newQuantity;
    item.total = newQuantity * item.price;
    updateCartDisplay();
}

function updateCartDisplay() {
    const cartContainer = $('#cart-items');
    const emptyCart = $('#empty-cart');
    
    if (cart.length === 0) {
        emptyCart.show();
        cartContainer.children('.cart-item').remove();
    } else {
        emptyCart.hide();
        
        let cartHtml = '';
        cart.forEach(item => {
            cartHtml += `
                <div class="cart-item">
                    <div class="cart-item-header">
                        <div class="cart-item-info">
                            <h6>${item.name}</h6>
                            <div class="cart-item-meta">₹${item.price.toFixed(2)} per ${item.unit} • Customer Rate</div>
                        </div>
                        <button class="remove-btn" onclick="removeFromCart(${item.stock_id})">
                            <i class="mdi mdi-delete-outline"></i>
                        </button>
                    </div>
                    <div class="cart-item-controls">
                        <div class="qty-controls">
                            <button class="qty-btn" onclick="updateItemQuantity(${item.stock_id}, ${item.quantity - (item.unit === 'Kg' || item.unit === 'Ltr' ? 0.01 : 1)})">
                                <i class="mdi mdi-minus"></i>
                            </button>
                            <input type="number" class="qty-input" value="${item.quantity}" 
                                   onchange="updateItemQuantity(${item.stock_id}, parseFloat(this.value))" 
                                   min="0.01" step="${item.unit === 'Kg' || item.unit === 'Ltr' ? '0.01' : '1'}" 
                                   max="${item.max_stock}">
                            <button class="qty-btn" onclick="updateItemQuantity(${item.stock_id}, ${item.quantity + (item.unit === 'Kg' || item.unit === 'Ltr' ? 0.01 : 1)})">
                                <i class="mdi mdi-plus"></i>
                            </button>
                        </div>
                        <div class="item-total">₹${item.total.toFixed(2)}</div>
                    </div>
                </div>
            `;
        });
        
        cartContainer.html(emptyCart[0].outerHTML + cartHtml);
    }
    
    // Update summary
    const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
    const tax = 0;
    const discount = 0;
    const total = subtotal + tax - discount;
    
    $('#cart-count').text(cart.length);
    $('#total-items').text(cart.length);
    $('#subtotal').text(`₹${subtotal.toFixed(2)}`);
    $('#tax-amount').text(`₹${tax.toFixed(2)}`);
    $('#discount-amount').text(`₹${discount.toFixed(2)}`);
    $('#grand-total').text(`₹${total.toFixed(2)}`);
    
    updatePayButtons();
}

function updatePayButtons() {
    const canPay = selectedCustomer && cart.length > 0;
    $('#main-pay-btn, #cart-pay-btn').prop('disabled', !canPay);
}

// Payment functions
function openPayment() {
    if (!selectedCustomer || cart.length === 0) {
        alert('Please select a customer and add items to cart');
        return;
    }
    
    const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
    const tax = 0;
    const discount = 0;
    const total = subtotal + tax - discount;
    
    $('#modal-total-products').text(cart.length);
    $('#modal-subtotal').text(`₹${subtotal.toFixed(2)}`);
    $('#modal-tax').text(`₹${tax.toFixed(2)}`);
    $('#modal-discount').text(`₹${discount.toFixed(2)}`);
    $('#modal-grand-total').text(`₹${total.toFixed(2)}`);
    $('#received-amount').val(total.toFixed(2));
    $('#change-return').val('0.00');
    
    $('#paymentModal').modal('show');
}

function calculateChange() {
    const total = parseFloat($('#modal-grand-total').text().replace('₹', '')) || 0;
    const received = parseFloat($('#received-amount').val()) || 0;
    const change = received - total;
    $('#change-return').val(change.toFixed(2));
}

function processPayment(shouldPrint) {
    if (cart.length === 0 || !selectedCustomer) return;
    
    const receivedAmount = parseFloat($('#received-amount').val());
    const total = parseFloat($('#modal-grand-total').text().replace('₹', ''));
    
    if (receivedAmount < total) {
        alert('Received amount cannot be less than total amount');
        return;
    }
    
    const data = {
        customer_id: selectedCustomer.id,
        items: cart.map(item => ({
            stock_id: item.stock_id,
            quantity: item.quantity,
            price: item.price // This will be customer_price
        })),
        payment_type: $('#payment-type').val(),
        received_amount: receivedAmount,
        note: $('#payment-note').val()
    };
    
    // Show loading state
    const submitBtn = shouldPrint ? 
        $('.btn-success').text('Processing...').prop('disabled', true) :
        $('.btn-info').text('Processing...').prop('disabled', true);
    
    $.post('{{ route('admin.depots.pos.process-sale', $depot) }}', data)
        .done(function(response) {
            if (response.success) {
                // Reset cart and customer
                cart = [];
                selectedCustomer = null;
                $('#selected-customer').hide();
                $('#family-members').hide();
                updateCartDisplay();
                
                $('#paymentModal').modal('hide');
                
                if (shouldPrint) {
                    window.open(`{{ url('admin/depots/'.$depot->id.'/pos/print') }}/${response.sale_id}`, '_blank');
                }
                
                // Redirect to invoice
                window.location.href = `{{ url('admin/depots/'.$depot->id.'/pos/invoice') }}/${response.sale_id}`;
            } else {
                alert(response.message || 'Error processing payment');
            }
        })
        .fail(function(xhr) {
            alert('Error processing payment. Please try again.');
            console.error('Payment error:', xhr.responseText);
        })
        .always(function() {
            // Reset button states
            $('.btn-success').text('Save & Print Receipt').prop('disabled', false);
            $('.btn-info').text('Save Transaction').prop('disabled', false);
        });
}

// Utility functions
function resetCart() {
    if (cart.length > 0 && !confirm('Are you sure you want to reset the cart? All items will be removed.')) return;
    
    cart = [];
    selectedCustomer = null;
    $('#selected-customer').hide();
    $('#family-members').hide();
    $('#family-id-input').val('');
    updateCartDisplay();
}

// Clean up camera when page unloads
$(window).on('beforeunload', function() {
    stopCamera();
});
</script>
@endpush