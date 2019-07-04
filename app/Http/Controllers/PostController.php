<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $records = Post::paginate(20);
        return view('posts.index', compact('records'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $rules = array(
            'title'=>'required',
            'image'=>'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'body'=>'required',
        );

        $this->validate($request, $rules);


        $record = Post::Create($request->all());

        if ($request->hasFile('image')) {
            $path = public_path();
            $destinationPath = $path . '/uploads/posts/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renaming image
            $image->move($destinationPath, $name); // uploading file to given path
            $record->image = '/uploads/posts/' . $name;
        }
        $record->save();
        flash()->success("Saved successfully");
        return redirect(route('post.index'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $model = Post::findOrFail($id);
        return view('posts.edit', compact('model'));
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
        $record = Post::find($id);
        $record->update($request->except('image'));
        if ($request->hasFile('image')) {
            if(file_exists($record->image))
                unlink($record->image);
            $path = public_path();
            $destinationPath = $path . '/uploads/posts/'; // upload path
            $image = $request->file('image');
            $extension = $image->getClientOriginalExtension(); // getting image extension
            $name = time() . '' . rand(11111, 99999) . '.' . $extension; // renaming image
            $image->move($destinationPath, $name); // uploading file to given path
            $record->image = '/uploads/posts/' . $name;
        }
        $record->save();
        return redirect(route('post.index'));


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $record = Post::findOrFail($id);
        $record->delete();
        flash()->success("Deleted successfully");
        return back();

    }
}
