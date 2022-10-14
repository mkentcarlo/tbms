<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\Transaction;
use App\Models\Expense;
use App\Models\OfficeCategory;


class DashboardController extends Controller
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
        $year = $request->session()->get('selected_year', date('Y'));
        $month = $request->session()->get('selected_month', date('m'));
        $selected_office = $request->session()->get('selected_office', Office::first()->id);
        $offices = Office::all();
        $selected_office = Office::find($selected_office);
        $selected_office = !$selected_office ? Office::first() : $selected_office;
        $selected_office->getDescription();
        $transactions = Transaction::latest()->take(20)->get();
        $categories = OfficeCategory::where('parent_id', 0)->get();
        return view('dashboard.homepage',['offices' => $offices, 'transactions' => $transactions, 'categories'  =>  $categories, 'selected_office'  =>  $selected_office, 'month'   =>  $month, 'year'  =>  $year, 'expense_id' => Expense::generate_expense_id()]);
    }
    
    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function selectOffice(Request $request)
    {
        $request->session()->put('selected_office', $request->input('office_id'));
        $request->session()->flash('message', 'Selected office successfully changed.');
        return redirect()->route('dashboard.index');
    }
    
    public function selectDate(Request $request)
    {
        $request->session()->put('selected_year', $request->input('year'));
        $request->session()->put('selected_month', $request->input('month'));
        $request->session()->flash('message', 'Selected date successfully changed.');
        return redirect()->route('dashboard.index');
    }
    
}
