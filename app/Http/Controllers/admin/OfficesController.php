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
        return view('dashboard.offices.index',['offices' => OfficeCategory::all()]);
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
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function create()
    {
        $categories = OfficeCategory::all();
        return view('dashboard.offices.create',['categories' => $categories]);
    }

     /**
     * Show the office create form.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function edit($id)
    {
        $office = Office::find($id);
        $categories = OfficeCategory::all();
        return view('dashboard.offices.edit',['office' => $office, 'categories' => $categories]);
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
        $office->name = $request->input('name');
        $office->office_category_id = $request->input('office_category_id');
        $office->description = $request->input('description');
        $office->save();

        $request->session()->flash('message', 'Successfully created office.');
        return redirect()->route('office.index');
    }

     /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function delete($id)
    {
        $office = Office::find($id);
        if($office){
            $office->delete();
        }
        return redirect()->route('office.index');
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
        $office->office_category_id = $request->input('office_category_id');
        $office->description = $request->input('description');
        $office->save();

        $request->session()->flash('message', 'Successfully updated office.');
        return redirect()->route('office.index');
    }
    
    /**
     * Show the offices list.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function categories()
    {
        $categories = OfficeCategory::all();
        return view('dashboard.offices.categories', [ 'categories' => $categories ]);
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


}
