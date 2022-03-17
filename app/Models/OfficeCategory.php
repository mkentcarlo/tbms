<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfficeCategory extends Model
{
    use HasFactory;

    protected $table = 'office_categories';

    /**
     * Get the User that owns the Notes.
     */
    public function parent()
    {
        return $this->belongsTo('App\Models\OfficeCategory', 'parent_id');
    }
    
}
