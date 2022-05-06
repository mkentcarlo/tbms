<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Allotment;
use App\Models\Office;
use App\Models\OfficeCategory;
use App\Models\Transaction;
use App\Models\Expense;


class AllotmentController extends Controller
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
        $query = Allotment::select('*');
        $office_id = $request->input('office_id');
        if($office_id!=''){
            $query = $query->where('office_id', $office_id);
        }
        $allotments = $query->orderBy('id', 'asc')->get();
        return view('dashboard.allotments.index',['allotments' => $allotments, 'offices' => $offices]);
    }

    /**
     * Show the allotment create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $offices = Office::all();
        $categories = OfficeCategory::where('parent_id', 0)->get();
        return view('dashboard.allotments.create',['offices' => $offices, 'categories' => $categories]);
    }

     /**
     * Show the store allotment.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'year'       => 'required',
            'office_id'       => 'required',
            'amount'       => 'required',
        ]);

        $allotment = new Allotment();
        $allotment->year = $request->input('year');
        $allotment->month = $request->input('month');
        $allotment->office_id = $request->input('office_id');
        $allotment->amount = $request->input('amount');
        $allotment->remarks = $request->input('remarks');
        $allotment->save();

        $transaction = new Transaction();
        $transaction->reference_id = $allotment->id;
        $transaction->type = 'allotment';
        $transaction->recepient = $allotment->office_id;
        $transaction->amount = $allotment->amount;
        $transaction->ending_balance = ( $allotment->monthly_allocation($allotment->office_id, $allotment->month, $allotment->year) - Expense::get_total_expenses($allotment->office_id, null, $year)) + $allotment->amount;
        $transaction->remarks = $allotment->remarks;
        $transaction->transaction_date = $allotment->created_at;
        $transaction->allotment_total_quarter = 0;
        $transaction->expense_total = 0;
        $transaction->allotment_available = 0;
        $transaction->save();

        $request->session()->flash('message', 'Successfully created allotment.');
        return redirect()->route('allotment.index');
    }

    /**
     * Show the allotment edit form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $allotment = Allotment::find($id);
        $categories = OfficeCategory::where('parent_id', 0)->get();
        $object_expenditures = OfficeCategory::where('parent_id', $allotment->expense_class->category->parent_id)->get();
        $expense_classes = Office::where('office_category_id', $allotment->expense_class->category->id)->get();
        return view('dashboard.allotments.edit',['allotment' => $allotment, 'categories' => $categories, 'expense_classes' => $expense_classes, 'object_expenditures' => $object_expenditures]);
    }

    /**
     * Show the update allotment.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'year'       => 'required',
            'office_id'       => 'required',
            'amount'       => 'required',
        ]);

        $allotment = Allotment::find($id);
        $allotment->year = $request->input('year');
        $allotment->month = !$request->input('month') ? 0: $request->input('month');
        $allotment->office_id = $request->input('office_id');
        $allotment->amount = $request->input('amount');
        $allotment->remarks = $request->input('remarks');
        $allotment->save();

        $transaction = Transaction::find($allotment->transaction->id);
        $transaction->recepient = $allotment->office_id;
        $transaction->amount = $allotment->amount;
        $transaction->ending_balance = $allotment->monthly_allocation($allotment->office_id, $allotment->month, $allotment->year);
        $transaction->remarks = $allotment->remarks;
        $transaction->save();

        $request->session()->flash('message', 'Successfully updated allotment.');
        return redirect()->route('allotment.index');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id, Request $request)
    {
        $allotment = Allotment::find($id);

        if($allotment->transaction){
            $allotment->transaction->delete();
        }
        if($allotment){
            $allotment->delete();
        }
        $request->session()->flash('message', 'Successfully deleted allotment.');
        return redirect()->route('allotment.index');
    }
    
    
}
