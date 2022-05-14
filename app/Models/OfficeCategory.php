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

    /**
     * Get the User that owns the Notes.
     */
    public function children()
    {
        return $this->hasMany('App\Models\OfficeCategory', 'parent_id');
    }
    

    /**
     * Get the User that owns the Notes.
     */
    public function expense_classes()
    {
        return $this->hasMany('App\Models\Office', 'office_category_id');
    }

    /**
     * Get the User that owns the Notes.
     */
    public function getUniqueDescriptions()
    {
        return Office::select('description')->groupBy('description')->orderByRaw("FIELD(description, 'Personal Services') DESC")->get();
    }
    
}
