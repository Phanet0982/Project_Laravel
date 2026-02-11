<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemPreference extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'low_stock_alerts',
        'email_notifications',
        'auto_backup',
        'receipt_printing',
        'low_stock_threshold',
        'receipt_footer_text'
    ];
    
    protected $casts = [
        'low_stock_alerts' => 'boolean',
        'email_notifications' => 'boolean',
        'auto_backup' => 'boolean',
        'receipt_printing' => 'boolean',
        'low_stock_threshold' => 'integer'
    ];
}
