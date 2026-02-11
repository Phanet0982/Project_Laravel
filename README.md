<<<<<<< HEAD
# Project_Laravel
=======
# 🏪 POS Management System

A comprehensive Point of Sale (POS) Management System built with Laravel 8, featuring modern dark theme UI and complete business management capabilities.

## 📋 Features

- **Dashboard & Analytics** - Real-time business insights
- **Product Management** - Complete inventory control with categories and suppliers
- **Customer Management** - CRM with loyalty programs (VIP/Regular members)
- **Employee Management** - Staff tracking with role-based access control
- **Point of Sale** - Fast checkout system with receipt generation
- **Comprehensive Reports** - Sales, inventory, customer, employee, financial, and transaction reports
- **Modern UI** - Professional dark theme with responsive design
- **Security** - Role-based authentication and data protection

## 🚀 Quick Setup

### Method 1: Using Laravel Migrations (Recommended)

1. **Clone/Download the project**
   ```bash
   git clone [your-repo-url]
   cd pos-management-system
   ```

2. **Install Dependencies**
   ```bash
   composer install
   ```

3. **Environment Setup**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

4. **Database Configuration**
   Edit `.env` file with your database credentials:
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sale_management
   DB_USERNAME=your_username
   DB_PASSWORD=your_password
   ```

5. **Create Database**
   ```sql
   CREATE DATABASE sale_management;
   ```

6. **Run Migrations and Seeders**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

7. **Create Storage Link**
   ```bash
   php artisan storage:link
   ```

8. **Start the Server**
   ```bash
   php artisan serve
   ```

9. **Access the System**
   - URL: `http://127.0.0.1:8000`
   - Username: `admin`
   - Password: `admin123`

### Method 2: Using SQL File

1. **Import Database**
   ```bash
   mysql -u your_username -p < database.sql
   ```

2. **Follow steps 1-4 and 7-9 from Method 1**

## 🔐 Default Login Credentials

- **Username**: `admin`
- **Password**: `admin123`

**⚠️ Important**: Change the default password after first login for security!

## 📁 Project Structure

```
├── app/
│   ├── Http/Controllers/     # Application controllers
│   └── Models/              # Eloquent models
├── database/
│   ├── migrations/          # Database migrations
│   └── seeders/            # Database seeders
├── resources/
│   └── views/              # Blade templates
├── routes/
│   └── web.php             # Web routes
├── public/                 # Public assets
└── storage/               # File storage
```

## 🛠️ System Requirements

- **PHP**: 7.4 or higher
- **Composer**: Latest version
- **MySQL**: 5.7 or higher / MariaDB 10.2+
- **Web Server**: Apache/Nginx
- **Extensions**: OpenSSL, PDO, Mbstring, Tokenizer, XML, Ctype, JSON, BCMath

## 🎯 User Roles & Permissions

### Admin
- Full system access
- User management
- System configuration
- All reports and analytics

### Manager
- Sales operations
- Inventory management
- Customer management
- Reports (except employee management)

### Cashier
- POS operations
- Basic customer management
- Limited inventory view

## 📊 Available Reports

1. **Sales Report** - Revenue analysis and transaction details
2. **Inventory Report** - Stock levels and product valuation
3. **Customer Report** - Customer analytics and behavior patterns
4. **Employee Report** - Staff performance and attendance
5. **Financial Report** - Profit/loss analysis and margins
6. **Transaction Report** - Detailed transaction logs

## 🔧 Configuration

### Adding Sample Data

Run the following commands to add sample data for testing:

```bash
php artisan db:seed --class=EmployeeSeeder
php artisan db:seed --class=SalesSeeder
```

### File Storage

The system uses Laravel's file storage for product images:
- Images are stored in `storage/app/public/products/`
- Make sure to run `php artisan storage:link` to create the symbolic link

## 🚨 Troubleshooting

### Login Issues

**Problem**: "The provided credentials do not match our records"

**Quick Fix Options**:

**Option 1: Use the Fix Script**
```bash
php fix-login.php
```

**Option 2: Use Laravel Command**
```bash
php artisan admin:reset-password
```

**Option 3: Manual Database Reset**
```bash
php artisan migrate:fresh --seed
```

**Option 4: Check Database Connection**
1. Verify `.env` database settings
2. Run `php artisan config:clear`
3. Test database connection: `php artisan tinker --execute="echo \App\Models\User::count();"`

### Database Issues

**Problem**: Migration errors or table not found

**Solutions**:
1. **Fresh Install**: 
   ```bash
   php artisan migrate:fresh --seed
   ```
2. **Check Database**: Ensure database exists and credentials are correct
3. **Permissions**: Check database user permissions

### File Upload Issues

**Problem**: Product images not uploading

**Solutions**:
1. **Storage Link**: Run `php artisan storage:link`
2. **Permissions**: Check storage folder permissions (755 or 775)
3. **Directory**: Ensure `storage/app/public/products/` exists

### Performance Issues

**Solutions**:
1. **Optimize**: Run `php artisan optimize`
2. **Config Cache**: Run `php artisan config:cache`
3. **Route Cache**: Run `php artisan route:cache`

## 🔒 Security Best Practices

1. **Change Default Password**: Update admin password after installation
2. **Environment File**: Keep `.env` file secure and never commit it
3. **Database**: Use strong database passwords
4. **Updates**: Keep Laravel and dependencies updated
5. **Backups**: Regular database backups

## 📱 Browser Compatibility

- Chrome 70+
- Firefox 65+
- Safari 12+
- Edge 79+

## 🤝 Support

For issues and questions:

1. **Check Documentation**: Review this README and `SYSTEM_FEATURES.md`
2. **Common Issues**: Check the troubleshooting section above
3. **Database Setup**: Ensure proper database configuration
4. **File Permissions**: Check storage and cache folder permissions

## 📄 License

This project is open-source software. Feel free to modify and distribute according to your needs.

## 🎉 Getting Started

1. Follow the setup instructions above
2. Login with default credentials
3. Explore the dashboard and features
4. Add your products, customers, and employees
5. Start processing sales and generating reports!

**Enjoy your new POS Management System!** 🚀

---

**Note**: This system is designed for small to medium businesses. For enterprise-level requirements, additional customization may be needed.
>>>>>>> 34f5307 (Initial commit)
