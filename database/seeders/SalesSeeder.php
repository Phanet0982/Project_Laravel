<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SalesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create some sample sales for testing
        $customers = \App\Models\Customer::all();
        $products = \App\Models\Product::all();
        
        if ($customers->count() > 0 && $products->count() > 0) {
            // Create sales for the last 30 days
            for ($i = 0; $i < 15; $i++) {
                $sale = \App\Models\Sale::create([
                    'user_id' => 1, // Admin user
                    'customer_id' => $customers->random()->id,
                    'total_amount' => rand(50, 500),
                    'created_at' => now()->subDays(rand(0, 30))
                ]);
                
                // Add 1-3 items to each sale
                $itemCount = rand(1, 3);
                for ($j = 0; $j < $itemCount; $j++) {
                    $product = $products->random();
                    \App\Models\SaleItem::create([
                        'sale_id' => $sale->id,
                        'product_id' => $product->id,
                        'quantity' => rand(1, 3),
                        'unit_price' => $product->sale_price,
                        'total_price' => $product->sale_price * rand(1, 3)
                    ]);
                }
            }
        }
    }
}
