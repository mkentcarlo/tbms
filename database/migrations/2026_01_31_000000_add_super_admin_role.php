<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Role;
use App\Models\RoleHierarchy;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if super_admin role already exists
        if (!Role::where('name', 'super_admin')->exists()) {
            $superAdminRole = Role::create(['name' => 'super_admin']);
            
            // Get the current highest hierarchy (lowest number = highest rank)
            $currentHighest = RoleHierarchy::min('hierarchy') ?? 1;
            
            // Create hierarchy entry with highest rank (0)
            RoleHierarchy::create([
                'role_id' => $superAdminRole->id,
                'hierarchy' => 0,
            ]);
            
            // Update existing hierarchies to shift down
            RoleHierarchy::where('hierarchy', '>=', 0)
                ->where('role_id', '!=', $superAdminRole->id)
                ->increment('hierarchy');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $role = Role::where('name', 'super_admin')->first();
        
        if ($role) {
            RoleHierarchy::where('role_id', $role->id)->delete();
            $role->delete();
        }
    }
};
