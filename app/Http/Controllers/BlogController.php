<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Traits\apiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    use apiResponse;
    public function index()
{
    $blogs = Blog::all();
    return $this->ApiResponse($blogs,'found',200);
}

public function show($id)
{
    $blog = Blog::find($id);
    if(is_null($blog))
    {
         return response()->json(['msg'=>"not found"],200);
    }
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
    return $this->ApiResponse($blog,'created successfuly',200);
}

public function update(Request $request, $id)
{  $validator=Validator::make($request->all(),[
    'title' => 'required|max:255',
    'body' => 'required',
]);
if($validator->fails())
{
    return response()->json(['errors'=>$validator->errors()],400,);
}

$blog = Blog::find($id);
if(is_null($blog))
{
     return response()->json(['msg'=>"not found"],200);
}

$blog->update($request->all());


return $this->ApiResponse($blog,'updated successfuly',200);

}


public function destroy($id)
{

    $blog = Blog::find($id);
    if(is_null($blog))
    {
         return response()->json(['msg'=>"not found"],200);
    }

   else{ $blog->delete();


    return $this->ApiResponse($blog,'deleted successfuly',200);

}
}
}
