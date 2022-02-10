<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $table = 'expenses';

    /**
     * Get the Office that owns the Expenses.
     */
    public function office()
    {
        return $this->belongsTo('App\Models\Office', 'office_id');
    }

    public function transaction()
    {
        return $this->hasOne('App\Models\Transaction', 'reference_id');
    }

    public static function generate_expense_id(){
        $m = date('m');
        $y = date('Y');
        $d = date('d');
        $code = $y.$m.$d.'0000';
        $count = self::where('year', $y)->get()->count() + 1;
        $code = $code + $count;
        return $code;
    }

    public static function get_allotment_balance($office_id, $month, $year = null)
    {
        $expense = new Expense();
        $year = !$year ? date('Y') : $year;
        $latest_expense = $expense->with('transaction')->where('month', $month)->where('year', $year)->where('office_id', $office_id)->orderBy('id', 'desc')->first();
        if(!$latest_expense){
            $allotment = new Allotment();
            return $allotment->monthly_allocation($office_id, $month, $year);
        }
        return $latest_expense->transaction->ending_balance;
    }
}
