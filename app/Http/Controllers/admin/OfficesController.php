<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OfficeCategory;
use App\Models\Office;


class OfficesController extends Controller
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
    public function index()
    {
        return view('dashboard.offices.index',['offices' => OfficeCategory::where('parent_id','<>', 0)->get()]);
    }

    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function expense_classes()
    {
        return view('dashboard.offices.expense_classes',['expense_classes' => Office::all()]);
    }

    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function object_expenditures()
    {
        $object_expenditures = OfficeCategory::where('parent_id', '<>', 0)->get();
        return view('dashboard.offices.object_expenditures',['object_expenditures' => $object_expenditures]);
    }

    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function offices()
    {
        $offices = OfficeCategory::where('parent_id', '<>', 0)->get();
        return view('dashboard.offices.index',['offices' => $offices]);
    }

    /**
     * Show the office groups list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function office_groups()
    {
        $office_groups = OfficeCategory::where('parent_id', '=', 0)->get();
        return view('dashboard.offices.office_groups.index',['office_groups' => $office_groups]);
    }
    

    /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $categories = OfficeCategory::where('parent_id', 0)->get();
        return view('dashboard.offices.create',['categories' => $categories]);
    }

    /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create_ooe()
    {
        $categories = OfficeCategory::all();
        return view('dashboard.offices.create_ooe',['categories' => $categories]);
    }

     /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create_main_office()
    {
        $office_groups = OfficeCategory::all();
        return view('dashboard.offices.main_offices.create',['office_groups' => $office_groups]);
    }

    /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create_office_group()
    {
        return view('dashboard.offices.office_groups.create');
    }

     /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $expense_class = Office::find($id);
        $categories = OfficeCategory::where('parent_id', 0)->get();
        $object_expenditures = OfficeCategory::where('parent_id', $expense_class->category->parent_id)->get();
        return view('dashboard.offices.edit',['expense_class' => $expense_class, 'categories' => $categories, 'object_expenditures' => $object_expenditures]);
    }

    /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit_ooe($id)
    {
        $officecategory = OfficeCategory::find($id);
        $categories = OfficeCategory::all();
        return view('dashboard.offices.edit_ooe',['ooe' => $officecategory, 'categories' => $categories]);
    }

    /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit_main_office($id)
    {
        $office = OfficeCategory::find($id);
        $office_groups = OfficeCategory::all();
        return view('dashboard.offices.main_offices.edit',['office' => $office, 'office_groups' => $office_groups]);
    }

     /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit_office_group($id)
    {
        $office_group = OfficeCategory::find($id);
        return view('dashboard.offices.office_groups.edit',['office_group' => $office_group]);
    }


    /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $office = new Office();
        $office->name = addslashes($request->input('name'));
        $office->office_category_id = $request->input('object_of_expenditures');
        $office->description = $request->input('description');
        $office->save();

        $request->session()->flash('message', 'Successfully created office.');
        return redirect()->route('office.expense_classes');
    }

    /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_ooe(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $category = new OfficeCategory();
        $category->name = $request->input('name');
        $category->parent_id = $request->input('office_category_id');
        $category->save();
        return redirect()->route('office.object_expenditures');
    }

    /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_main_office(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $category = new OfficeCategory();
        $category->name = $request->input('name');
        $category->parent_id = $request->input('office_category_id');
        $category->save();
        return redirect()->route('office.index');
    }


    /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function store_office_group(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
        ]);

        $category = new OfficeCategory();
        $category->name = $request->input('name');
        $category->parent_id = 0;
        $category->save();
        $request->session()->flash('message', 'Successfully added office group.');
        return redirect()->route('office.office_groups');
    }

     /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_ooe($id, Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $OfficeCategory = OfficeCategory::find($id);
        $OfficeCategory->name = $request->input('name');
        $OfficeCategory->parent_id = $request->input('office_category_id');
        $OfficeCategory->save();

        $request->session()->flash('message', 'Successfully updated object expenditure.');
        return redirect()->route('office.object_expenditures');
    }

     /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_main_office($id, Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $OfficeCategory = OfficeCategory::find($id);
        $OfficeCategory->name = $request->input('name');
        $OfficeCategory->parent_id = $request->input('office_category_id');
        $OfficeCategory->save();

        $request->session()->flash('message', 'Successfully updated office.');
        return redirect()->route('office.index');
    }

     /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_office_group($id, Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256'
        ]);

        $OfficeCategory = OfficeCategory::find($id);
        $OfficeCategory->name = $request->input('name');
        $OfficeCategory->save();

        $request->session()->flash('message', 'Successfully updated office group.');
        return redirect()->route('office.office_groups');
    }
    

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request, $id)
    {
        $office = Office::find($id);
        if($office){
            $office->delete();
        }
        $request->session()->flash('message', 'Expense class deleted successfully.');
        return redirect()->route('office.expense_classes');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_ooe($id)
    {
        $officeCategory = OfficeCategory::find($id);
        if($officeCategory){
            $officeCategory->delete();
        }
        return redirect()->route('office.object_expenditures');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_main_office(Request $request, $id)
    {
        $officeCategory = OfficeCategory::find($id);
        if($officeCategory){
            $officeCategory->delete();
        }
        $request->session()->flash('message', 'Office deleted successfully.');
        return redirect()->route('office.index');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete_office_group(Request $request, $id)
    {
        $officeCategory = OfficeCategory::find($id);
        if($officeCategory){
            $officeCategory->delete();
        }
        $request->session()->flash('message', 'Office group deleted successfully.');
        return redirect()->route('office.office_groups');
    }
    
     /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update($id, Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256',
            'office_category_id'       => 'required',
        ]);

        $office = Office::find($id);
        $office->name = $request->input('name');
        $office->office_category_id = $request->input('object_of_expenditures');
        $office->description = $request->input('description');
        $office->save();

        $request->session()->flash('message', 'Successfully updated expense class.');
        return redirect()->route('office.expense_classes');
    }
    
    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categories()
    {
        $categories = OfficeCategory::all();
        return view('dashboard.offices.index', [ 'offices' => $categories ]);
    }

    /**
     * Show the store office category.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categories_store(Request $request)
    {
        $validatedData = $request->validate([
            'name'       => 'required|min:1|max:256'
        ]);

        if($request->input('id')){
            $category = OfficeCategory::find($request->input('id'));
        }else{
            $category = new OfficeCategory();
        }


        $category->name = $request->input('name');
        $category->save();

        $request->session()->flash('message', 'Successfully created office category.');
        return redirect()->route('office.categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function categories_delete($id)
    {
        $category = OfficeCategory::find($id);
        if($category){
            $category->delete();
        }
        return redirect()->route('office.categories');
    }

    public function load_ooes( $parent_id )
    {
        $ooes = OfficeCategory::where('parent_id', $parent_id)->get();
        echo "<option value='0'>-------</option>";
        foreach($ooes as $ooe){
            echo "<option value='{$ooe->id}'>{$ooe->name}</option>";
        }
    }

    public function load_tags(Request $request){
        $data = Office::selectRaw('description as name')->where('description', 'LIKE', "%{$request->input('query')}%")->distinct()->get();
        return response()->json($data);
    }

    public function load_expense_classes( $category_id )
    {
        $expense_classes = Office::where('office_category_id', $category_id)->get();
        $year = $_GET['year'];
        echo "<option value='0'>Select expense class</option>";
        foreach($expense_classes as $expense_class){
            $appropriation = $expense_class->getAppropriation( $year );
            $allotment_total = $expense_class->getAllotmentTotalByYear($year);
            $appropriation_balance = $appropriation - $allotment_total;
            echo "<option data-balance='$appropriation_balance' value='{$expense_class->id}'>{$expense_class->name}</option>";
        }
    }


}
