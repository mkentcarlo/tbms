<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create super_admin role if it doesn't exist
        $superAdminRole = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $userRole = Role::firstOrCreate(['name' => 'user', 'guard_name' => 'web']);

        // Check if user already exists (including soft-deleted)
        $superAdmin = User::withTrashed()->where('email', 'superadmin@admin.com')->first();

        if ($superAdmin) {
            // Restore if soft-deleted
            if ($superAdmin->trashed()) {
                $superAdmin->restore();
                $this->command->info('Super Admin user restored from trash!');
            }
            // Update existing user
            $superAdmin->name = 'Super Admin';
            $superAdmin->password = Hash::make('superadmin2026');
            $superAdmin->save();
            $this->command->info('Super Admin user updated!');
        } else {
            // Create new user using DB to avoid any model issues
            DB::table('users')->insert([
                'name' => 'Super Admin',
                'email' => 'superadmin@admin.com',
                'password' => Hash::make('superadmin2026'),
                'menuroles' => 'user',
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $superAdmin = User::where('email', 'superadmin@admin.com')->first();
            $this->command->info('Super Admin user created!');
        }

        // Assign all roles to super admin
        $superAdmin->syncRoles(['super_admin', 'admin', 'user']);

        $this->command->info('Email: superadmin@admin.com');
        $this->command->info('Password: superadmin2026');
    }
}
