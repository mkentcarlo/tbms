<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ensure super_admin role exists
        $role = Role::firstOrCreate(['name' => 'super_admin']);
        
        // Check if superadmin user already exists
        $user = User::where('email', 'superadmin@admin.com')->first();
        
        if (!$user) {
            $user = User::create([
                'name' => 'superadmin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('superadmin2026'),
                'email_verified_at' => now(),
                'menuroles' => 'user,admin,super_admin',
            ]);
        }
        
        // Assign super_admin role
        if (!$user->hasRole('super_admin')) {
            $user->assignRole('super_admin');
        }
        if (!$user->hasRole('admin')) {
            $user->assignRole('admin');
        }
        if (!$user->hasRole('user')) {
            $user->assignRole('user');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $user = User::where('email', 'superadmin@admin.com')->first();
        
        if ($user) {
            $user->removeRole('super_admin');
        }
    }
};
