@extends('layouts.app')

@section('title', 'Point of Sale - Amazon Style')

@section('content')
<div class="amazon-pos">
    <!-- Amazon-style Header -->
    <div class="amazon-header">
        <div class="header-content">
            <div class="header-left">
                <h1 class="header-title">Point of Sale</h1>
                <p class="header-subtitle">Amazon Professional Retail System</p>
            </div>
            <div class="header-right">
                <div class="header-actions">
                    <button class="btn btn-outline-light btn-sm" id="toggleFullscreen">
                        <i class="fas fa-expand"></i>
                    </button>
                    <button class="btn btn-amazon-primary" id="processPaymentHeader">
                        <i class="fas fa-shopping-cart me-2"></i>Complete Order
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="amazon-container">
        <div class="amazon-grid">
            <!-- Left Panel - Product Catalog -->
            <div class="amazon-panel catalog-panel">
                <div class="panel-header">
                    <h2 class="panel-title">
                        <i class="fas fa-boxes me-2"></i>Product Catalog
                    </h2>
                    <div class="header-controls">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text" class="search-input" id="productSearch" placeholder="Search products, barcodes...">
                        </div>
                        <select class="category-filter" id="categoryFilter">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                
                <div class="catalog-content">
                    <div class="product-grid" id="productGrid">
                        @foreach($products as $product)
                            <div class="product-card" 
                                 data-category="{{ $product->category_id }}" 
                                 data-name="{{ strtolower($product->name) }}"
                                 data-barcode="{{ $product->barcode ?? '' }}">
                                <div class="product-image">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                    @else
                                        <div class="image-placeholder">
                                            <i class="fas fa-box"></i>
                                        </div>
                                    @endif
                                    @if($product->qty <= 5)
                                        <span class="stock-badge low">Low Stock</span>
                                    @elseif($product->qty == 0)
                                        <span class="stock-badge out">Out of Stock</span>
                                    @endif
                                </div>
                                <div class="product-info">
                                    <h3 class="product-name">{{ $product->name }}</h3>
                                    <p class="product-category">{{ $product->category->name }}</p>
                                    <div class="product-meta">
                                        <span class="product-price">${{ number_format($product->sale_price, 2) }}</span>
                                        <span class="product-stock">{{ $product->qty }} in stock</span>
                                    </div>
                                    @if($product->barcode)
                                        <span class="product-barcode">#{{ $product->barcode }}</span>
                                    @endif
                                </div>
                                <div class="product-actions">
                                    <button class="btn-add-to-cart" 
                                            onclick="addToCart({{ $product->id }}, '{{ $product->name }}', {{ $product->sale_price }})">
                                        <i class="fas fa-plus"></i>
                                        Add to Order
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Right Panel - Order Management -->
            <div class="amazon-panel order-panel">
                <div class="panel-header">
                    <h2 class="panel-title">
                        <i class="fas fa-receipt me-2"></i>Current Order
                    </h2>
                    <span class="order-count" id="cartCount">0 items</span>
                </div>
                
                <div class="order-content">
                    <!-- Cart Items -->
                    <div class="cart-container">
                        <div id="cartItems" class="cart-items">
                            <div class="empty-cart">
                                <i class="fas fa-shopping-cart cart-icon"></i>
                                <h3>Your order is empty</h3>
                                <p>Add products to start building your order</p>
                            </div>
                        </div>
                    </div>

                    <!-- Order Summary -->
                    <div class="order-summary">
                        <div class="summary-section">
                            <h4>Order Summary</h4>
                            
                            <div class="summary-row">
                                <span>Subtotal</span>
                                <span id="subtotal">$0.00</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Tax (10%)</span>
                                <span id="tax">$0.00</span>
                            </div>
                            
                            <div class="summary-row discount-row">
                                <span>Discount</span>
                                <div class="discount-control">
                                    <input type="number" 
                                           class="discount-input" 
                                           id="discount" 
                                           value="0" 
                                           min="0" 
                                           max="100" 
                                           placeholder="%">
                                    <span class="discount-percent">%</span>
                                </div>
                            </div>
                            
                            <div class="summary-total">
                                <span>Total</span>
                                <span id="total">$0.00</span>
                            </div>
                        </div>

                        <!-- Customer Selection -->
                        <div class="customer-section">
                            <h4>Customer</h4>
                            <select class="customer-select" id="customerSelect">
                                <option value="">Walk-in Customer</option>
                                @foreach($customers as $customer)
                                    <option value="{{ $customer->id }}">
                                        {{ $customer->name }}
                                        @if(isset($customer->customer_type) && $customer->customer_type == 'vip')
                                            <span class="vip-badge">VIP</span>
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Payment Method -->
                        <div class="payment-section">
                            <h4>Payment Method</h4>
                            <div class="payment-options">
                                <div class="payment-option" data-method="Cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                    <span>Cash</span>
                                </div>
                                <div class="payment-option active" data-method="Card">
                                    <i class="fas fa-credit-card"></i>
                                    <span>Card</span>
                                </div>
                                <div class="payment-option" data-method="QR">
                                    <i class="fas fa-qrcode"></i>
                                    <span>QR Code</span>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="action-buttons">
                            <button class="btn btn-amazon-secondary btn-block" id="clearCart">
                                <i class="fas fa-trash me-2"></i>Clear Order
                            </button>
                            <button class="btn btn-amazon-primary btn-block btn-lg" id="processPayment">
                                <i class="fas fa-credit-card me-2"></i>Complete Order
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modals will be added here -->
@endsection

@push('styles')
<style>
/* Amazon POS Styles */
.amazon-pos {
    background: #ffffff;
    min-height: 100vh;
    font-family: 'Amazon Ember', 'Segoe UI', Roboto, sans-serif;
}

/* Header Styles */
.amazon-header {
    background: #232f3e;
    color: white;
    padding: 20px 0;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.header-content {
    display: flex;
    justify-content: space-between;
    align-items: center;
    max-width: 1600px;
    margin: 0 auto;
    padding: 0 20px;
}

.header-title {
    font-size: 28px;
    font-weight: 700;
    margin: 0 0 5px 0;
    color: white;
}

.header-subtitle {
    font-size: 16px;
    color: #cccccc;
    margin: 0;
}

.header-actions {
    display: flex;
    gap: 15px;
    align-items: center;
}

.btn-amazon-primary {
    background: #ff9900;
    color: #000000;
    border: none;
    padding: 12px 24px;
    border-radius: 4px;
    font-weight: 600;
    font-size: 16px;
    transition: all 0.2s ease;
}

.btn-amazon-primary:hover {
    background: #e68a00;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255,153,0,0.3);
}

.btn-amazon-secondary {
    background: #333333;
    color: white;
    border: 1px solid #555555;
    padding: 12px 24px;
    border-radius: 4px;
    font-weight: 500;
    transition: all 0.2s ease;
}

.btn-amazon-secondary:hover {
    background: #444444;
    border-color: #666666;
}

/* Container Styles */
.amazon-container {
    max-width: 1600px;
    margin: 0 auto;
    padding: 20px;
}

.amazon-grid {
    display: grid;
    grid-template-columns: 2fr 1fr;
    gap: 25px;
    height: calc(100vh - 180px);
}

/* Panel Styles */
.amazon-panel {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 15px rgba(0,0,0,0.08);
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.panel-header {
    background: #f0f2f2;
    padding: 20px;
    border-bottom: 1px solid #e0e0e0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.panel-title {
    font-size: 20px;
    font-weight: 600;
    color: #333333;
    margin: 0;
}

.header-controls {
    display: flex;
    gap: 15px;
    align-items: center;
}

/* Search Box */
.search-box {
    position: relative;
    min-width: 250px;
}

.search-icon {
    position: absolute;
    left: 12px;
    top: 50%;
    transform: translateY(-50%);
    color: #666666;
}

.search-input {
    width: 100%;
    padding: 10px 15px 10px 40px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    transition: all 0.2s ease;
}

.search-input:focus {
    outline: none;
    border-color: #ff9900;
    box-shadow: 0 0 0 2px rgba(255,153,0,0.2);
}

.category-filter {
    padding: 10px 15px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 14px;
}

/* Catalog Content */
.catalog-content {
    flex: 1;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.product-grid {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 15px;
}

/* Product Card */
.product-card {
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    background: white;
    display: flex;
    flex-direction: column;
    height: 100%;
}

.product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.12);
    border-color: #ff9900;
}

.product-image {
    position: relative;
    height: 180px;
    background: #f8f8f8;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.product-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.image-placeholder {
    color: #999999;
    font-size: 48px;
}

.stock-badge {
    position: absolute;
    top: 10px;
    right: 10px;
    padding: 4px 10px;
    border-radius: 12px;
    font-size: 12px;
    font-weight: 600;
    color: white;
}

.stock-badge.low {
    background: #ff9900;
}

.stock-badge.out {
    background: #cc0000;
}

.product-info {
    padding: 15px;
    flex: 1;
    display: flex;
    flex-direction: column;
}

.product-name {
    font-size: 16px;
    font-weight: 600;
    color: #333333;
    margin: 0 0 8px 0;
    line-height: 1.3;
}

.product-category {
    font-size: 13px;
    color: #666666;
    margin: 0 0 12px 0;
}

.product-meta {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.product-price {
    font-size: 18px;
    font-weight: 700;
    color: #b12704;
}

.product-stock {
    font-size: 12px;
    color: #007185;
    background: #f0f8f0;
    padding: 2px 8px;
    border-radius: 10px;
}

.product-barcode {
    font-size: 11px;
    color: #999999;
    font-family: monospace;
}

.product-actions {
    padding: 0 15px 15px;
}

.btn-add-to-cart {
    width: 100%;
    background: #ffd814;
    color: #000000;
    border: none;
    padding: 12px;
    border-radius: 20px;
    font-weight: 600;
    font-size: 14px;
    cursor: pointer;
    transition: all 0.2s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.btn-add-to-cart:hover {
    background: #f7ca00;
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(255,216,20,0.3);
}

/* Order Panel */
.order-panel {
    display: flex;
    flex-direction: column;
}

.order-content {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.order-count {
    background: #ff9900;
    color: #000000;
    padding: 4px 12px;
    border-radius: 12px;
    font-size: 14px;
    font-weight: 600;
}

.cart-container {
    flex: 1;
    overflow-y: auto;
    padding: 15px;
}

.cart-items {
    min-height: 200px;
}

.empty-cart {
    text-align: center;
    padding: 40px 20px;
    color: #666666;
}

.cart-icon {
    font-size: 48px;
    margin-bottom: 15px;
    color: #cccccc;
}

.empty-cart h3 {
    font-size: 20px;
    color: #333333;
    margin: 0 0 10px 0;
}

.empty-cart p {
    font-size: 14px;
    margin: 0;
}

/* Order Summary */
.order-summary {
    background: #f8f8f8;
    border-top: 1px solid #e0e0e0;
    padding: 20px;
}

.summary-section h4,
.customer-section h4,
.payment-section h4 {
    font-size: 16px;
    font-weight: 600;
    color: #333333;
    margin: 0 0 15px 0;
}

.summary-row {
    display: flex;
    justify-content: space-between;
    padding: 8px 0;
    font-size: 14px;
}

.summary-row:not(:last-child) {
    border-bottom: 1px solid #e0e0e0;
}

.discount-row {
    align-items: center;
}

.discount-control {
    display: flex;
    align-items: center;
    gap: 5px;
}

.discount-input {
    width: 60px;
    padding: 5px;
    border: 1px solid #ddd;
    border-radius: 4px;
    text-align: center;
}

.discount-percent {
    font-size: 12px;
    color: #666666;
}

.summary-total {
    display: flex;
    justify-content: space-between;
    padding: 15px 0;
    font-size: 18px;
    font-weight: 700;
    color: #b12704;
    border-top: 2px solid #333333;
    margin-top: 10px;
}

/* Customer Section */
.customer-select {
    width: 100%;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 14px;
    margin-bottom: 20px;
}

.vip-badge {
    background: #ff9900;
    color: #000000;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 11px;
    font-weight: 600;
    margin-left: 8px;
}

/* Payment Section */
.payment-options {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}

.payment-option {
    flex: 1;
    text-align: center;
    padding: 15px 10px;
    border: 2px solid #e0e0e0;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    background: white;
}

.payment-option:hover {
    border-color: #ff9900;
}

.payment-option.active {
    border-color: #ff9900;
    background: #fff8e1;
}

.payment-option i {
    display: block;
    font-size: 24px;
    margin-bottom: 8px;
    color: #333333;
}

.payment-option span {
    font-size: 13px;
    font-weight: 500;
    color: #333333;
}

/* Action Buttons */
.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 10px;
}

.btn-block {
    width: 100%;
    padding: 12px;
    font-size: 16px;
    font-weight: 600;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-block.btn-lg {
    padding: 15px;
    font-size: 18px;
}

/* Scrollbar Styling */
.product-grid::-webkit-scrollbar,
.cart-container::-webkit-scrollbar {
    width: 8px;
}

.product-grid::-webkit-scrollbar-track,
.cart-container::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 4px;
}

.product-grid::-webkit-scrollbar-thumb,
.cart-container::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 4px;
}

.product-grid::-webkit-scrollbar-thumb:hover,
.cart-container::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .amazon-grid {
        grid-template-columns: 1fr;
        height: auto;
    }
    
    .product-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .header-content {
        flex-direction: column;
        gap: 15px;
        text-align: center;
    }
    
    .header-actions {
        width: 100%;
        justify-content: center;
    }
    
    .product-grid {
        grid-template-columns: 1fr;
    }
    
    .payment-options {
        flex-direction: column;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Amazon POS JavaScript
let cart = [];
let selectedPaymentMethod = 'Card';

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
    showToast(`${productName} added to order`, 'success');
}

// Update cart display
function updateCart() {
    const cartItems = document.getElementById('cartItems');
    const cartCount = document.getElementById('cartCount');
    const subtotalEl = document.getElementById('subtotal');
    const taxEl = document.getElementById('tax');
    const totalEl = document.getElementById('total');
    
    cartCount.textContent = `${cart.length} ${cart.length === 1 ? 'item' : 'items'}`;
    
    if (cart.length === 0) {
        cartItems.innerHTML = `
            <div class="empty-cart">
                <i class="fas fa-shopping-cart cart-icon"></i>
                <h3>Your order is empty</h3>
                <p>Add products to start building your order</p>
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
            <div class="cart-item" style="background: white; border: 1px solid #e0e0e0; border-radius: 8px; padding: 15px; margin-bottom: 12px;">
                <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 10px;">
                    <div>
                        <h4 style="margin: 0 0 5px 0; font-size: 16px; color: #333333;">${item.name}</h4>
                        <div style="font-size: 14px; color: #666666;">$${item.price.toFixed(2)} each</div>
                    </div>
                    <div style="text-align: right;">
                        <div style="font-size: 18px; font-weight: 700; color: #b12704;">$${itemTotal.toFixed(2)}</div>
                    </div>
                </div>
                <div style="display: flex; align-items: center; justify-content: space-between;">
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <button onclick="updateQuantity(${index}, -1)" 
                                style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-minus" style="font-size: 12px;"></i>
                        </button>
                        <span style="min-width: 30px; text-align: center; font-weight: 500;">${item.quantity}</span>
                        <button onclick="updateQuantity(${index}, 1)" 
                                style="width: 32px; height: 32px; border-radius: 50%; border: 1px solid #ddd; background: white; cursor: pointer; display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-plus" style="font-size: 12px;"></i>
                        </button>
                    </div>
                    <button onclick="removeFromCart(${index})" 
                            style="color: #cc0000; background: none; border: none; cursor: pointer; font-size: 14px; display: flex; align-items: center; gap: 5px;">
                        <i class="fas fa-trash"></i> Remove
                    </button>
                </div>
            </div>
        `;
    });
    
    cartItems.innerHTML = cartHTML;
    
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const total = subtotal + tax - discountAmount;
    
    subtotalEl.textContent = `$${subtotal.toFixed(2)}`;
    taxEl.textContent = `$${tax.toFixed(2)}`;
    totalEl.textContent = `$${total.toFixed(2)}`;
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
    showToast(`${itemName} removed from order`, 'info');
}

// Clear cart
function clearCart() {
    if (cart.length === 0) return;
    
    showConfirm('Are you sure you want to clear this order?', function() {
        cart = [];
        updateCart();
        showToast('Order cleared', 'warning');
    }, 'Clear Order');
}

// Process payment
function processPayment() {
    if (cart.length === 0) {
        showToast('Order is empty!', 'error');
        return;
    }
    
    const subtotal = cart.reduce((sum, item) => sum + (item.price * item.quantity), 0);
    const tax = subtotal * 0.1;
    const discount = parseFloat(document.getElementById('discount').value || 0);
    const discountAmount = subtotal * (discount / 100);
    const finalTotal = subtotal + tax - discountAmount;
    
    // Process based on payment method
    if (selectedPaymentMethod === 'QR') {
        showQrPaymentModal(finalTotal);
    } else if (selectedPaymentMethod === 'Card') {
        showCardPaymentOptions(finalTotal);
    } else {
        processSaleToServer(finalTotal, selectedPaymentMethod, discount, tax, subtotal);
    }
}

// Payment method selection
document.addEventListener('click', function(e) {
    if (e.target.closest('.payment-option')) {
        document.querySelectorAll('.payment-option').forEach(option => {
            option.classList.remove('active');
        });
        e.target.closest('.payment-option').classList.add('active');
        selectedPaymentMethod = e.target.closest('.payment-option').dataset.method;
    }
});

// Search functionality
document.getElementById('productSearch').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase().trim();
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const productName = product.getAttribute('data-name');
        const barcode = product.getAttribute('data-barcode') || '';
        
        if (searchTerm === '' || 
            productName.includes(searchTerm) || 
            barcode.includes(searchTerm)) {
            product.style.display = 'flex';
        } else {
            product.style.display = 'none';
        }
    });
});

// Category filter
document.getElementById('categoryFilter').addEventListener('change', function() {
    const categoryId = this.value;
    const products = document.querySelectorAll('.product-card');
    
    products.forEach(product => {
        const productCategory = product.getAttribute('data-category');
        if (categoryId === '' || productCategory === categoryId) {
            product.style.display = 'flex';
        } else {
            product.style.display = 'none';
        }
    });
});

// Event listeners
document.getElementById('processPayment').addEventListener('click', processPayment);
document.getElementById('processPaymentHeader').addEventListener('click', processPayment);
document.getElementById('clearCart').addEventListener('click', clearCart);
document.getElementById('discount').addEventListener('input', updateCart);

// Initialize
document.addEventListener('DOMContentLoaded', function() {
    updateCart();
});
</script>
@endpush