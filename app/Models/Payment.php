<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'payment_reference',
        'sale_id',
        'amount',
        'status',
        'payment_method',
        'processed_at',
        'confirmed_at',
        'notes'
    ];
    
    protected $casts = [
        'amount' => 'decimal:2',
        'processed_at' => 'datetime',
        'confirmed_at' => 'datetime'
    ];
}
