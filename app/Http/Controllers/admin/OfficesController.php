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
        return view('dashboard.offices.index',['offices' => OfficeCategory::where('parent_id', 0)->get()]);
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
        echo "<option value='0'>Select expense class</option>";
        foreach($expense_classes as $expense_class){
            echo "<option value='{$expense_class->id}'>{$expense_class->name}</option>";
        }
    }


}
