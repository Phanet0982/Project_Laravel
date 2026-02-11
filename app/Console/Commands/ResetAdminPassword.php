<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class ResetAdminPassword extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:reset-password {password=admin123}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset admin password to default or specified password';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $password = $this->argument('password');
        
        // Find or create admin user
        $admin = User::where('username', 'admin')->first();
        
        if (!$admin) {
            $admin = User::create([
                'username' => 'admin',
                'password' => Hash::make($password),
                'role' => 'admin'
            ]);
            $this->info('✅ Admin user created successfully!');
        } else {
            $admin->update([
                'password' => Hash::make($password)
            ]);
            $this->info('✅ Admin password updated successfully!');
        }
        
        $this->info('');
        $this->info('🔐 Login Credentials:');
        $this->info('   Username: admin');
        $this->info('   Password: ' . $password);
        $this->info('');
        $this->info('🌐 Access your system at: http://127.0.0.1:8000');
        
        return 0;
    }
}
