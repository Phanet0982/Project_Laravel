@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-cog me-3"></i>System Settings</h1>
            <p class="text-muted mb-0">Configure system preferences, manage categories, and business settings</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary">
                <i class="fas fa-download me-2"></i>Backup Data
            </button>
            <button class="btn btn-success">
                <i class="fas fa-save me-2"></i>Save All Changes
            </button>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-xl-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fas fa-folder me-2"></i>Product Categories
                    </h5>
                    <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                        <i class="fas fa-plus me-1"></i>Add Category
                    </button>
                </div>
            </div>
            <div class="card-body">
                <div class="list-group list-group-flush" id="categoriesList">
                    @forelse($categories as $category)
                    <div class="list-group-item category-item d-flex justify-content-between align-items-center" data-category-id="{{ $category->id }}">
                        <div class="d-flex align-items-center">
                            <div class="bg-{{ $category->color ?? 'primary' }} rounded-circle p-2 me-3">
                                <i class="fas fa-{{ $category->icon ?? 'folder' }} text-white"></i>
                            </div>
                            <div>
                                <div class="fw-bold">{{ $category->name }}</div>
                                <small class="text-muted">{{ $category->description ?? 'No description' }}</small>
                            </div>
                        </div>
                        <div class="d-flex gap-1">
                            <button class="btn btn-sm btn-outline-primary" onclick="editCategory({{ $category->id }})" title="Edit">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-outline-danger" onclick="deleteCategory({{ $category->id }})" title="Delete">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                    </div>
                    @empty
                    <div class="list-group-item text-center text-muted py-4">
                        <i class="fas fa-folder-open fa-2x mb-2"></i>
                        <p>No categories found</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-2">
    <div class="col-xl-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-store me-2"></i>Business Settings
                </h5>
            </div>
            <div class="card-body">
                <form id="businessSettingsForm">
                    @csrf
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-store"></i>
                                Business Name
                            </label>
                            <input type="text" class="form-control" name="business_name" value="{{ $businessSettings['business_name'] }}" placeholder="Enter business name" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-phone"></i>
                                Business Phone
                            </label>
                            <input type="tel" class="form-control" name="business_phone" value="{{ $businessSettings['business_phone'] }}" placeholder="Enter business phone">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-envelope"></i>
                                Business Email
                            </label>
                            <input type="email" class="form-control" name="business_email" value="{{ $businessSettings['business_email'] }}" placeholder="Enter business email">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-percentage"></i>
                                Default Tax Rate (%)
                            </label>
                            <input type="number" class="form-control" name="tax_rate" value="{{ $businessSettings['tax_rate'] }}" step="0.01" min="0" max="100" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">
                                <i class="fas fa-map-marker-alt"></i>
                                Business Address
                            </label>
                            <textarea class="form-control" name="business_address" rows="3" placeholder="Enter complete business address">{{ $businessSettings['business_address'] }}</textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-dollar-sign"></i>
                                Currency
                            </label>
                            <select class="form-select" name="currency" required>
                                <option value="USD" {{ $businessSettings['currency'] == 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                                <option value="EUR" {{ $businessSettings['currency'] == 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                                <option value="GBP" {{ $businessSettings['currency'] == 'GBP' ? 'selected' : '' }}>GBP - British Pound</option>
                                <option value="JPY" {{ $businessSettings['currency'] == 'JPY' ? 'selected' : '' }}>JPY - Japanese Yen</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">
                                <i class="fas fa-clock"></i>
                                Timezone
                            </label>
                            <select class="form-select" name="timezone" required>
                                <option value="UTC" {{ $businessSettings['timezone'] == 'UTC' ? 'selected' : '' }}>UTC - Coordinated Universal Time</option>
                                <option value="EST" {{ $businessSettings['timezone'] == 'EST' ? 'selected' : '' }}>EST - Eastern Standard Time</option>
                                <option value="PST" {{ $businessSettings['timezone'] == 'PST' ? 'selected' : '' }}>PST - Pacific Standard Time</option>
                                <option value="GMT" {{ $businessSettings['timezone'] == 'GMT' ? 'selected' : '' }}>GMT - Greenwich Mean Time</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Business Settings
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-xl-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bell me-2"></i>System Preferences
                </h5>
            </div>
            <div class="card-body">
                <form id="systemPreferencesForm">
                    @csrf
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="lowStockAlerts" name="low_stock_alerts" {{ $systemPreferences['low_stock_alerts'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="lowStockAlerts">
                            <i class="fas fa-exclamation-triangle me-2"></i>Low Stock Alerts
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="emailNotifications" name="email_notifications" {{ $systemPreferences['email_notifications'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="emailNotifications">
                            <i class="fas fa-envelope me-2"></i>Email Notifications
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="autoBackup" name="auto_backup" {{ $systemPreferences['auto_backup'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="autoBackup">
                            <i class="fas fa-cloud-upload-alt me-2"></i>Auto Backup
                        </label>
                    </div>
                    <div class="form-check form-switch mb-3">
                        <input class="form-check-input" type="checkbox" id="receiptPrinting" name="receipt_printing" {{ $systemPreferences['receipt_printing'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="receiptPrinting">
                            <i class="fas fa-print me-2"></i>Auto Print Receipts
                        </label>
                    </div>
                    
                    <hr>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-exclamation-triangle"></i>
                            Low Stock Threshold
                        </label>
                        <input type="number" class="form-control" name="low_stock_threshold" value="{{ $systemPreferences['low_stock_threshold'] }}" min="1" required>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-receipt"></i>
                            Receipt Footer Text
                        </label>
                        <textarea class="form-control" name="receipt_footer_text" rows="2" placeholder="Thank you for your business!">{{ $systemPreferences['receipt_footer_text'] }}</textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Preferences
                    </button>
                </form>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shield-alt me-2"></i>System Information
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="border-end">
                            <h5 class="text-primary mb-1">v2.1.0</h5>
                            <small class="text-muted">Version</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <h5 class="text-success mb-1">Active</h5>
                        <small class="text-muted">License</small>
                    </div>
                    <div class="col-6">
                        <div class="border-end">
                            <h5 class="text-info mb-1">MySQL</h5>
                            <small class="text-muted">Database</small>
                        </div>
                    </div>
                    <div class="col-6">
                        <h5 class="text-warning mb-1">Laravel</h5>
                        <small class="text-muted">Framework</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Category Modal -->
<div class="modal fade" id="categoryModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="categoryForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="categoryModalLabel">
                        <i class="fas fa-folder-plus me-2"></i>
                        Add Category
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="categoryId" name="category_id">
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-tag me-1"></i>
                            Category Name
                        </label>
                        <input type="text" class="form-control" id="categoryName" name="name" placeholder="Enter category name" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-align-left me-1"></i>
                            Description
                        </label>
                        <textarea class="form-control" id="categoryDescription" name="description" rows="2" placeholder="Enter category description"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-palette me-1"></i>
                            Color
                        </label>
                        <select class="form-select" id="categoryColor" name="color">
                            <option value="primary">Primary (Blue)</option>
                            <option value="success">Success (Green)</option>
                            <option value="info">Info (Cyan)</option>
                            <option value="warning">Warning (Yellow)</option>
                            <option value="danger">Danger (Red)</option>
                            <option value="secondary">Secondary (Gray)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">
                            <i class="fas fa-icons me-1"></i>
                            Icon
                        </label>
                        <select class="form-select" id="categoryIcon" name="icon">
                            <option value="folder">Folder</option>
                            <option value="box">Box</option>
                            <option value="tag">Tag</option>
                            <option value="shopping-bag">Shopping Bag</option>
                            <option value="utensils">Utensils</option>
                            <option value="laptop">Laptop</option>
                            <option value="tshirt">T-Shirt</option>
                            <option value="wine-bottle">Wine Bottle</option>
                            <option value="cookie-bite">Cookie</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save me-2"></i>Save Category
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
<style>
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .form-check-input:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.25rem rgba(99, 102, 241, 0.25);
    }
    
    /* Smooth delete animation */
    .list-group-item {
        transition: all 0.3s ease-out;
    }
    
    /* Enhanced category item styling */
    .category-item {
        border-left: 3px solid transparent;
        transition: all 0.2s ease;
    }
    
    .category-item:hover {
        border-left-color: var(--primary-color);
        transform: translateX(2px);
    }
    
    .list-group-item {
        background-color: transparent;
        border-color: var(--dark-border);
        color: var(--dark-text);
    }
    
    .list-group-item:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
</style>
@endpush
@endsection

@push('scripts')
<script>
// Category Management Functions
function editCategory(categoryId) {
    // Fetch category data
    fetch(`/categories/${categoryId}`, {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Populate modal with category data
            document.getElementById('categoryId').value = data.category.id;
            document.getElementById('categoryName').value = data.category.name;
            document.getElementById('categoryDescription').value = data.category.description || '';
            document.getElementById('categoryColor').value = data.category.color || 'primary';
            document.getElementById('categoryIcon').value = data.category.icon || 'folder';
            document.getElementById('categoryModalLabel').textContent = 'Edit Category';
            
            // Show modal
            const modal = new bootstrap.Modal(document.getElementById('categoryModal'));
            modal.show();
        } else {
            showToast('Error loading category data', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error loading category data', 'error');
    });
}

function deleteCategory(categoryId) {
    // Get the category name from the DOM
    const categoryElement = event.target.closest('.list-group-item');
    const categoryName = categoryElement.querySelector('.fw-bold').textContent;
    
    showConfirm(
        `Are you sure you want to delete the category "${categoryName}"? This action cannot be undone and will affect all associated products.`,
        function() {
            fetch(`/categories/${categoryId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the category element with animation
                    categoryElement.style.transition = 'all 0.3s ease-out';
                    categoryElement.style.transform = 'translateX(100%)';
                    categoryElement.style.opacity = '0';
                    setTimeout(() => {
                        categoryElement.remove();
                        showToast(data.message, 'success');
                    }, 300);
                } else {
                    showToast(data.message || 'Error deleting category', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Error deleting category', 'error');
            });
        },
        'Delete Category'
    );
}

// Reset modal when opening for new category
function resetCategoryModal() {
    document.getElementById('categoryId').value = '';
    document.getElementById('categoryName').value = '';
    document.getElementById('categoryDescription').value = '';
    document.getElementById('categoryColor').value = 'primary';
    document.getElementById('categoryIcon').value = 'folder';
    document.getElementById('categoryModalLabel').textContent = 'Add Category';
}

// Handle form submission
document.getElementById('categoryForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const categoryId = document.getElementById('categoryId').value;
    const formData = {
        name: document.getElementById('categoryName').value,
        description: document.getElementById('categoryDescription').value,
        color: document.getElementById('categoryColor').value,
        icon: document.getElementById('categoryIcon').value,
        _token: '{{ csrf_token() }}'
    };
    
    const url = categoryId ? `/categories/${categoryId}` : '/categories';
    const method = categoryId ? 'PUT' : 'POST';
    
    fetch(url, {
        method: method,
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify(formData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
            // Close modal and refresh page
            const modal = bootstrap.Modal.getInstance(document.getElementById('categoryModal'));
            modal.hide();
            setTimeout(() => location.reload(), 1000);
        } else {
            showToast(data.message || 'Error saving category', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error saving category', 'error');
    });
});

// Reset modal when opened for adding new category
document.querySelector('[data-bs-target="#categoryModal"]').addEventListener('click', function() {
    if (!document.getElementById('categoryId').value) {
        resetCategoryModal();
    }
});

// Handle business settings form submission
document.getElementById('businessSettingsForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route('settings.business.update') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Error saving business settings', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error saving business settings', 'error');
    });
});

// Handle system preferences form submission
document.getElementById('systemPreferencesForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('{{ route('settings.preferences.update') }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showToast(data.message, 'success');
        } else {
            showToast(data.message || 'Error saving system preferences', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error saving system preferences', 'error');
    });
});

</script>
@endpush