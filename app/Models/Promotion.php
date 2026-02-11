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
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    // Scope for expired promotions
    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    // Scope for upcoming promotions
    public function scopeUpcoming($query)
    {
        return $query->where('start_date', '>', now());
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
}