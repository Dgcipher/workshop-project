<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function index()
{
    $blogs = Blog::all();
    return response()->json($blogs);
}

public function show($id)
{
    $blog = Blog::findOrFail($id);
    return response()->json($blog);
}

public function store(Request $request)
{


    $validator=Validator::make($request->all(),[
        'title' => 'required|max:255',
        'body' => 'required',
    ]);
    if($validator->fails())
    {
        return response()->json(['errors'=>$validator->errors()],400,);
    }
    $blog = Blog::create($request->all());
    return response()->json($blog, 201);
}

public function update(Request $request, $id)
{
    $validatedData = $request->validate([
        'title' => 'required|max:255',
        'body' => 'required',
    ]);
    $blog = Blog::findOrFail($id);
    $blog->update($validatedData);
    return response()->json($blog, 200);
}

public function destroy($id)
{
    $blog = Blog::findOrFail($id);
    $blog->delete();
    return response()->json(null, 204);
}
}
