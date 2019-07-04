<?php

namespace App\Http\Controllers;

use App\Governorate;
use Illuminate\Http\Request;

class GovernorateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Governorate::paginate(20);
        return view('governorates.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('governorates.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, ['name'=>'required'], ['name.required'=>'Name is required']);
//        $record = new Governorate();
//        $record->name = $request->input('name');
//        $record->save();
        $record = Governorate::create($request->all());
        flash()->success("Saved successfully");
        return redirect(route('governorate.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Governorate::findOrFail($id);
        return view('governorates.edit', compact('model'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $record = Governorate::findOrFAil($id);
        $record->update($request->all());
        flash()->success("Edited successfully");
        return redirect(route('governorate.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $record = Governorate::findOrFail($id);
        if($record->cities()->count()){
            flash()->error("Can not be deleted, There are " . $record->cities()->count(). " city associated with this governorate");
            return back();

        }else{

            $record->delete();
            flash()->success("Deleted successfully");
            return back();
        }

    }
}
