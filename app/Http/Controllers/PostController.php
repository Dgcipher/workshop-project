<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostRequest;
use App\Methods\Helper;
use App\Models\Post;

class PostController extends Controller
{
    use Helper;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return $this->responseJson(1,'data loaded',$posts);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostRequest $request)
    {
        //upload image
        $image = $request->image;
        $image_name = time().'.'.$image->getClientoriginalExtension();
        $request->image->move('images/posts',$image_name);

        //create post
        Post::create([
            'image'     =>  $image_name,
            'title'     => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id
        ]);

        return $this->responseJson(1,'post added successfully');
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
        $post = Post::find($id);
        return $this->responseJson(1,'data loaded',$post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostRequest $request, $id)
    {
        $post = Post::find($id);
        //check image
        $image = $request->image;
        if($image){
            $image_path = public_path().'/images/posts/'.$post->image;
            if(file_exists($image_path)){
                unlink($image_path);
            }

            $image_name = time().'.'.$image->getClientoriginalExtension();
            $request->image->move('images/posts',$image_name);

            $post->update([
                'image'     =>  $image_name,
                'title'     => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id
            ]);

        }else{
            $post->update([
                'title'     => $request->title,
                'description' => $request->description,
                'category_id' => $request->category_id
            ]);
        }

        return $this->responseJson(1, 'data updated successffuly');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $post = Post::find($id);
        $image_path = public_path().'/images/posts/'.$post->image;
        $image = $post->image;
        if($image){
            unlink($image_path);
        }
        $post->delete();

        return $this->responseJson(1,'post deleted successfully');
    }
}
