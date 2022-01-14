<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\Transaction;
use App\Models\Expense;


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
        $offices = Office::all();
        $transactions = Transaction::all();
        return view('dashboard.homepage',['offices' => $offices, 'transactions' => $transactions]);
    }
    
}
