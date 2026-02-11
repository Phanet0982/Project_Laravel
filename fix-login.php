<?php
/**
 * POS Management System - Login Fix Script
 * 
 * Run this script if you can't login with admin/admin123
 * This will reset the admin password to the correct hash
 */

echo "🔧 POS System - Login Fix Script\n";
echo "===============================\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the project root directory\n";
    echo "   (The directory containing the 'artisan' file)\n";
    exit(1);
}

// Check if vendor directory exists
if (!file_exists('vendor/autoload.php')) {
    echo "❌ Error: Composer dependencies not found\n";
    echo "   Please run: composer install\n";
    exit(1);
}

// Load Laravel
require_once 'vendor/autoload.php';

try {
    $app = require_once 'bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    
    // Bootstrap Laravel
    $kernel->bootstrap();
    
    echo "🔍 Checking current admin user...\n";
    
    // Check if admin user exists
    $admin = \App\Models\User::where('username', 'admin')->first();
    
    if (!$admin) {
        echo "❌ No admin user found. Creating new admin user...\n";
        
        $admin = \App\Models\User::create([
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'admin'
        ]);
        
        echo "✅ Admin user created successfully!\n";
    } else {
        echo "✅ Admin user found. Updating password...\n";
        
        $admin->update([
            'password' => \Illuminate\Support\Facades\Hash::make('admin123')
        ]);
        
        echo "✅ Admin password updated successfully!\n";
    }
    
    echo "\n🔐 Login Credentials:\n";
    echo "   Username: admin\n";
    echo "   Password: admin123\n\n";
    
    echo "🌐 Access your system at: http://127.0.0.1:8000\n";
    echo "   (Make sure to run 'php artisan serve' first)\n\n";
    
    echo "🎉 Login issue fixed! You can now login to your POS system.\n";
    
} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "\n💡 Alternative solutions:\n";
    echo "1. Run: php artisan admin:reset-password\n";
    echo "2. Check your .env database configuration\n";
    echo "3. Run: php artisan migrate:fresh --seed\n";
    exit(1);
}
?>