@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="page-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-cog me-3"></i>System Settings</h1>
            <p class="text-muted mb-0">Configure system preferences and business settings</p>
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
</style>
@endpush
@endsection

@push('scripts')
<script>
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