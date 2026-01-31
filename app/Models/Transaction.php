<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $table = 'transactions';

    /**
     * Get the Office that owns the Allotments.
     */
    public function reference()
    {
        
        if($this->type == 'expense'){
            return $this->belongsTo('App\Models\Expense', 'reference_id');
        }

        return $this->belongsTo('App\Models\Allotment', 'reference_id');
    }

    /**
     * Recalculate all ending balances for a specific office and year.
     * This should be called after any expense/allotment create, update, or delete.
     * 
     * @param int $officeId
     * @param int $year
     * @return void
     */
    public static function recalculateEndingBalances($officeId, $year)
    {
        // Get total allotments for this office up to current point
        $totalAllotments = Allotment::where('office_id', $officeId)
            ->where('year', $year)
            ->sum('amount');

        // Get all expense transactions for this office, ordered by transaction date and ID
        $expenseTransactions = self::where('recepient', $officeId)
            ->where('type', 'expense')
            ->whereHas('reference', function($query) use ($year) {
                $query->where('year', $year);
            })
            ->orderBy('transaction_date', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        // Running balance starts with total allotments
        $runningBalance = $totalAllotments;
        $runningExpenseTotal = 0;

        // Update each transaction's ending balance
        foreach ($expenseTransactions as $transaction) {
            $runningExpenseTotal += $transaction->amount;
            $runningBalance = $totalAllotments - $runningExpenseTotal;
            
            // Update without triggering events
            self::where('id', $transaction->id)->update([
                'ending_balance' => $runningBalance,
                'expense_total' => $runningExpenseTotal,
                'allotment_available' => $runningBalance
            ]);
        }
    }

    /**
     * Recalculate ending balances for all offices in a given year.
     * Useful for batch corrections.
     * 
     * @param int $year
     * @return void
     */
    public static function recalculateAllEndingBalances($year = null)
    {
        $year = $year ?? date('Y');
        
        // Get all unique office IDs that have transactions this year
        $officeIds = self::where('type', 'expense')
            ->whereHas('reference', function($query) use ($year) {
                $query->where('year', $year);
            })
            ->distinct()
            ->pluck('recepient');

        foreach ($officeIds as $officeId) {
            self::recalculateEndingBalances($officeId, $year);
        }
    }
}
