<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $settings = [
            ['key' => 'country_name', 'value' => 'Republic of the Philippines'],
            ['key' => 'province_name', 'value' => 'PROVINCE OF AGUSAN DEL SUR'],
            ['key' => 'municipality_name', 'value' => 'Municipality of Talacogon'],
            ['key' => 'certifying_officer_name', 'value' => 'MARILOU P. AZUCENA,MM'],
            ['key' => 'certifying_officer_title', 'value' => 'Budget Officer III'],
            ['key' => 'budget_officer_name', 'value' => 'GWENDOLYN A. CLAROS, REB,MEM'],
            ['key' => 'budget_officer_title', 'value' => 'Municipal Budget Officer'],
        ];

        foreach ($settings as $setting) {
            // Only insert if setting doesn't exist
            $exists = DB::table('app_settings')->where('key', $setting['key'])->exists();
            if (!$exists) {
                DB::table('app_settings')->insert([
                    'key' => $setting['key'],
                    'value' => $setting['value'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('app_settings')->whereIn('key', [
            'country_name',
            'province_name',
            'municipality_name',
            'certifying_officer_name',
            'certifying_officer_title',
            'budget_officer_name',
            'budget_officer_title',
        ])->delete();
    }
};
