<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'business_name',
        'business_phone',
        'business_email',
        'tax_rate',
        'business_address',
        'currency',
        'timezone'
    ];
    
    protected $casts = [
        'tax_rate' => 'decimal:2'
    ];
}
