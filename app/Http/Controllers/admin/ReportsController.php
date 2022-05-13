<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Transaction;


class ReportsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $office_groups = OfficeCategory::where('parent_id', 0)->get();
        return view('dashboard.reports.index', ['office_groups' => $office_groups, 'filters' => $request->all()]);
    }
    
    
}
