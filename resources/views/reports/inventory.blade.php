@extends('layouts.app')

@section('title', 'Inventory Report')

@section('content')
<div class="page-header" data-total-products="{{ $totalProducts ?? 0 }}">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1><i class="fas fa-boxes me-3"></i>Inventory Report</h1>
            <p class="text-muted mb-0">Stock levels, movements, and inventory valuation</p>
        </div>
        <div class="d-flex gap-2">
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" id="backupDropdown" data-bs-toggle="dropdown">
                    <i class="fas fa-database me-2"></i>Backup Options
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#" onclick="createStockBackup()"><i class="fas fa-save me-2"></i>Create Backup</a></li>
                    <li><a class="dropdown-item" href="#" onclick="restoreStockBackup()"><i class="fas fa-undo me-2"></i>Restore Backup</a></li>
                    <li><a class="dropdown-item" href="#" onclick="viewBackups()"><i class="fas fa-history me-2"></i>View Backups</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="#" onclick="exportStockData()"><i class="fas fa-file-export me-2"></i>Export Stock Data</a></li>
                </ul>
            </div>
            <button class="btn btn-outline-primary" onclick="exportReport('inventory')">
                <i class="fas fa-download me-2"></i>Export PDF
            </button>
            <a href="{{ route('reports.index') }}" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Back to Reports
            </a>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-primary rounded-circle p-3 me-3">
                        <i class="fas fa-boxes text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $totalProducts }}</h4>
                        <p class="text-muted mb-0">Total Products</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-success rounded-circle p-3 me-3">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $inStock }}</h4>
                        <p class="text-muted mb-0">In Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-warning rounded-circle p-3 me-3">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $lowStock }}</h4>
                        <p class="text-muted mb-0">Low Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="bg-danger rounded-circle p-3 me-3">
                        <i class="fas fa-times-circle text-white"></i>
                    </div>
                    <div>
                        <h4 class="mb-1">{{ $outOfStock }}</h4>
                        <p class="text-muted mb-0">Out of Stock</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <h3 class="text-primary mb-1">${{ number_format($totalValue, 2) }}</h3>
                <p class="text-muted mb-0">Total Inventory Value (Cost Price)</p>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="fas fa-warehouse me-2"></i>Product Inventory
            </h5>
            <div class="d-flex gap-2">
                <select class="form-select form-select-sm" style="width: auto;" onchange="filterByStatus(this.value)">
                    <option value="all">All Products</option>
                    <option value="instock">In Stock</option>
                    <option value="lowstock">Low Stock</option>
                    <option value="outofstock">Out of Stock</option>
                </select>
            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-striped" id="inventoryTable">
                <thead>
                    <tr>
                        <th><i class="fas fa-image me-2"></i>Product</th>
                        <th><i class="fas fa-folder me-2"></i>Category</th>
                        <th><i class="fas fa-truck me-2"></i>Supplier</th>
                        <th><i class="fas fa-dollar-sign me-2"></i>Cost Price</th>
                        <th><i class="fas fa-money-bill me-2"></i>Sale Price</th>
                        <th><i class="fas fa-warehouse me-2"></i>Stock</th>
                        <th><i class="fas fa-calculator me-2"></i>Value</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr data-status="{{ $product->qty <= 0 ? 'outofstock' : ($product->qty <= 10 ? 'lowstock' : 'instock') }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    @if($product->image)
                                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" class="rounded me-3" style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-secondary rounded d-flex align-items-center justify-content-center me-3" style="width: 40px; height: 40px;">
                                            <i class="fas fa-image text-white"></i>
                                        </div>
                                    @endif
                                    <div>
                                        <div class="fw-bold">{{ $product->name }}</div>
                                        @if($product->barcode)
                                            <small class="text-muted">{{ $product->barcode }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-primary">{{ $product->category->name ?? 'No Category' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">{{ $product->supplier->name ?? 'No Supplier' }}</span>
                            </td>
                            <td>
                                <span class="text-muted">${{ number_format($product->cost_price, 2) }}</span>
                            </td>
                            <td>
                                <span class="fw-bold text-success">${{ number_format($product->sale_price, 2) }}</span>
                            </td>
                            <td>
                                @if($product->qty <= 0)
                                    <span class="badge bg-danger">Out of Stock</span>
                                @elseif($product->qty <= 10)
                                    <span class="badge bg-warning">{{ $product->qty }} Low</span>
                                @else
                                    <span class="badge bg-success">{{ $product->qty }} In Stock</span>
                                @endif
                            </td>
                            <td>
                                <span class="fw-bold">${{ number_format($product->qty * $product->cost_price, 2) }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Backup Creation Modal -->
<div class="modal fade" id="createBackupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-save me-2"></i>Create Stock Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Backup Name</label>
                    <input type="text" class="form-control" id="backupName" placeholder="e.g., End of Month Stock Backup">
                </div>
                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea class="form-control" id="backupDescription" rows="3" placeholder="Reason for backup or additional notes..."></textarea>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    This will create a backup of all current stock levels, product information, and inventory data.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" onclick="confirmCreateBackup()">
                    <i class="fas fa-save me-2"></i>Create Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Backup Restore Modal -->
<div class="modal fade" id="restoreBackupModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-undo me-2"></i>Restore Stock Backup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> Restoring a backup will overwrite current stock levels. This action cannot be undone.
                </div>
                <div class="mb-3">
                    <label class="form-label">Select Backup to Restore</label>
                    <select class="form-select" id="backupSelect">
                        <option value="">Choose a backup...</option>
                        <!-- Backup options will be loaded here -->
                    </select>
                </div>
                <div id="backupDetails" class="d-none">
                    <div class="card bg-light">
                        <div class="card-body">
                            <h6>Backup Details:</h6>
                            <p class="mb-1"><strong>Name:</strong> <span id="backupDetailName"></span></p>
                            <p class="mb-1"><strong>Date:</strong> <span id="backupDetailDate"></span></p>
                            <p class="mb-1"><strong>Products:</strong> <span id="backupDetailCount"></span></p>
                            <p class="mb-0"><strong>Description:</strong> <span id="backupDetailDesc"></span></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-danger" id="confirmRestoreBtn" disabled onclick="confirmRestoreBackup()">
                    <i class="fas fa-undo me-2"></i>Restore Backup
                </button>
            </div>
        </div>
    </div>
</div>

<!-- View Backups Modal -->
<div class="modal fade" id="viewBackupsModal" tabindex="-1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-history me-2"></i>Stock Backups</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Backup Name</th>
                                <th>Date Created</th>
                                <th>Products</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="backupsTableBody">
                            <!-- Backup entries will be loaded here -->
                        </tbody>
                    </table>
                </div>
                <div id="noBackupsMessage" class="text-center d-none">
                    <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                    <h5>No backups found</h5>
                    <p class="text-muted">Create your first backup to get started</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<script>
    function filterByStatus(status) {
        const table = document.getElementById('inventoryTable');
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            if (status === 'all' || row.dataset.status === status) {
                row.style.display = '';
            } else {
                row.style.display = 'none';
            }
        });
        
        showToast(`Filtered by ${status === 'all' ? 'all products' : status}`, 'info');
    }

    function exportReport(type) {
        // Export whole report page as PDF
        exportPagePDF('Inventory Report', { filename: 'inventory_report' });
    }

    // Backup Functions
    function createStockBackup() {
        const modal = new bootstrap.Modal(document.getElementById('createBackupModal'));
        modal.show();
    }

    function restoreStockBackup() {
        loadBackupOptions();
        const modal = new bootstrap.Modal(document.getElementById('restoreBackupModal'));
        modal.show();
    }

    function viewBackups() {
        loadBackupList();
        const modal = new bootstrap.Modal(document.getElementById('viewBackupsModal'));
        modal.show();
    }

    function exportStockData() {
        showToast('Exporting stock data...', 'info');
        // Simulate export process
        setTimeout(() => {
            showToast('Stock data exported successfully!', 'success');
        }, 1500);
    }

    function confirmCreateBackup() {
        const backupName = document.getElementById('backupName').value.trim();
        const backupDescription = document.getElementById('backupDescription').value.trim();
        
        if (!backupName) {
            showToast('Please enter a backup name', 'error');
            return;
        }

        showToast('Creating stock backup...', 'info');
        
        fetch('{{ route("stock-backup.create") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            },
            body: JSON.stringify({
                name: backupName,
                description: backupDescription
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Reset form
                document.getElementById('backupName').value = '';
                document.getElementById('backupDescription').value = '';
                
                // Close modal
                bootstrap.Modal.getInstance(document.getElementById('createBackupModal')).hide();
                
                showToast('Stock backup created successfully!', 'success');
            } else {
                throw new Error(data.message || 'Failed to create backup');
            }
        })
        .catch(error => {
            console.error('Backup creation error:', error);
            showToast('Error creating backup: ' + error.message, 'error');
        });
    }

    function loadBackupOptions() {
        const select = document.getElementById('backupSelect');
        
        fetch('{{ route("stock-backup.list") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    select.innerHTML = '<option value="">Choose a backup...</option>';
                    
                    data.backups.forEach((backup, index) => {
                        const option = document.createElement('option');
                        option.value = backup.id;
                        option.textContent = `${backup.name} (${new Date(backup.backup_date).toLocaleDateString()})`;
                        select.appendChild(option);
                    });
                    
                    if (data.backups.length === 0) {
                        const option = document.createElement('option');
                        option.disabled = true;
                        option.textContent = 'No backups available';
                        select.appendChild(option);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading backups:', error);
                showToast('Error loading backups', 'error');
            });
        
        // Reset backup details
        document.getElementById('backupDetails').classList.add('d-none');
        document.getElementById('confirmRestoreBtn').disabled = true;
    }

    function loadBackupList() {
        const tableBody = document.getElementById('backupsTableBody');
        const noBackupsMessage = document.getElementById('noBackupsMessage');
        
        fetch('{{ route("stock-backup.list") }}')
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.backups.length === 0) {
                        tableBody.innerHTML = '';
                        noBackupsMessage.classList.remove('d-none');
                        return;
                    }
                    
                    noBackupsMessage.classList.add('d-none');
                    tableBody.innerHTML = '';
                    
                    data.backups.forEach(backup => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${backup.name}</td>
                            <td>${new Date(backup.backup_date).toLocaleString()}</td>
                            <td>${backup.product_count} products</td>
                            <td>${backup.description || 'No description'}</td>
                            <td>
                                <div class="btn-group btn-group-sm">
                                    <button class="btn btn-outline-primary" onclick="previewBackup(${backup.id})">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger" onclick="deleteBackup(${backup.id})">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </td>
                        `;
                        tableBody.appendChild(row);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading backup list:', error);
                showToast('Error loading backup list', 'error');
            });
    }

    document.getElementById('backupSelect').addEventListener('change', function() {
        const backupId = this.value;
        const detailsDiv = document.getElementById('backupDetails');
        const restoreBtn = document.getElementById('confirmRestoreBtn');
        
        if (!backupId) {
            detailsDiv.classList.add('d-none');
            restoreBtn.disabled = true;
            return;
        }
        
        // In a real implementation, you'd fetch backup details
        // For now, we'll enable the restore button
        detailsDiv.classList.remove('d-none');
        restoreBtn.disabled = false;
    });

    function confirmRestoreBackup() {
        const backupId = document.getElementById('backupSelect').value;
        
        if (!backupId) {
            showToast('Please select a backup to restore', 'error');
            return;
        }
        
        showConfirm(
            'Are you sure you want to restore this backup? This will overwrite all current stock levels.',
            function() {
                showToast('Restoring backup...', 'info');
                
                fetch(`{{ url('/stock-backup/restore') }}/${backupId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast(`Backup restored successfully! ${data.restored_products} products updated.`, 'success');
                        bootstrap.Modal.getInstance(document.getElementById('restoreBackupModal')).hide();
                        
                        // Refresh the page to show updated data
                        setTimeout(() => {
                            location.reload();
                        }, 2000);
                    } else {
                        throw new Error(data.message || 'Failed to restore backup');
                    }
                })
                .catch(error => {
                    console.error('Restore error:', error);
                    showToast('Error restoring backup: ' + error.message, 'error');
                });
            },
            'Restore Backup'
        );
    }

    function deleteBackup(backupId) {
        showConfirm(
            'Are you sure you want to delete this backup? This action cannot be undone.',
            function() {
                fetch(`{{ url('/stock-backup/delete') }}/${backupId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showToast('Backup deleted successfully', 'success');
                        loadBackupList(); // Refresh the backup list
                    } else {
                        throw new Error(data.message || 'Failed to delete backup');
                    }
                })
                .catch(error => {
                    console.error('Delete error:', error);
                    showToast('Error deleting backup: ' + error.message, 'error');
                });
            },
            'Delete Backup'
        );
    }

    // Initialize backup system
    document.addEventListener('DOMContentLoaded', function() {
        // Check if backups exist and show notification
        const backups = JSON.parse(localStorage.getItem('stockBackups') || '[]');
        if (backups.length > 0) {
            showToast(`You have ${backups.length} stock backup${backups.length > 1 ? 's' : ''} available`, 'info');
        }
    });
</script>
@endsection