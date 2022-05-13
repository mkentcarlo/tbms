<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;

    protected $table = 'offices';

    /**
     * Get the User that owns the Notes.
     */
    public function category()
    {
        return $this->belongsTo('App\Models\OfficeCategory', 'office_category_id');
    }

    public function getAppropriation($year)
    {
        return Allotment::where('year', $year)->where('month', '0')->where('office_id', $this->id)->sum('amount');
    }

    public function getAllotmentTotal($start ='', $end='')
    {
        return Transaction::where('type', 'allotment')
                ->where('recepient', $this->id)
                ->where('transaction_date', '>=', $start)
                ->where('transaction_date', '<=', $end)
                ->sum('amount');
    }

    public function getExpensesTotal($start ='', $end='')
    {
        return Transaction::where('type', 'expense')
                ->where('recepient', $this->id)
                ->where('transaction_date', '>=', $start)
                ->where('transaction_date', '<=', $end)
                ->sum('amount');
    }

    /**
     * Get the User that owns the Notes.
     */
    public function getExpenseClassesByDescription()
    {
        return $this->where('description', $this->description)->get();
    }

}


