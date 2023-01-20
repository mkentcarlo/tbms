<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\Transaction;
use App\Models\OfficeCategory;


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
        $categories = OfficeCategory::where('parent_id', 0)->get();
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
        $expenses = $query->orderBy('created_at', 'asc')->paginate(20);

        return view('dashboard.expenses.index',['expenses' => $expenses, 'offices' => $offices, 'categories' => $categories]);
    }

    /**
     * Show the expense create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $offices = Office::all();
        $categories = OfficeCategory::where('parent_id', 0)->get();
        return view('dashboard.expenses.create',['offices' => $offices, 'categories' => $categories, 'expense_id' => Expense::generate_expense_id()]);
    }

    public function print($id)
    {
        $expense = Expense::find($id);
        $allotment = new Allotment();
        $monthly_allotment = $allotment->monthly_allocation($expense->office_id, $expense->month, $expense->year);
        return view('dashboard.expenses.print', ['expense' => $expense, 'monthly_allotment' => $monthly_allotment]);
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
            'account_code'       => 'required',
        ]);

        $expense = new Expense();
        $expense->year = $request->input('year');
        $expense->month = $request->input('month');
        $expense->office_id = $request->input('office_id');
        $expense->amount = $request->input('amount');
        $expense->remarks = $request->input('remarks');
        $expense->account_code = $request->input('account_code');
        $expense->expense_class = $request->input('office_id');
        $expense->save();

        $transaction = new Transaction();
        $transaction->reference_id = $expense->id;
        $transaction->type = 'expense';
        $transaction->recepient = $expense->office_id; 
        $transaction->amount = $expense->amount;
        $transaction->ending_balance = $request->input('ending_balance');
        $transaction->expense_total = $request->input('total_expenses');
        $transaction->allotment_total_quarter = $request->input('total_allotment_quarter');
        $transaction->allotment_available = $request->input('allotment_available');
        $transaction->remarks = $expense->remarks;
        $transaction->transaction_date = $request->input('transaction_date');
        $transaction->save();
        $print_link = route('expense.print', ['id' => $expense->id]);

        $request->session()->flash('message', 'Successfully added expense. <a class="print" href="'.$print_link.'">Click here</a> to print.');
        return redirect()->route($request->input('redirect_to'));
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

        if($expense->transaction()){
            Transaction::where('reference_id', $id)->where('type', 'expense')->delete();
        }
        if($expense){
            $expense->delete();
        }
        $request->session()->flash('message', 'Successfully deleted expense.');
        return redirect()->route('expense.index');
    }

    public function get_office_allotment_balance(Request $request)
    {
        $allotment = new Allotment();
        $total_allotment_quarter = $allotment->quarterly_allocation($request->input('office_id'), $request->input('month'), $request->input('year'));
        $total_expenses = Expense::get_total_expenses($request->input('office_id'));
        $total_allotment_balance = Expense::get_allotment_balance($request->input('office_id'), $request->input('month'), $request->input('year'));
        return response()->json([
            'total_allotment_quarter' => $total_allotment_quarter,
            'total_expenses' => $total_expenses,
            'total_allotment_balance' => $total_allotment_balance
        ]);
    }
    
    
}
