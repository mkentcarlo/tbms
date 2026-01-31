<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add login background setting if not exists
        if (!DB::table('app_settings')->where('key', 'login_background')->exists()) {
            DB::table('app_settings')->insert([
                'key' => 'login_background',
                'value' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('app_settings')->where('key', 'login_background')->delete();
    }
};
