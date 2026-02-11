<?php
// Test QR Generation
require_once 'vendor/autoload.php';

use Illuminate\Http\Request;
use App\Http\Controllers\POSController;

// Create a mock request
$request = new \Illuminate\Http\Request();
$request->setMethod('POST');
$request->request->add([
    'amount' => 25.50
]);

// Add CSRF token
$request->headers->set('X-CSRF-TOKEN', 'test-token');

try {
    $controller = new POSController();
    $response = $controller->generateQrCode($request);
    
    echo "QR Generation Test Result:\n";
    echo "Status Code: " . $response->status() . "\n";
    echo "Response: " . $response->content() . "\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}