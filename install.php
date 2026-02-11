<?php
/**
 * POS Management System - Installation Helper
 * 
 * This script helps users set up the system correctly
 * Run this after downloading the project files
 */

echo "🏪 POS Management System - Installation Helper\n";
echo "=============================================\n\n";

// Check PHP version
if (version_compare(PHP_VERSION, '7.4.0') < 0) {
    echo "❌ Error: PHP 7.4 or higher is required. Current version: " . PHP_VERSION . "\n";
    exit(1);
}

echo "✅ PHP Version: " . PHP_VERSION . " (Compatible)\n";

// Check if composer is installed
if (!file_exists('vendor/autoload.php')) {
    echo "❌ Error: Composer dependencies not installed.\n";
    echo "   Please run: composer install\n";
    exit(1);
}

echo "✅ Composer dependencies found\n";

// Check if .env file exists
if (!file_exists('.env')) {
    if (file_exists('.env.example')) {
        copy('.env.example', '.env');
        echo "✅ Created .env file from .env.example\n";
    } else {
        echo "❌ Error: .env.example file not found\n";
        exit(1);
    }
} else {
    echo "✅ .env file exists\n";
}

// Load Laravel
require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

echo "\n📋 Setup Instructions:\n";
echo "===================\n";
echo "1. Configure your database in .env file:\n";
echo "   - DB_DATABASE=sale_management\n";
echo "   - DB_USERNAME=your_username\n";
echo "   - DB_PASSWORD=your_password\n\n";

echo "2. Create the database:\n";
echo "   CREATE DATABASE sale_management;\n\n";

echo "3. Run the following commands:\n";
echo "   php artisan key:generate\n";
echo "   php artisan migrate\n";
echo "   php artisan db:seed\n";
echo "   php artisan storage:link\n";
echo "   php artisan serve\n\n";

echo "4. Access the system:\n";
echo "   URL: http://127.0.0.1:8000\n";
echo "   Username: admin\n";
echo "   Password: admin123\n\n";

echo "🔐 IMPORTANT SECURITY NOTE:\n";
echo "Change the default admin password after first login!\n\n";

echo "📚 For detailed instructions, see README.md\n";
echo "📋 For feature documentation, see SYSTEM_FEATURES.md\n\n";

echo "🚀 Ready to install! Follow the steps above.\n";
?>