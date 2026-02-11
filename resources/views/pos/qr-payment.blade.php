@extends('layouts.app')

@section('title', 'QR Payment')

@section('content')
<div class="qr-payment-container">
    <div class="payment-card" data-payment-ref="{{ $payment_ref }}">
        <div class="card-header text-center">
            <h2 class="mb-2"><i class="fas fa-qrcode text-primary"></i> QR Payment</h2>
            <p class="text-muted">Scan QR code to make payment</p>
        </div>
        
        <div class="card-body">
            @if($payment_ref)
                <!-- Payment details when reference is provided -->
                <div class="payment-details mb-4" id="paymentDetails">
                    <div class="text-center mb-4">
                        <div class="qr-display bg-white p-3 rounded d-inline-block" id="customerQrCode">
                            <!-- QR Code will be generated here -->
                        </div>
                    </div>
                    
                    <div class="payment-info">
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Payment Reference:</label>
                            <div class="info-value text-monospace" id="displayPaymentRef">{{ $payment_ref }}</div>
                        </div>
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Amount:</label>
                            <div class="info-value text-success h5" id="displayAmount">$0.00</div>
                        </div>
                        <div class="info-item mb-3">
                            <label class="form-label fw-bold">Merchant:</label>
                            <div class="info-value">Sale Management System</div>
                        </div>
                        <div class="info-item">
                            <label class="form-label fw-bold">Status:</label>
                            <div class="info-value">
                                <span class="badge bg-warning" id="paymentStatus">Waiting for payment</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="payment-actions text-center">
                    <button class="btn btn-primary btn-lg mb-3" id="scanQrBtn">
                        <i class="fas fa-camera me-2"></i>Scan QR Code
                    </button>
                    <button class="btn btn-success btn-lg w-100" id="confirmPaymentBtn" style="display: none;">
                        <i class="fas fa-check me-2"></i>Confirm Payment
                    </button>
                </div>
            @else
                <!-- Enter payment reference -->
                <div class="enter-reference">
                    <div class="mb-4 text-center">
                        <i class="fas fa-barcode fa-3x text-primary mb-3"></i>
                        <h4>Enter Payment Reference</h4>
                        <p class="text-muted">Please enter the payment reference from your receipt</p>
                    </div>
                    
                    <form id="paymentForm">
                        <div class="mb-3">
                            <label class="form-label">Payment Reference</label>
                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </span>
                                <input type="text" class="form-control" id="paymentRefInput" 
                                       placeholder="Enter payment reference..." required>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary btn-lg w-100">
                            <i class="fas fa-arrow-right me-2"></i>Continue to Payment
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Camera Modal for Scanning -->
<div class="modal fade" id="cameraModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-camera me-2"></i>Scan QR Code
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <div class="camera-container">
                    <div class="camera-placeholder bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-camera fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-0">Camera feed will appear here</p>
                            <small class="text-muted">Click "Start Camera" to begin scanning</small>
                        </div>
                    </div>
                    <video id="qrScanner" style="display: none; width: 100%; height: 300px; object-fit: cover;" autoplay muted></video>
                </div>
                
                <div class="scan-status mt-3">
                    <div class="status-indicator bg-light p-3 rounded">
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="fas fa-search me-2 text-primary"></i>
                            <span class="status-text">Ready to scan</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline-secondary" id="startScannerBtn">
                    <i class="fas fa-video me-2"></i>Start Camera
                </button>
                <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>Cancel
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.qr-payment-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    padding: 20px;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.payment-card {
    background: white;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.2);
    width: 100%;
    max-width: 500px;
    overflow: hidden;
}

.card-header {
    background: linear-gradient(135deg, #4e54c8 0%, #8f94fb 100%);
    color: white;
    padding: 30px 20px;
}

.card-body {
    padding: 30px 20px;
}

.qr-display {
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    margin: 0 auto;
}

.info-item {
    padding: 12px 15px;
    background: #f8f9fa;
    border-radius: 8px;
    border-left: 4px solid #4e54c8;
}

.info-label {
    color: #6c757d;
    font-size: 0.9rem;
    margin-bottom: 5px;
}

.info-value {
    font-weight: 500;
    color: #212529;
}

.camera-placeholder {
    border: 2px dashed #dee2e6;
}

.camera-placeholder i {
    opacity: 0.5;
}

.status-indicator {
    transition: all 0.3s ease;
}

.status-indicator.scanning {
    background: #e8f5e8 !important;
    border-color: #28a745 !important;
}

.status-indicator.found {
    background: #d4edda !important;
    border-color: #28a745 !important;
}

.payment-actions .btn-lg {
    padding: 12px 24px;
    font-size: 1.1rem;
    font-weight: 500;
}

@media (max-width: 576px) {
    .qr-payment-container {
        padding: 10px;
    }
    
    .payment-card {
        border-radius: 10px;
    }
    
    .card-header, .card-body {
        padding: 20px 15px;
    }
}
</style>
@endpush

@push('scripts')
<script>
(function() {
    // Payment reference from data attribute
    const paymentRef = document.querySelector('.payment-card').dataset.paymentRef;
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });
        
        // Handle payment reference logic
        if (paymentRef) {
            loadPaymentDetails(paymentRef);
        } else {
            // Handle form submission for payment reference
            document.getElementById('paymentForm').addEventListener('submit', function(e) {
                e.preventDefault();
                const inputPaymentRef = document.getElementById('paymentRefInput').value.trim();
                
                if (inputPaymentRef) {
                    window.location.href = '{{ route("pos.qr-payment") }}/' + encodeURIComponent(inputPaymentRef);
                }
            });
        }
    });
    
    function loadPaymentDetails(paymentRef) {
        // In a real implementation, this would fetch from an API
        // For demo, we'll simulate the data
        document.getElementById('displayAmount').textContent = '$' + (Math.random() * 100 + 10).toFixed(2);
        document.getElementById('displayPaymentRef').textContent = paymentRef;
        
        // Generate QR code for payment reference
        generatePaymentQrCode(paymentRef);
        
        // Scan QR Code functionality
        document.getElementById('scanQrBtn').addEventListener('click', function() {
            const cameraModal = new bootstrap.Modal(document.getElementById('cameraModal'));
            cameraModal.show();
        });
        
        // Start Camera for Scanning
        document.getElementById('startScannerBtn').addEventListener('click', function() {
            const video = document.getElementById('qrScanner');
            const placeholder = document.querySelector('.camera-placeholder');
            const startBtn = this;
            const statusElement = document.querySelector('.status-indicator');
            const statusText = document.querySelector('.status-text');
            
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
                    
                    statusElement.classList.add('scanning');
                    statusText.textContent = 'Scanning for QR codes...';
                    
                    // Simulate QR detection and processing
                    setTimeout(() => {
                        statusElement.classList.remove('scanning');
                        statusElement.classList.add('found');
                        statusText.textContent = 'QR code detected! Processing payment...';
                        
                        // Process the actual payment
                        processPaymentFromQr(paymentRef);
                    }, 2000);
                })
                .catch(function(err) {
                    console.error('Camera error:', err);
                    showToast('Could not access camera: ' + err.message, 'error');
                });
        });
        
        // Process payment from QR scan
        function processPaymentFromQr(paymentRef) {
            const amount = document.getElementById('displayAmount').textContent.replace('$', '');
            
            fetch('{{ route("pos.process-qr-payment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_ref: paymentRef,
                    amount: parseFloat(amount)
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update payment record in database
                    updatePaymentStatus(paymentRef, 'confirmed');
                    
                    // Update UI to show success
                    document.getElementById('paymentStatus').className = 'badge bg-success';
                    document.getElementById('paymentStatus').textContent = 'Payment Processed';
                    document.getElementById('confirmPaymentBtn').style.display = 'block';
                    document.getElementById('scanQrBtn').style.display = 'none';
                    
                    const statusElement = document.querySelector('.status-indicator');
                    const statusText = document.querySelector('.status-text');
                    statusElement.classList.remove('found');
                    statusElement.classList.add('bg-success');
                    statusText.innerHTML = '<i class="fas fa-check-circle me-2"></i>Payment Processed Successfully!';
                    
                    showToast('Payment processed successfully!', 'success');
                    
                    // Stop camera
                    const video = document.getElementById('qrScanner');
                    if (video.srcObject) {
                        video.srcObject.getTracks().forEach(track => track.stop());
                    }
                    
                    // Close modal after delay
                    setTimeout(() => {
                        const cameraModal = bootstrap.Modal.getInstance(document.getElementById('cameraModal'));
                        cameraModal.hide();
                    }, 3000);
                } else {
                    throw new Error(data.message || 'Payment processing failed');
                }
            })
            .catch(error => {
                console.error('Payment processing error:', error);
                showToast('Payment processing failed: ' + error.message, 'error');
                
                const statusElement = document.querySelector('.status-indicator');
                const statusText = document.querySelector('.status-text');
                statusElement.classList.remove('found');
                statusElement.classList.add('bg-danger');
                statusText.innerHTML = '<i class="fas fa-times-circle me-2"></i>Payment Failed';
            });
        }
        
        // Update payment status in database
        function updatePaymentStatus(paymentRef, status) {
            fetch('{{ route("pos.check-payment") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    payment_ref: paymentRef
                })
            }).catch(error => console.error('Status update error:', error));
        }
        
        // Confirm Payment
        document.getElementById('confirmPaymentBtn').addEventListener('click', function() {
            const paymentRef = document.getElementById('displayPaymentRef').textContent;
            const amount = document.getElementById('displayAmount').textContent;
            
            // In a real implementation, this would send to server
            // For demo, we'll just show success
            showToast(`Payment of ${amount} confirmed for reference ${paymentRef}!`, 'success');
            
            // Redirect or show success message
            setTimeout(() => {
                window.location.href = '{{ route("pos.qr-payment") }}';
            }, 2000);
        });
    }
    
    function generatePaymentQrCode(paymentRef) {
        const qrContent = `Payment:${paymentRef}|Amount:${document.getElementById('displayAmount').textContent}|Merchant:Sale Management System`;
        generateQrCode(qrContent, 'customerQrCode');
    }
    
    // Generate QR code using client-side library
    function generateQrCode(content, elementId) {
        const element = document.getElementById(elementId);
        element.innerHTML = '';
        
        // Create a simple visual representation
        const qrContainer = document.createElement('div');
        qrContainer.className = 'position-relative';
        qrContainer.style.width = '200px';
        qrContainer.style.height = '200px';
        qrContainer.style.backgroundColor = '#000';
        qrContainer.style.display = 'grid';
        qrContainer.style.gridTemplateColumns = 'repeat(21, 1fr)';
        qrContainer.style.gridTemplateRows = 'repeat(21, 1fr)';
        qrContainer.style.gap = '1px';
        qrContainer.style.padding = '10px';
        
        // Generate pseudo-random pattern based on content
        const seed = content.split('').reduce((a, b) => a + b.charCodeAt(0), 0);
        Math.seed = seed;
        
        for (let i = 0; i < 441; i++) {
            const cell = document.createElement('div');
            cell.style.backgroundColor = Math.random() > 0.5 ? '#000' : '#fff';
            
            // Ensure finder patterns (corners)
            if ((i < 21 && i < 7) || (i < 147 && i % 21 < 7) || (i > 420 && i % 21 > 14)) {
                cell.style.backgroundColor = '#000';
            }
            if ((i < 21 && i > 14) || (i < 147 && i % 21 > 14) || (i > 420 && i % 21 < 7)) {
                cell.style.backgroundColor = '#000';
            }
            if ((i > 126 && i < 147) || (i > 294 && i < 315)) {
                cell.style.backgroundColor = '#000';
            }
            
            qrContainer.appendChild(cell);
        }
        
        element.appendChild(qrContainer);
        
        // Add content text below
        const contentDiv = document.createElement('div');
        contentDiv.className = 'mt-2 small text-muted';
        contentDiv.textContent = content.substring(0, 30) + '...';
        element.appendChild(contentDiv);
    }
})();
</script>
@endpush