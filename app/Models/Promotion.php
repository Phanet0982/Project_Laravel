<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'discount_percent',
        'start_date',
        'end_date',
        'active'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'active' => 'boolean',
        'discount_percent' => 'decimal:2'
    ];

    protected $dates = [
        'start_date',
        'end_date'
    ];

    // Scope for active promotions
    public function scopeActive($query)
    {
        return $query->where('active', true)
                    ->whereDate('start_date', '<=', now()->toDateString())
                    ->whereDate('end_date', '>=', now()->toDateString());
    }

    // Scope for expired promotions
    public function scopeExpired($query)
    {
        return $query->whereDate('end_date', '<', now()->toDateString());
    }

    // Scope for upcoming promotions
    public function scopeUpcoming($query)
    {
        return $query->whereDate('start_date', '>', now()->toDateString());
    }

    // Check if promotion can still be used
    public function isActive()
    {
        return $this->active && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    // Get formatted discount for display
    public function getFormattedDiscountAttribute()
    {
        return $this->discount_percent . '%';
    }
    
    // Relationship with products
    public function products()
    {
        return $this->belongsToMany(Product::class, 'promotion_product')
                    ->withPivot('discount_amount')
                    ->withTimestamps();
    }
}