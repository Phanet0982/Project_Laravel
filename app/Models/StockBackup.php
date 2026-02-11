<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockBackup extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'name',
        'description',
        'backup_data',
        'product_count',
        'created_by',
        'backup_date'
    ];
    
    protected $casts = [
        'backup_data' => 'array',
        'backup_date' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
