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

    /**
     * Get the Office that owns the Expenses.
     */
    public function expense_class()
    {
        return $this->belongsTo('App\Models\Office', 'office_id');
    }

    public function transaction()
    {
        return Transaction::where('reference_id', $this->id)->where('type', 'expense')->first();
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
        $allotment = new Allotment();
        // $latest_expense = $expense->where('month', $month)->where('year', $year)->where('office_id', $office_id)->orderBy('id', 'desc')->first();
        $latest_transaction = Transaction::where('recepient', $office_id)->where('ending_balance', '<>', 0)->orderBy('id', 'desc')->first();
        $monthly_allocation = $allotment->monthly_allocation($office_id, $month, $year);
        if(!$latest_transaction){ 
            return $allotment->monthly_allocation($office_id, $month, $year);
        }
        return $latest_transaction->ending_balance;
    }

    public static function get_total_expenses($office_id, $month = null, $year = null)
    {
        $expense = new Expense();
        $year = !$year ? date('Y') : $year;
        $total_expense = $expense->where('year', $year)->where('office_id', $office_id);
        if($month){
            $total_expense = $total_expense->where('month', $month);
        }
        return $total_expense->sum('amount');
    }
}
