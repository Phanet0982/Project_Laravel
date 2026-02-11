<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'username',
        'email',
        'phone',
        'role',
        'password',
        'is_active',
        'last_login'
    ];

    protected $hidden = [
        'password'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login' => 'datetime'
    ];

    // Automatically hash password when setting
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = Hash::make($value);
    }

    // Get role badge color
    public function getRoleBadgeColorAttribute()
    {
        return match($this->role) {
            'admin' => 'danger',
            'manager' => 'warning',
            'cashier' => 'primary',
            default => 'secondary'
        };
    }

    // Get role icon
    public function getRoleIconAttribute()
    {
        return match($this->role) {
            'admin' => 'fa-crown',
            'manager' => 'fa-user-tie',
            'cashier' => 'fa-cash-register',
            default => 'fa-user'
        };
    }

    // Relationship with attendance
    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'user_id');
    }

    // Get today's attendance
    public function todayAttendance()
    {
        return $this->attendances()->whereDate('date', today())->first();
    }
}
