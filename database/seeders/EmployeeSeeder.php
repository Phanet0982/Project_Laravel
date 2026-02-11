<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Employee::create([
            'name' => 'John Manager',
            'username' => 'manager',
            'email' => 'manager@example.com',
            'phone' => '555-0123',
            'role' => 'manager',
            'password' => 'password123',
            'is_active' => true,
            'last_login' => now()->subHours(2)
        ]);

        \App\Models\Employee::create([
            'name' => 'Sarah Cashier',
            'username' => 'cashier1',
            'email' => 'sarah@example.com',
            'phone' => '555-0124',
            'role' => 'cashier',
            'password' => 'password123',
            'is_active' => true,
            'last_login' => now()->subDays(1)
        ]);

        \App\Models\Employee::create([
            'name' => 'Mike Cashier',
            'username' => 'cashier2',
            'email' => 'mike@example.com',
            'phone' => '555-0125',
            'role' => 'cashier',
            'password' => 'password123',
            'is_active' => false,
            'last_login' => null
        ]);
    }
}
