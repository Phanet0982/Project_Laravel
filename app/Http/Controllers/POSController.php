<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\StockTransaction;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

class POSController extends Controller
{
    public function index()
    {
        $products = Product::with(['category', 'supplier'])->get();
        $customers = Customer::all();
        $categories = Category::all();

        return view('pos.index', compact('products', 'customers', 'categories'));
    }

    public function processSale(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'payment_method' => 'required|string|in:Cash,Card,QR',
            'discount_percentage' => 'required|numeric|min:0|max:100',
            'tax_amount' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0'
        ]);

        DB::beginTransaction();
        
        try {
            // Generate unique invoice number
            $invoiceNumber = 'INV-' . now()->format('Ymd') . '-' . str_pad(random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            
            // Create sale record
            $sale = Sale::create([
                'user_id' => Auth::id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $request->total_amount,
                'discount' => $request->discount_percentage,
                'tax' => $request->tax_amount,
                'final_total' => $request->total_amount,
                'payment_type' => $request->payment_method,
            ]);

            // If this is a QR payment, link it to the payment record
            if ($request->payment_method === 'QR' && isset($request->payment_ref)) {
                $payment = Payment::where('payment_reference', $request->payment_ref)->first();
                if ($payment) {
                    $payment->update([
                        'sale_id' => $sale->id,
                        'status' => 'confirmed'
                    ]);
                }
            }

            // Process each item
            foreach ($request->items as $item) {
                // Create sale item
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['price'],
                    'total_price' => $item['price'] * $item['quantity'],
                ]);

                // Update product stock
                $product = Product::find($item['product_id']);
                if ($product->qty >= $item['quantity']) {
                    $product->decrement('qty', $item['quantity']);

                    // Create stock transaction
                    StockTransaction::create([
                        'product_id' => $item['product_id'],
                        'transaction_type' => 'Stock out',
                        'quantity' => $item['quantity'],
                        'reference' => 'SALE-' . $sale->id,
                        'notes' => 'Sale transaction - ' . $invoiceNumber,
                    ]);
                } else {
                    throw new \Exception('Insufficient stock for product: ' . $product->name);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Sale processed successfully',
                'sale_id' => $sale->id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $request->total_amount
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Error processing sale: ' . $e->getMessage()
            ], 500);
        }
    }

    public function generateQrCode(Request $request)
    {
        try {
            Log::info('QR Generation Request:', $request->all());
            
            $request->validate([
                'amount' => 'required|numeric|min:0.01',
                'sale_id' => 'nullable|exists:sales,id'
            ]);
            
            Log::info('Validation passed');

            // Generate unique payment reference
            $paymentRef = 'PAY-' . now()->format('Ymd') . '-' . strtoupper(Str::random(8));
            Log::info('Payment reference generated: ' . $paymentRef);
            
            // Create payment record manually to avoid mass assignment issues
            $payment = new Payment();
            $payment->payment_reference = $paymentRef;
            $payment->sale_id = $request->sale_id;
            $payment->amount = $request->amount;
            $payment->status = 'pending';
            $payment->payment_method = 'QR';
            $payment->notes = 'QR payment generated for POS transaction';
            $payment->save();
            
            Log::info('Payment record created: ' . $payment->id);
            
            // Create payment data
            $paymentData = [
                'reference' => $paymentRef,
                'amount' => $request->amount,
                'sale_id' => $request->sale_id,
                'timestamp' => now()->timestamp,
                'merchant' => 'Sale Management System',
                'payment_id' => $payment->id
            ];

            return response()->json([
                'success' => true,
                'payment_ref' => $paymentRef,
                'payment_id' => $payment->id,
                'payment_data' => json_encode($paymentData),
                'qr_content' => "Payment:{$paymentRef}|Amount:{$request->amount}|Merchant:Sale Management System"
            ]);
            
        } catch (\Exception $e) {
            Log::error('QR Generation Error: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Error generating QR code: ' . $e->getMessage(),
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function checkPaymentStatus(Request $request)
    {
        $request->validate([
            'payment_ref' => 'required|string'
        ]);

        // Find the payment record
        $payment = Payment::where('payment_reference', $request->payment_ref)->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment reference not found'
            ], 404);
        }

        // Simulate payment processing time (increase chance over time)
        $timeElapsed = now()->diffInSeconds($payment->created_at);
        
        // Increase confirmation probability over time (up to 95% after 30 seconds)
        $baseChance = min(95, 30 + ($timeElapsed / 2)); // Start at 30%, max 95%
        $shouldConfirm = rand(1, 100) <= $baseChance;
        
        if ($shouldConfirm && $payment->status === 'pending') {
            // Update payment status to confirmed
            $payment->update([
                'status' => 'confirmed',
                'confirmed_at' => now()
            ]);
            
            return response()->json([
                'success' => true,
                'status' => 'confirmed',
                'message' => 'Payment confirmed successfully',
                'confirmed_at' => now(),
                'payment_id' => $payment->id
            ]);
        } elseif ($payment->status === 'confirmed') {
            // Already confirmed
            return response()->json([
                'success' => true,
                'status' => 'confirmed',
                'message' => 'Payment already confirmed',
                'confirmed_at' => $payment->confirmed_at,
                'payment_id' => $payment->id
            ]);
        } else {
            // Still pending
            return response()->json([
                'success' => true,
                'status' => 'pending',
                'message' => 'Payment still processing',
                'estimated_time' => max(0, 30 - $timeElapsed) . ' seconds'
            ]);
        }
    }

    public function processQrPayment(Request $request)
    {
        $request->validate([
            'payment_ref' => 'required|string',
            'amount' => 'required|numeric|min:0.01'
        ]);

        // Find the payment record
        $payment = Payment::where('payment_reference', $request->payment_ref)->first();
        
        if (!$payment) {
            return response()->json([
                'success' => false,
                'message' => 'Payment reference not found'
            ], 404);
        }

        // Verify amount matches
        if (abs($payment->amount - $request->amount) > 0.01) {
            return response()->json([
                'success' => false,
                'message' => 'Payment amount mismatch'
            ], 400);
        }

        // Log the payment processing
        Log::info('QR Payment processed', [
            'payment_ref' => $request->payment_ref,
            'amount' => $request->amount,
            'payment_id' => $payment->id,
            'timestamp' => now()
        ]);
        
        // Simulate 90% success rate
        $success = rand(1, 100) <= 90;
        
        if ($success) {
            // Update payment status
            $payment->update([
                'status' => 'processing',
                'processed_at' => now(),
                'notes' => 'Payment processed via QR scan'
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'QR payment processed successfully',
                'payment_ref' => $request->payment_ref,
                'amount' => $request->amount,
                'payment_id' => $payment->id,
                'processed_at' => now()
            ]);
        } else {
            // Update payment status to failed
            $payment->update([
                'status' => 'failed',
                'notes' => 'Payment processing failed'
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Payment processing failed. Please try again.'
            ], 400);
        }
    }
}