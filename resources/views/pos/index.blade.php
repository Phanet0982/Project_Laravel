@extends('layouts.app')

@section('title', 'Point of Sale')

@section('content')
<div class="pos-container">
    <!-- Header Section -->
    <div class="pos-header mb-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h1 class="pos-title mb-1">Point of Sale</h1>
                <p class="pos-subtitle mb-0">Process sales quickly and efficiently</p>
            </div>
            <div class="d-flex gap-2">
                <button class="btn btn-outline-light" onclick="toggleFullscreen()">
                    <i class="fas fa-expand me-2"></i>Fullscreen
                </button>
                <button class="btn btn-success btn-lg px-4" onclick="processPayment()">
                    <i class="fas fa-credit-card me-2"></i>Process Payment
                </button>
            </div>
        </div>
    </div>

    <div class="row g-4">
        <!-- Product Selection Panel -->
        <div class="col-lg-8">
            <div class="pos-panel h-100">
                <div class="panel-header">
                    <h5 class="mb-0"><i class="fas fa-boxes me-2"></i>Products</h5>
                </div>
                <div class="panel-body">
                    <!-- Search and Filter Controls -->
                    <div class="search-filters mb-4">
                        <div class="row g-3">
                            <div class="col-12 col-md-8">
                                <div class="input-group">
                                    <span class="input-group-text bg-tertiary border-secondary">
                                        <i class="fas fa-search text-muted"></i>
                                    </span>
                                    <input type="text" class="form-control" placeholder="Search products by name or barcode..." id="productSearch">
                                </div>
                            </div>
                            <div class="col-12 col-md-4">
                                <select class="form-select" id="categoryFilter">
                                    <option value="">All Categories</option>
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Mobile Cart Summary -->
                    <div class="d-lg-none mb-3">
                        <div class="card">
                            <div class="card-header bg-success text-white">
                                <div class="d-flex justify-content-between align-items-center">
                                    <h6 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Cart Summary</h6>
                                    <span id="mobileCartCount">0 items</span>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Subtotal:</span>
                                    <strong id="mobileSubtotal">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span>Tax (10%):</span>
                                    <strong id="mobileTax">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span>Discount:</span>
                                    <strong id="mobileDiscount">$0.00</strong>
                                </div>
                                <div class="d-flex justify-content-between align-items-center">
                                    <strong>Total:</strong>
                                    <strong id="mobileTotal" class="text-success fs-5">$0.00</strong>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Product Grid -->
                    <div class="product-grid" id="productGrid">
                        @foreach($products as $product)
                            <div class="product-item" 
                                 data-category="{{ $product->category_id }}" 
                                 data-name="{{ strtolower($product->name) }}"
                                 data-barcode="{{ $product->barcode ?? '' }}">
                                <div class="product-card" 
                                     onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price }})"
                                     data-bs-toggle="tooltip" 
                                     title="Click to add to cart">
                                    <div class="product-image">
                                        @if($product->image)
                                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="img-fluid">
                                        @else
                                            <div class="image-placeholder">
                                                <i class="fas fa-box text-primary"></i>
                                            </div>
                                        @endif
                                        @if($product->qty <= 10)
                                            <span class="stock-badge low">Low Stock</span>
                                        @elseif($product->qty == 0)
                                            <span class="stock-badge out">Out of Stock</span>
                                        @endif
                                    </div>
                                    <div class="product-info">
                                        <h6 class="product-name">{{ $product->name }}</h6>
                                        <p class="product-category text-muted small mb-2">{{ $product->category->name }}</p>
                                        <div class="product-price">
                                            <span class="price">${{ number_format($product->sale_price, 2) }}</span>
                                            @if($product->cost_price)
                                                <span class="cost-price text-muted small">Cost: ${{ number_format($product->cost_price, 2) }}</span>
                                            @endif
                                        </div>
                                        <div class="product-stock">
                                            <span class="stock-qty">Stock: {{ $product->qty }}</span>
                                            @if($product->barcode)
                                                <span class="barcode">#{{ $product->barcode }}</span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="product-action">
                                        <button class="btn btn-primary btn-sm add-to-cart-btn">
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Cart and Payment Panel -->
        <div class="col-lg-4">
            <div class="pos-panel sticky-top">
                <div class="panel-header bg-success">
                    <h5 class="mb-0 text-white"><i class="fas fa-shopping-cart me-2"></i>Shopping Cart</h5>
                </div>
                <div class="panel-body">
                    <!-- Cart Items -->
                    <div class="cart-container mb-4">
                        <div id="cartItems" class="cart-items">
                            <div class="empty-cart text-center py-5">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h6 class="text-muted">Cart is empty</h6>
                                <p class="text-muted small">Add products to get started</p>
                            </div>
                        </div>
                    </div>

                    <!-- Customer Selection -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">Customer</label>
                        <select class="form-select" id="customerSelect">
                            <option value="">Walk-in Customer</option>
                            @foreach($customers as $customer)
                                <option value="{{ $customer->id }}" 
                                        data-type="{{ $customer->customer_type ?? 'regular' }}">
                                    {{ $customer->name }}
                                    @if(isset($customer->customer_type) && $customer->customer_type == 'vip')
                                        <span class="badge bg-warning ms-2">VIP</span>
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary mb-4">
                        <h6 class="border-bottom pb-2 mb-3">Order Summary</h6>
                        
                        <div class="summary-item mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Subtotal</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                        </div>
                        
                        <div class="summary-item mb-2">
                            <div class="d-flex justify-content-between">
                                <span>Tax (10%)</span>
                                <span id="tax">$0.00</span>
                            </div>
                        </div>
                        
                        <div class="summary-item mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span>Discount</span>
                                <div class="discount-input">
                                    <input type="number" 
                                           class="form-control form-control-sm" 
                                           id="discount" 
                                           value="0" 
                                           min="0" 
                                           max="100" 
                                           placeholder="%"
                                           onchange="updateCart()">
                                </div>
                            </div>
                        </div>
                        
                        <div class="summary-total border-top pt-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Total</h5>
                                <h4 class="mb-0 text-success" id="total">$0.00</h4>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Method -->
                    <div class="mb-4">
                        <label class="form-label fw-medium mb-2">Payment Method</label>
                        <div class="payment-methods">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="cash" value="Cash" checked>
                                <label class="form-check-label" for="cash">
                                    <i class="fas fa-money-bill-wave me-1"></i>Cash
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="card" value="Card">
                                <label class="form-check-label" for="card">
                                    <i class="fas fa-credit-card me-1"></i>Card
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="paymentMethod" id="qr" value="QR">
                                <label class="form-check-label" for="qr">
                                    <i class="fas fa-qrcode me-1"></i>QR
                                </label>
                            </div>
                        </div>
                        
                        <!-- Card Payment Options -->
                        <div id="cardOptions" class="card-options mt-3" style="display: none;">
                            <div class="border rounded p-3 bg-tertiary">
                                <h6 class="mb-3"><i class="fas fa-credit-card me-2"></i>Card Payment Options</h6>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-primary flex-fill" id="manualCardBtn">
                                        <i class="fas fa-keyboard me-2"></i>Enter Card Details
                                    </button>
                                    <button type="button" class="btn btn-outline-success flex-fill" id="scanCardBtn">
                                        <i class="fas fa-camera me-2"></i>Scan Card
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="d-grid gap-2">
                        <button class="btn btn-success btn-lg" onclick="processPayment()">
                            <i class="fas fa-credit-card me-2"></i>Process Payment
                        </button>
                        <button class="btn btn-outline-danger" onclick="clearCart()">
                            <i class="fas fa-trash me-2"></i>Clear Cart
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- QR Code Payment Modal -->
<div class="modal fade" id="qrPaymentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-qrcode me-2"></i>Scan to Pay
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="qr-instructions mb-4">
                    <h6>Scan this QR code to complete payment</h6>
                    <p class="text-muted">Amount: <span id="qrAmount" class="fw-bold text-success"></span></p>
                    <p class="text-muted small">Payment Reference: <span id="paymentRef" class="fw-bold"></span></p>
                </div>
                
                <div class="qr-code-container mb-4">
                    <div id="qrCodeDisplay" class="bg-white p-3 rounded" style="display: inline-block; min-width: 220px; min-height: 220px;">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=10&data=PaymentQR" 
                             alt="Payment QR Code" 
                             style="width: 200px; height: 200px; border-radius: 8px; display: block;">
                    </div>
                </div>
                
                <div class="payment-status">
                    <div class="status-indicator" id="paymentStatus">
                        <div class="spinner-border text-primary me-2" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <span class="status-text">Waiting for payment confirmation...</span>
                    </div>
                </div>
                
                <div class="manual-payment mt-3">
                    <button type="button" class="btn btn-outline-primary btn-sm" id="manualPaymentBtn">
                        <i class="fas fa-mobile-alt me-2"></i>Simulate Mobile Payment
                    </button>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmQrPayment" style="display: none;">
                    <i class="fas fa-check me-2"></i>Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Manual Card Payment Modal -->
<div class="modal fade" id="manualCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-credit-card me-2"></i>Enter Card Details
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="cardPaymentForm">
                    <div class="mb-3">
                        <label class="form-label">Card Number</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-credit-card"></i>
                            </span>
                            <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456" maxlength="19" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Expiry Date</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-calendar"></i>
                                </span>
                                <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY" maxlength="5" required>
                            </div>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">CVV</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <input type="text" class="form-control" id="cardCvv" placeholder="123" maxlength="4" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Cardholder Name</label>
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-user"></i>
                            </span>
                            <input type="text" class="form-control" id="cardName" placeholder="John Doe" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="text" class="form-control" id="cardAmount" readonly>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="processCardPayment">
                    <i class="fas fa-check me-2"></i>Process Payment
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Card Scan Modal -->
<div class="modal fade" id="scanCardModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-camera me-2"></i>Scan Card
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="scan-instructions mb-4">
                    <h6>Position card in camera view</h6>
                    <p class="text-muted">Amount: <span id="scanAmount" class="fw-bold text-success"></span></p>
                </div>
                
                <div class="camera-container mb-4">
                    <div class="camera-placeholder bg-tertiary rounded d-flex align-items-center justify-content-center" style="height: 200px;">
                        <div class="text-center">
                            <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Camera feed will appear here</p>
                            <small class="text-muted">Click "Start Camera" to begin</small>
                        </div>
                    </div>
                    <video id="cardScanner" style="display: none; width: 100%; height: 200px; object-fit: cover;" autoplay muted></video>
                </div>
                
                <div class="scan-status">
                    <div class="status-indicator bg-tertiary p-3 rounded">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-search me-2 text-primary"></i>
                            <span class="status-text">Ready to scan</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="startCameraBtn">
                    <i class="fas fa-video me-2"></i>Start Camera
                </button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-success" id="confirmScanPayment" style="display: none;">
                    <i class="fas fa-check me-2"></i>Confirm Payment
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
/* POS Container */
.pos-container {
    padding: 20px;
    max-width: 100%;
}

/* Header Styles */
.pos-header {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    padding: 20px;
}

.pos-title {
    font-size: 1.75rem;
    font-weight: 600;
    color: var(--text-primary);
    margin: 0;
}

.pos-subtitle {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

/* Panel Styles */
.pos-panel {
    background: var(--bg-secondary);
    border: 1px solid var(--border);
    border-radius: 12px;
    overflow: hidden;
    height: fit-content;
}

.panel-header {
    background: var(--bg-tertiary);
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
}

.panel-header.bg-success {
    background: var(--success) !important;
}

.panel-body {
    padding: 20px;
}

/* Search and Filters */
.search-filters .input-group-text {
    background: var(--bg-tertiary);
    border-color: var(--border);
}

/* Product Grid */
.product-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 16px;
    max-height: 500px;
    overflow-y: auto;
    padding: 4px;
}

.product-item {
    transition: all 0.2s ease;
}

.product-card {
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 16px;
    cursor: pointer;
    transition: all 0.2s ease;
    position: relative;
    height: 100%;
    display: flex;
    flex-direction: column;
}

.product-card:hover {
    transform: translateY(-3px);
    border-color: var(--primary);
    box-shadow: 0 6px 12px rgba(99, 102, 241, 0.15);
}

.product-image {
    position: relative;
    margin-bottom: 12px;
    text-align: center;
    min-height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.product-image img {
    max-height: 80px;
    max-width: 100%;
    border-radius: 6px;
    object-fit: contain;
}

.image-placeholder {
    width: 60px;
    height: 60px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(99, 102, 241, 0.1);
    border-radius: 8px;
    margin: 0 auto;
}

.stock-badge {
    position: absolute;
    top: 8px;
    right: 8px;
    padding: 4px 8px;
    border-radius: 20px;
    font-size: 0.7rem;
    font-weight: 500;
}

.stock-badge.low {
    background: var(--warning);
    color: white;
}

.stock-badge.out {
    background: var(--danger);
    color: white;
}

.product-info {
    flex: 1;
    margin-bottom: 12px;
}

.product-name {
    font-size: 1.1rem;
    font-weight: 500;
    color: var(--text-primary);
    margin-bottom: 4px;
    line-height: 1.3;
}

.product-category {
    color: var(--text-muted);
    margin-bottom: 8px;
}

.product-price {
    margin-bottom: 8px;
}

.price {
    font-size: 1.25rem;
    font-weight: 600;
    color: var(--success);
}

.cost-price {
    display: block;
    font-size: 0.8rem;
}

.product-stock {
    display: flex;
    justify-content: space-between;
    align-items: center;
    font-size: 0.85rem;
}

.stock-qty {
    color: var(--text-secondary);
}

.barcode {
    color: var(--text-muted);
    font-family: monospace;
}

.product-action {
    text-align: center;
    margin-top: auto;
}

.add-to-cart-btn {
    width: 100%;
    padding: 8px;
    font-weight: 500;
}

/* Cart Styles */
.cart-container {
    max-height: 300px;
    overflow-y: auto;
}

.empty-cart {
    color: var(--text-muted);
}

.cart-item {
    background: var(--bg-tertiary);
    border: 1px solid var(--border);
    border-radius: 8px;
    padding: 12px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.cart-item:hover {
    border-color: var(--text-muted);
}

.cart-item-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 8px;
}

.item-name {
    font-weight: 500;
    color: var(--text-primary);
    margin: 0;
}

.item-price {
    color: var(--text-secondary);
    font-size: 0.9rem;
}

.quantity-controls {
    display: flex;
    align-items: center;
    gap: 8px;
}

.quantity-btn {
    width: 28px;
    height: 28px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 0.9rem;
}

.quantity-display {
    min-width: 40px;
    text-align: center;
    font-weight: 500;
}

.remove-btn {
    margin-left: 8px;
    padding: 4px 8px;
    font-size: 0.8rem;
}

.item-total {
    text-align: right;
    font-weight: 600;
    color: var(--text-primary);
    font-size: 1.1rem;
}

/* Order Summary */
.order-summary {
    background: var(--bg-tertiary);
    border-radius: 8px;
    padding: 16px;
}

.summary-item {
    color: var(--text-primary);
}

.summary-total {
    color: var(--text-primary);
}

.discount-input {
    width: 80px;
}

.discount-input .form-control {
    text-align: center;
    padding: 4px 8px;
}

/* Payment Methods */
.payment-methods {
    background: var(--bg-tertiary);
    border-radius: 8px;
    padding: 12px;
}

.payment-methods .form-check {
    margin-bottom: 8px;
}

.payment-methods .form-check-label {
    color: var(--text-primary);
    font-weight: 500;
    padding: 6px 12px;
    border-radius: 6px;
    transition: all 0.2s ease;
}

.payment-methods .form-check-input:checked + .form-check-label {
    background: rgba(99, 102, 241, 0.15);
    color: var(--primary);
}

/* Card Options */
.card-options {
    animation: slideDown 0.3s ease-out;
}

@keyframes slideDown {
    from {
        opacity: 0;
        transform: translateY(-10px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

/* QR Code Modal Styles */
.qr-code-container {
    background: white;
    padding: 20px;
    border-radius: 12px;
    display: inline-block;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
}

.qr-code-image {
    max-width: 200px;
    max-height: 200px;
    border-radius: 8px;
}

.qr-instructions h6 {
    color: var(--text-primary);
    margin-bottom: 8px;
}

.status-indicator {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 12px;
    background: var(--bg-tertiary);
    border-radius: 8px;
    margin-top: 16px;
}

.status-text {
    color: var(--text-primary);
    font-weight: 500;
}

/* Camera/Scan Styles */
.camera-placeholder {
    border: 2px dashed var(--border);
}

.camera-placeholder i {
    opacity: 0.5;
}

/* Scrollbar Styling */
.product-grid::-webkit-scrollbar,
.cart-container::-webkit-scrollbar {
    width: 6px;
}

.product-grid::-webkit-scrollbar-track,
.cart-container::-webkit-scrollbar-track {
    background: var(--bg-primary);
    border-radius: 3px;
}

.product-grid::-webkit-scrollbar-thumb,
.cart-container::-webkit-scrollbar-thumb {
    background: var(--border);
    border-radius: 3px;
}

.product-grid::-webkit-scrollbar-thumb:hover,
.cart-container::-webkit-scrollbar-thumb:hover {
    background: var(--text-muted);
}

/* Responsive Design */
@media (max-width: 992px) {
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
    
    .pos-header {
        text-align: center;
    }
    
    .pos-header .d-flex {
        flex-direction: column;
        gap: 16px;
    }
    
    .col-lg-8, .col-lg-4 {
        flex: 0 0 100%;
        max-width: 100%;
    }
}

@media (max-width: 768px) {
    .pos-container {
        padding: 12px;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        max-height: 400px;
    }
    
    .search-filters .row > div {
        margin-bottom: 12px;
    }
    
    .pos-panel {
        margin-bottom: 20px;
    }
}

@media (max-width: 576px) {
    .product-grid {
        grid-template-columns: 1fr;
        max-height: 300px;
    }
    
    .pos-title {
        font-size: 1.5rem;
    }
    
    .product-card {
        padding: 12px;
    }
    
    .quantity-controls {
        flex-wrap: wrap;
        justify-content: center;
    }
    
    .quantity-btn {
        margin-bottom: 4px;
    }
}
</style>
@endpush

@push('scripts')
<script>
let cart = [];

// Add product to cart
function addToCart(productId, productName, price) {
    const existingItem = cart.find(item => item.product_id === productId);
    
    if (existingItem) {
        existingItem.quantity += 1;
    } else {
        cart.push({
            product_id: productId,
            name: productName,
            price: price,
            quantity: 1
        });
    }
    
    updateCart();
    showToast(`${productName} added to cart`, 'success');
}

// Update cart display
function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart text-center py-5">
                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                <h6 class="text-muted">Cart is empty</h6>
                <p class="text-muted small">Add products to get started</p>
            </div>
        `;
        subtotalEl.textContent = '$0.00';
        taxEl.textContent = '$0.00';
        totalEl.textContent = '$0.00';
        return;
    }
    
    let cartHTML = '';
    let subtotal = 0;
    
    cart.forEach((item, index) => {
        const itemTotal = item.price * item.quantity;
        subtotal += itemTotal;
        
        cartHTML += `
            <div class="cart-item" data-item-index="${index}">
                <div class="cart-item-header">
                    <div>
                        <h6 class="item-name">${item.name}</h6>
                        <div class="item-price">$${item.price.toFixed(2)} each</div>
                    </div>
                    <div class="item-total">$${itemTotal.toFixed(2)}</div>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <div class="quantity-controls">
                        <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                onclick="updateQuantity(${index}, -1)">
                            <i class="fas fa-minus"></i>
                        </button>
                        <span class="quantity-display">${item.quantity}</span>
                        <button class="btn btn-sm btn-outline-secondary quantity-btn" 
                                onclick="updateQuantity(${index}, 1)">
                            <i class="fas fa-plus"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger remove-btn" 
                                onclick="removeFromCart(${index})">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
    });
    
    cartItems.innerHTML = cartHTML;
    
    const tax = subtotal * 0.1; // 10% tax
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const total = subtotal + tax - discountAmount;
    
    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    taxEl.textContent = `$${tax.toFixed(2)}`;
    totalEl.textContent = `$${total.toFixed(2)}`;
    
    // Update mobile cart summary
    const mobileCartCount = document.getElementById('mobileCartCount');
    const mobileSubtotal = document.getElementById('mobileSubtotal');
    const mobileTax = document.getElementById('mobileTax');
    const mobileDiscount = document.getElementById('mobileDiscount');
    const mobileTotal = document.getElementById('mobileTotal');
    
    if (mobileCartCount) mobileCartCount.textContent = `${cart.length} ${cart.length === 1 ? 'item' : 'items'}`;
    if (mobileSubtotal) mobileSubtotal.textContent = `$${subtotal.toFixed(2)}`;
    if (mobileTax) mobileTax.textContent = `$${tax.toFixed(2)}`;
    if (mobileDiscount) mobileDiscount.textContent = `$${discountAmount.toFixed(2)}`;
    if (mobileTotal) mobileTotal.textContent = `$${total.toFixed(2)}`;
}

// Update item quantity
function updateQuantity(index, change) {
    cart[index].quantity += change;
    if (cart[index].quantity <= 0) {
        cart.splice(index, 1);
    }
    updateCart();
}

// Remove item from cart
function removeFromCart(index) {
    const itemName = cart[index].name;
    cart.splice(index, 1);
    updateCart();
    showToast(`${itemName} removed from cart`, 'info');
}

// Clear cart
function clearCart() {
    if (cart.length === 0) return;
    
    showConfirm('Are you sure you want to clear the cart?', function() {
        cart = [];
        updateCart();
        showToast('Cart cleared', 'warning');
    }, 'Clear Cart');
}

// Process payment
function processPayment() {
    if (cart.length === 0) {
        showToast('Cart is empty!', 'error');
        return;
    }
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    // Get selected payment method
    const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
    
    // Handle different payment methods
    if (paymentMethod === 'QR') {
        showQrPaymentModal(finalTotal);
        return;
    }
    
    if (paymentMethod === 'Card') {
        // For card payments, we'll process after card details are entered
        // The actual processing happens in the card modal handlers
        return;
    }
    
    processSaleToServer(finalTotal, paymentMethod, discount, tax, subtotal);
}

// Show QR Payment Modal
function showQrPaymentModal(amount) {
    document.getElementById('qrAmount').textContent = `$${amount.toFixed(2)}`;
    
    // Generate QR code data
    fetch('{{ route("pos.generate-qr") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            amount: amount
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('paymentRef').textContent = data.payment_ref;
            
            // Generate QR code using client-side library
            generateQrCode(data.qr_content, 'qrCodeDisplay');
            
            // Start payment monitoring
            monitorPayment(data.payment_ref);
        }
    })
    .catch(error => {
        console.error('Error generating QR code:', error);
        showToast('Error generating QR code', 'error');
    });
    
    const qrModal = new bootstrap.Modal(document.getElementById('qrPaymentModal'));
    qrModal.show();
}

// Generate QR code using QR API
function generateQrCode(content, elementId) {
    const element = document.getElementById(elementId);
    if (!element) {
        console.error('QR Code element not found:', elementId);
        return;
    }
    
    // Clear the element and create fresh content
    element.innerHTML = '';
    
    // Create loading spinner
    const loadingDiv = document.createElement('div');
    loadingDiv.id = 'qrCodeLoading';
    loadingDiv.className = 'text-center py-5';
    loadingDiv.innerHTML = `
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
        <p class="text-muted small mt-2">Generating QR code...</p>
    `;
    element.appendChild(loadingDiv);
    
    // Create QR image
    const qrImage = document.createElement('img');
    qrImage.id = 'qrCodeImage';
    qrImage.alt = 'Payment QR Code';
    qrImage.style.width = '200px';
    qrImage.style.height = '200px';
    qrImage.style.borderRadius = '8px';
    qrImage.style.display = 'none';
    
    // Build the QR code URL with payment data
    const qrData = encodeURIComponent(content);
    qrImage.src = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&margin=10&data=${qrData}`;
    
    // When image loads successfully
    qrImage.onload = function() {
        loadingDiv.style.display = 'none';
        qrImage.style.display = 'block';
        console.log('QR Code loaded successfully');
    };
    
    // Handle load error
    qrImage.onerror = function() {
        loadingDiv.innerHTML = `
            <div class="text-danger">
                <i class="fas fa-exclamation-triangle fa-2x mb-2"></i>
                <p>Failed to load QR Code</p>
                <button class="btn btn-sm btn-primary" onclick="generateQrCode('${content}', '${elementId}')">Retry</button>
            </div>
        `;
        console.error('QR Code failed to load');
    };
    
    element.appendChild(qrImage);
}

// Monitor payment status
function monitorPayment(paymentRef) {
    const statusElement = document.getElementById('paymentStatus');
    const confirmBtn = document.getElementById('confirmQrPayment');
    
    const checkStatus = setInterval(() => {
        fetch('{{ route("pos.check-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_ref: paymentRef
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'confirmed') {
                clearInterval(checkStatus);
                statusElement.innerHTML = `
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-lg me-2"></i>
                        <span class="status-text">Payment confirmed! Processing sale...</span>
                    </div>
                `;
                
                // Automatically process the sale
                processQrSale(paymentRef);
            }
        })
        .catch(error => {
            console.error('Error checking payment status:', error);
        });
    }, 3000); // Check every 3 seconds
    
    // Stop checking after 2 minutes
    setTimeout(() => {
        clearInterval(checkStatus);
        if (!confirmBtn.style.display || confirmBtn.style.display === 'none') {
            statusElement.innerHTML = `
                <div class="text-warning">
                    <i class="fas fa-clock fa-lg me-2"></i>
                    <span class="status-text">Payment timeout. Please try again.</span>
                </div>
            `;
        }
    }, 120000);
}

// Process sale after QR payment confirmation
function processQrSale(paymentRef) {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    // Process the sale with QR payment
    processSaleToServer(finalTotal, 'QR', discount, tax, subtotal);
    
    // Close modal after successful processing
    setTimeout(() => {
        const qrModal = bootstrap.Modal.getInstance(document.getElementById('qrPaymentModal'));
        qrModal.hide();
        showToast('Sale processed successfully with QR payment!', 'success');
    }, 2000);
}

// Simulate Mobile Payment
document.getElementById('manualPaymentBtn').addEventListener('click', function() {
    const paymentRef = document.getElementById('paymentRef').textContent;
    const amount = parseFloat(document.getElementById('qrAmount').textContent.replace('$', ''));
    
    // Simulate payment processing
    const statusElement = document.getElementById('paymentStatus');
    statusElement.innerHTML = `
        <div class="text-info">
            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
            <span class="status-text">Processing mobile payment...</span>
        </div>
    `;
    
    // Simulate processing delay
    setTimeout(() => {
        fetch('{{ route("pos.process-qr-payment") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                payment_ref: paymentRef,
                amount: amount
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                statusElement.innerHTML = `
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-lg me-2"></i>
                        <span class="status-text">Mobile payment confirmed!</span>
                    </div>
                `;
                document.getElementById('confirmQrPayment').style.display = 'inline-block';
                showToast('Mobile payment successful!', 'success');
            }
        })
        .catch(error => {
            console.error('Error processing payment:', error);
            showToast('Error processing payment', 'error');
        });
    }, 2000);
});

// Handle QR Payment Confirmation
document.getElementById('confirmQrPayment').addEventListener('click', function() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    processSaleToServer(finalTotal, 'QR', discount, tax, subtotal);
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('qrPaymentModal')).hide();
});

// Process sale to server
function processSaleToServer(finalTotal, paymentMethod, discount, tax, subtotal) {
    const saleData = {
        items: cart,
        customer_id: document.getElementById('customerSelect').value || null,
        total_amount: finalTotal,
        payment_method: paymentMethod,
        discount_percentage: discount,
        tax_amount: tax,
        subtotal: subtotal,
        _token: '{{ csrf_token() }}'
    };
    
    // Show processing message
    const buttons = document.querySelectorAll('button');
    buttons.forEach(btn => {
        if (btn.onclick && btn.onclick.toString().includes('processPayment')) {
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
            btn.disabled = true;
            
            // Restore after processing
            setTimeout(() => {
                btn.innerHTML = originalText;
                btn.disabled = false;
            }, 3000);
        }
    });
    
    fetch('{{ route("pos.process-sale") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(saleData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(`Sale processed successfully! Total: $${finalTotal.toFixed(2)}`, 'success');
            clearCart();
        } else {
            showToast('Error processing sale: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error processing sale', 'error');
    });
}

// Toggle fullscreen mode
function toggleFullscreen() {
    if (!document.fullscreenElement) {
        document.documentElement.requestFullscreen().catch(err => {
            console.log(`Error attempting to enable fullscreen: ${err.message}`);
        });
    } else {
        document.exitFullscreen();
    }
}

// Search functionality with barcode support
document.getElementById('productSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const productName = product.getAttribute('data-name');
        const barcode = product.getAttribute('data-barcode') || '';
        
        if (searchTerm === '' || 
            productName.includes(searchTerm) || 
            barcode.includes(searchTerm)) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
});

// Category filter
document.getElementById('categoryFilter').addEventListener('change', function() {
    const categoryId = this.value;
    const products = document.querySelectorAll('.product-item');
    
    products.forEach(product => {
        const productCategory = product.getAttribute('data-category');
        if (categoryId === '' || productCategory === categoryId) {
            product.style.display = 'block';
        } else {
            product.style.display = 'none';
        }
    });
});

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Ctrl+Enter to process payment
    if (e.ctrlKey && e.key === 'Enter') {
        e.preventDefault();
        processPayment();
    }
    
    // Escape to clear search
    if (e.key === 'Escape') {
        document.getElementById('productSearch').value = '';
        document.getElementById('productSearch').dispatchEvent(new Event('input'));
    }
});

// Payment method change handler
document.querySelectorAll('input[name="paymentMethod"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const cardOptions = document.getElementById('cardOptions');
        if (this.value === 'Card') {
            cardOptions.style.display = 'block';
        } else {
            cardOptions.style.display = 'none';
        }
    });
});

// Manual Card Payment Handler
document.getElementById('manualCardBtn').addEventListener('click', function() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    document.getElementById('cardAmount').value = finalTotal.toFixed(2);
    
    const cardModal = new bootstrap.Modal(document.getElementById('manualCardModal'));
    cardModal.show();
});

// Process Manual Card Payment
document.getElementById('processCardPayment').addEventListener('click', function() {
    const cardForm = document.getElementById('cardPaymentForm');
    if (!cardForm.checkValidity()) {
        cardForm.classList.add('was-validated');
        return;
    }
    
    const cardNumber = document.getElementById('cardNumber').value;
    const cardExpiry = document.getElementById('cardExpiry').value;
    const cardCvv = document.getElementById('cardCvv').value;
    const cardName = document.getElementById('cardName').value;
    
    // Basic validation
    if (!validateCardDetails(cardNumber, cardExpiry, cardCvv)) {
        showToast('Please check your card details', 'error');
        return;
    }
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    // Process the payment
    processSaleToServer(finalTotal, 'Card', discount, tax, subtotal);
    
    // Close modal
    bootstrap.Modal.getInstance(document.getElementById('manualCardModal')).hide();
    
    // Reset form
    cardForm.reset();
    cardForm.classList.remove('was-validated');
});

// Scan Card Handler
document.getElementById('scanCardBtn').addEventListener('click', function() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    document.getElementById('scanAmount').textContent = `$${finalTotal.toFixed(2)}`;
    
    const scanModal = new bootstrap.Modal(document.getElementById('scanCardModal'));
    scanModal.show();
});

// Start Camera for Card Scanning
document.getElementById('startCameraBtn').addEventListener('click', function() {
    const video = document.getElementById('cardScanner');
    const placeholder = document.querySelector('.camera-placeholder');
    const startBtn = this;
    const confirmBtn = document.getElementById('confirmScanPayment');
    
    // Check if camera API is supported
    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        showToast('Camera not supported in this browser', 'error');
        return;
    }
    
    // Request camera access
    navigator.mediaDevices.getUserMedia({ video: { facingMode: 'environment' } })
        .then(function(stream) {
            video.srcObject = stream;
            video.style.display = 'block';
            placeholder.style.display = 'none';
            startBtn.style.display = 'none';
            confirmBtn.style.display = 'inline-block';
            
            // Simulate card detection after 3 seconds
            setTimeout(() => {
                document.querySelector('.status-text').textContent = 'Card detected! Ready to process';
                document.querySelector('.status-indicator i').className = 'fas fa-check-circle me-2 text-success';
            }, 3000);
        })
        .catch(function(err) {
            console.error('Camera error:', err);
            showToast('Could not access camera: ' + err.message, 'error');
        });
});

// Confirm Scan Payment
document.getElementById('confirmScanPayment').addEventListener('click', function() {
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    // Process the payment
    processSaleToServer(finalTotal, 'Card', discount, tax, subtotal);
    
    // Close modal and stop camera
    const scanModal = bootstrap.Modal.getInstance(document.getElementById('scanCardModal'));
    scanModal.hide();
    
    // Stop camera stream
    const video = document.getElementById('cardScanner');
    if (video.srcObject) {
        video.srcObject.getTracks().forEach(track => track.stop());
    }
});

// Card number formatting
document.getElementById('cardNumber').addEventListener('input', function(e) {
    let value = e.target.value.replace(/\s/g, '').replace(/[^0-9]/gi, '');
    let formattedValue = value.match(/.{1,4}/g)?.join(' ') || value;
    e.target.value = formattedValue;
});

// Expiry date formatting
document.getElementById('cardExpiry').addEventListener('input', function(e) {
    let value = e.target.value.replace(/[^0-9]/g, '');
    if (value.length >= 2) {
        value = value.substring(0, 2) + '/' + value.substring(2, 4);
    }
    e.target.value = value;
});

// Basic card validation function
function validateCardDetails(cardNumber, expiry, cvv) {
    // Remove spaces from card number
    cardNumber = cardNumber.replace(/\s/g, '');
    
    // Basic Luhn algorithm check
    if (!luhnCheck(cardNumber)) return false;
    
    // Expiry date validation
    const [month, year] = expiry.split('/');
    if (!month || !year || month < 1 || month > 12) return false;
    
    const currentYear = new Date().getFullYear() % 100;
    const currentMonth = new Date().getMonth() + 1;
    
    if (parseInt(year) < currentYear || (parseInt(year) === currentYear && parseInt(month) < currentMonth)) {
        return false;
    }
    
    // CVV validation
    if (!cvv || cvv.length < 3 || cvv.length > 4) return false;
    
    return true;
}

// Luhn algorithm for credit card validation
function luhnCheck(cardNumber) {
    let sum = 0;
    let isEven = false;
    
    for (let i = cardNumber.length - 1; i >= 0; i--) {
        let digit = parseInt(cardNumber.charAt(i));
        
        if (isEven) {
            digit *= 2;
            if (digit > 9) digit -= 9;
        }
        
        sum += digit;
        isEven = !isEven;
    }
    
    return sum % 10 === 0;
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush