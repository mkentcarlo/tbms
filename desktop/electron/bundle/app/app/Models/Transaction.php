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
}
