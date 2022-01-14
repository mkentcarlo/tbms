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
}
