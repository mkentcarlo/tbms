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
        return Transaction::with('reference')->where('type', 'allotment')
                ->whereHas('reference', function($q){
                    $q->where('month', '<>', 0);
                })
                ->where('recepient', $this->id)
                ->where('transaction_date', '>=', $start)
                ->where('transaction_date', '<=', $end)
                ->sum('amount');
    }

    public function getAllotmentTotalByYear($year)
    {
        return Transaction::with('reference')->where('type', 'allotment')
                ->whereHas('reference', function($q){
                    $q->where('month', '<>', 0);
                })
                ->where('recepient', $this->id)
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
    public function getExpenseClassesByDescription($category_id)
    {
        return $this->where('description', $this->description)->where('office_category_id', $category_id)->get();
    }

    public function getDescription(){
        return $this->category->parent->name. " > ".$this->category->name." > ".$this->name;
    }

}


