# 🔧 POS System - Troubleshooting Guide

## 🚨 Common Issues & Solutions

### 1. Login Problems

#### Issue: "The provided credentials do not match our records"

This is the most common issue when setting up the system. Here are **4 ways** to fix it:

#### ✅ Solution 1: Quick Fix Script (Easiest)
```bash
# For Windows users
fix-login.bat

# For Linux/Mac users
php fix-login.php
```

#### ✅ Solution 2: Laravel Command
```bash
php artisan admin:reset-password
```

#### ✅ Solution 3: Fresh Installation
```bash
php artisan migrate:fresh --seed
```

#### ✅ Solution 4: Manual Database Check
```bash
# Check if database is connected
php artisan tinker --execute="echo \App\Models\User::count();"

# If no users found, create admin user
php artisan tinker --execute="
\App\Models\User::create([
    'username' => 'admin',
    'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
    'role' => 'admin'
]);
echo 'Admin user created!';
"
```

---

### 2. Database Connection Issues

#### Issue: "Database connection failed" or "SQLSTATE errors"

#### ✅ Check Database Configuration
1. **Verify .env file**:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sale_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

2. **Test database connection**:
   ```bash
   php artisan tinker --execute="echo 'Database connected: ' . \DB::connection()->getPdo() ? 'YES' : 'NO';"
   ```

3. **Create database if it doesn't exist**:
   ```sql
   CREATE DATABASE sale_management;
   ```

#### ✅ Clear Configuration Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan config:cache
```

---

### 3. File Permission Issues

#### Issue: "Permission denied" or file upload errors

#### ✅ Fix Storage Permissions (Linux/Mac)
```bash
chmod -R 775 storage
chmod -R 775 bootstrap/cache
```

#### ✅ Create Storage Link
```bash
php artisan storage:link
```

#### ✅ Create Required Directories
```bash
mkdir -p storage/app/public/products
mkdir -p storage/logs
```

---

### 4. Composer/Dependencies Issues

#### Issue: "Class not found" or "Vendor directory missing"

#### ✅ Install Dependencies
```bash
composer install
```

#### ✅ Update Dependencies
```bash
composer update
```

#### ✅ Regenerate Autoload
```bash
composer dump-autoload
```

---

### 5. Laravel Key Issues

#### Issue: "No application encryption key has been specified"

#### ✅ Generate Application Key
```bash
php artisan key:generate
```

---

### 6. Migration Issues

#### Issue: "Table already exists" or migration errors

#### ✅ Fresh Migration (Warning: This deletes all data)
```bash
php artisan migrate:fresh --seed
```

#### ✅ Reset Specific Migration
```bash
php artisan migrate:rollback
php artisan migrate
```

#### ✅ Check Migration Status
```bash
php artisan migrate:status
```

#### Issue: "Invalid default value for 'check_out'" (MySQL TIMESTAMP error)

This happens with strict MySQL configurations. The SQL file has been updated to fix this.

#### ✅ Solution 1: Use Updated SQL File
- The `database.sql` file now includes `NULL DEFAULT NULL` for TIMESTAMP columns
- Re-import the updated SQL file

#### ✅ Solution 2: Disable MySQL Strict Mode
```sql
SET sql_mode = '';
-- Then import your SQL file
```

#### ✅ Solution 3: Use Laravel Migrations Instead
```bash
php artisan migrate:fresh --seed
```

---

### 7. Server Issues

#### Issue: "Server not starting" or "Port already in use"

#### ✅ Start Development Server
```bash
php artisan serve
```

#### ✅ Use Different Port
```bash
php artisan serve --port=8080
```

#### ✅ Check if Port is Available
```bash
# Windows
netstat -an | findstr :8000

# Linux/Mac
lsof -i :8000
```

---

### 8. Browser/Access Issues

#### Issue: "Page not loading" or "CSS not working"

#### ✅ Clear Browser Cache
- Press `Ctrl+F5` (Windows) or `Cmd+Shift+R` (Mac)

#### ✅ Check URL
- Make sure you're accessing: `http://127.0.0.1:8000`
- Not `http://localhost:8000` (sometimes causes issues)

#### ✅ Disable Browser Extensions
- Try accessing in incognito/private mode

---

## 🔍 Diagnostic Commands

### Check System Status
```bash
# Check PHP version
php --version

# Check Laravel version
php artisan --version

# Check database connection
php artisan tinker --execute="echo \DB::connection()->getPdo() ? 'Connected' : 'Failed';"

# Check users count
php artisan tinker --execute="echo \App\Models\User::count();"

# Check if admin exists
php artisan tinker --execute="echo \App\Models\User::where('username', 'admin')->exists() ? 'EXISTS' : 'NOT FOUND';"
```

### System Information
```bash
# Check installed extensions
php -m

# Check Laravel configuration
php artisan config:show

# Check routes
php artisan route:list
```

---

## 📞 Step-by-Step Recovery

If nothing works, follow these steps in order:

### Step 1: Fresh Start
```bash
# 1. Delete vendor folder and composer.lock
rm -rf vendor composer.lock

# 2. Reinstall dependencies
composer install

# 3. Copy environment file
cp .env.example .env

# 4. Generate key
php artisan key:generate
```

### Step 2: Database Setup
```bash
# 1. Configure .env with your database settings
# 2. Create database
# 3. Run migrations
php artisan migrate:fresh --seed
```

### Step 3: Fix Permissions
```bash
# 1. Set storage permissions
chmod -R 775 storage bootstrap/cache

# 2. Create storage link
php artisan storage:link
```

### Step 4: Test Login
```bash
# 1. Start server
php artisan serve

# 2. Access http://127.0.0.1:8000
# 3. Login with admin/admin123
```

---

## 🆘 Emergency Access

If you still can't login, create a temporary admin user:

### Create Emergency Admin
```bash
php artisan tinker --execute="
\App\Models\User::create([
    'username' => 'emergency',
    'password' => \Illuminate\Support\Facades\Hash::make('emergency123'),
    'role' => 'admin'
]);
echo 'Emergency admin created: emergency/emergency123';
"
```

---

## 📋 System Requirements Check

### Minimum Requirements
- **PHP**: 7.4+
- **MySQL**: 5.7+ or MariaDB 10.2+
- **Composer**: Latest version
- **Extensions**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

### Check Requirements
```bash
# Check PHP version
php --version

# Check required extensions
php -m | grep -E "(openssl|pdo|mbstring|tokenizer|xml|ctype|json|bcmath)"

# Check MySQL version
mysql --version
```

---

## 🎯 Quick Solutions Summary

| Problem | Quick Fix |
|---------|-----------|
| Can't login | `php fix-login.php` |
| Database error | Check `.env` file |
| Permission denied | `chmod -R 775 storage` |
| Class not found | `composer install` |
| No app key | `php artisan key:generate` |
| Migration error | `php artisan migrate:fresh --seed` |
| Server won't start | `php artisan serve --port=8080` |

---

## 📞 Still Need Help?

1. **Check this guide** - Most issues are covered here
2. **Run diagnostics** - Use the diagnostic commands above
3. **Fresh installation** - Follow the step-by-step recovery
4. **Check system requirements** - Ensure your system meets the requirements

**Remember**: The most common issue is login problems, which can be fixed with `php fix-login.php` in 99% of cases! 🎉