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
        return Allotment::where('year', $year)->where('month', '0')->sum('amount')->where('office_id', $this->id);
    }
}
