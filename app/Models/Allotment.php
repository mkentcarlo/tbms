<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Allotment extends Model
{
    use HasFactory;

    protected $table = 'allotments';

    /**
     * Get the Office that owns the Allotments.
     */
    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'office_id');
    }

    /**
     * Get the Office that owns the Allotments.
     */
    public function expense_class()
    {
        return $this->belongsTo('App\Models\Office', 'office_id');
    }

    public function monthly_allocation($office_id, $month, $year = null)
    {
        $year = $year == null ? date('Y') : $year;
        return $this->where('month', '<=', $month)
        ->where('month', '<>', 0)
        ->where('year', $year)
        ->where('office_id', $office_id)
        ->sum('amount');    
    }

    public function quarterly_allocation($office_id, $month, $year = null)
    {
        $year = $year == null ? date('Y') : $year;
        $quarter = ceil($month / 3);
        $e = $quarter * 4;
        $s = $e - 3;
        return $this->where('month', '>=', $s)->where('month', '<=', $e)
        ->where('year', $year)
        ->where('office_id', $office_id)
        ->sum('amount');
    }

    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'reference_id');
    }
}
