<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AppSetting extends Model
{
    protected $fillable = ['key', 'value'];

    /**
     * Get a setting value by key
     */
    public static function get($key, $default = null)
    {
        return Cache::remember("app_setting_{$key}", 3600, function () use ($key, $default) {
            $setting = self::where('key', $key)->first();
            return $setting ? $setting->value : $default;
        });
    }

    /**
     * Set a setting value
     */
    public static function set($key, $value)
    {
        $setting = self::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );
        
        Cache::forget("app_setting_{$key}");
        
        return $setting;
    }

    /**
     * Get app name
     */
    public static function appName()
    {
        return self::get('app_name', 'TBMS');
    }

    /**
     * Get app logo
     */
    public static function appLogo()
    {
        return self::get('app_logo');
    }

    /**
     * Get app description
     */
    public static function appDescription()
    {
        return self::get('app_description', 'Treasury Budget Management System');
    }

    /**
     * Get login background
     */
    public static function loginBackground()
    {
        return self::get('login_background');
    }

    // ========== Print Document Settings ==========

    /**
     * Get country name
     */
    public static function countryName()
    {
        return self::get('country_name', 'Republic of the Philippines');
    }

    /**
     * Get province name
     */
    public static function provinceName()
    {
        return self::get('province_name', 'PROVINCE OF AGUSAN DEL SUR');
    }

    /**
     * Get municipality name
     */
    public static function municipalityName()
    {
        return self::get('municipality_name', 'Municipality of Talacogon');
    }

    /**
     * Get certifying officer name
     */
    public static function certifyingOfficerName()
    {
        return self::get('certifying_officer_name', 'MARILOU P. AZUCENA,MM');
    }

    /**
     * Get certifying officer title
     */
    public static function certifyingOfficerTitle()
    {
        return self::get('certifying_officer_title', 'Budget Officer III');
    }

    /**
     * Get budget officer name
     */
    public static function budgetOfficerName()
    {
        return self::get('budget_officer_name', 'GWENDOLYN A. CLAROS, REB,MEM');
    }

    /**
     * Get budget officer title
     */
    public static function budgetOfficerTitle()
    {
        return self::get('budget_officer_title', 'Municipal Budget Officer');
    }
}
