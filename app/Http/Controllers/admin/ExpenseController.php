<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Office;
use App\Models\Transaction;


class ExpenseController extends Controller
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
        $query = Expense::select('*');
        $office_id = $request->input('office_id');
        $m = $request->input('m');
        $y = $request->input('y');
        $s = $request->input('s');
        if($office_id!=''){
            $query = $query->where('office_id', $request->input('office_id'));
        }
        if($m!=''){
            $query = $query->where('month', $m);
        }
        if($y!=''){
            $query = $query->where('year', $y);
        }
        if($s){
            $query->where(function($q) use($s){
                $query = $q->where('expense_class', 'like', '%'.$s.'%');
                $query = $q->orWhere('account_code', 'like', '%'.$s.'%');
            });
        }
        $expenses = $query->orderBy('created_at', 'asc')->get();

        return view('dashboard.expenses.index',['expenses' => $expenses, 'offices' => $offices]);
    }

    /**
     * Show the expense create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $offices = Office::all();
        return view('dashboard.expenses.create',['offices' => $offices, 'expense_id' => Expense::generate_expense_id()]);
    }

     /**
     * Show the store expense.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year'       => 'required',
            'month'       => 'required',
            'office_id'       => 'required',
            'amount'       => 'required',
            'expense_class'       => 'required',
            'account_code'       => 'required',
        ]);

        $expense = new Expense();
        $expense->year = $request->input('year');
        $expense->month = $request->input('month');
        $expense->office_id = $request->input('office_id');
        $expense->amount = $request->input('amount');
        $expense->remarks = $request->input('remarks');
        $expense->account_code = $request->input('account_code');
        $expense->expense_class = $request->input('expense_class');
        $expense->save();

        $transaction = new Transaction();
        $transaction->reference_id = $expense->id;
        $transaction->type = 'expense';
        $transaction->recepient = $expense->office_id;
        $transaction->amount = $expense->amount;
        $transaction->ending_balance = $request->input('ending_balance');
        $transaction->remarks = $expense->remarks;
        $transaction->transaction_date = $request->input('transaction_date');
        $transaction->save();

        $request->session()->flash('message', 'Successfully added expense.');
        return redirect()->route('expense.index');
    }

    /**
     * Show the expense edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $expense = Expense::find($id);
        $offices = Office::all();
        return view('dashboard.expenses.edit',['offices' => $offices, 'expense' => $expense]);
    }

    /**
     * Show the update expense.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'year'       => 'required',
            'month'       => 'required',
            'office_id'       => 'required',
            'amount'       => 'required',
        ]);

        $expense = Expense::find($id);
        $expense->year = $request->input('year');
        $expense->month = $request->input('month');
        $expense->office_id = $request->input('office_id');
        $expense->amount = $request->input('amount');
        $expense->remarks = $request->input('remarks');
        $expense->save();

        $transaction = Transaction::find($expense->transaction->id);
        $transaction->recepient = $expense->office_id;
        $transaction->amount = $expense->amount;
        $transaction->ending_balance = $expense->monthly_allocation($expense->month, $expense->year);
        $transaction->remarks = $expense->remarks;
        $transaction->save();

        $request->session()->flash('message', 'Successfully updated expense.');
        return redirect()->route('expense.index');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id, Request $request)
    {
        $expense = Expense::find($id);

        if($expense->transaction){
            $expense->transaction->delete();
        }
        if($expense){
            $expense->delete();
        }
        $request->session()->flash('message', 'Successfully deleted expense.');
        return redirect()->route('expense.index');
    }

    public function get_office_allotment_balance(Request $request)
    {
        return Expense::get_allotment_balance($request->input('office_id'), $request->input('month'), $request->input('year'));
    }
    
    
}
