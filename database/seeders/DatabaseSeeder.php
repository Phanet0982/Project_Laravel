<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Category;
use App\Models\Supplier;
use App\Models\Customer;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'username' => 'admin',
            'password' => Hash::make('admin123'),
            'role' => 'admin'
        ]);

        // Create categories
        $categories = [
            'Beverages',
            'Snacks',
            'Electronics',
            'Clothing',
            'Books'
        ];

        foreach ($categories as $category) {
            Category::create(['name' => $category]);
        }

        // Create suppliers
        $suppliers = [
            ['name' => 'Coca-Cola Company', 'phone' => '123-456-7890', 'address' => '123 Main St, City'],
            ['name' => 'PepsiCo', 'phone' => '098-765-4321', 'address' => '456 Oak Ave, Town'],
            ['name' => 'Samsung Electronics', 'phone' => '555-123-4567', 'address' => '789 Tech Blvd, Metro']
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }

        // Create customers
        $customers = [
            ['name' => 'John Doe', 'phone' => '111-222-3333', 'email' => 'john@email.com', 'member_type' => 'regular'],
            ['name' => 'Jane Smith', 'phone' => '444-555-6666', 'email' => 'jane@email.com', 'member_type' => 'vip'],
            ['name' => 'Bob Johnson', 'phone' => '777-888-9999', 'email' => 'bob@email.com', 'member_type' => 'regular']
        ];

        foreach ($customers as $customer) {
            Customer::create($customer);
        }

        // Create sample products
        $products = [
            ['category_id' => 1, 'supplier_id' => 1, 'name' => 'Coca Cola', 'barcode' => '123456789', 'cost_price' => 1.00, 'sale_price' => 1.50, 'qty' => 100],
            ['category_id' => 1, 'supplier_id' => 2, 'name' => 'Pepsi', 'barcode' => '987654321', 'cost_price' => 0.95, 'sale_price' => 1.45, 'qty' => 80],
            ['category_id' => 2, 'supplier_id' => 1, 'name' => 'Chips', 'barcode' => '456789123', 'cost_price' => 1.50, 'sale_price' => 2.00, 'qty' => 50],
            ['category_id' => 2, 'supplier_id' => 2, 'name' => 'Chocolate', 'barcode' => '789123456', 'cost_price' => 2.50, 'sale_price' => 3.50, 'qty' => 30]
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}