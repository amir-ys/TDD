<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Tag;
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
        $posts = Post::latest()->paginate();
        return  view('admins.posts.index' , compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $tags = Tag::latest()->paginate();
        return  view('admins.posts.create' , compact('tags'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function store(Request $request)
    {
        //create post
       $post =  auth()->user()->posts()->create([
           'title' => $request->title ,
           'description' => $request->description ,
           'image' => $request->image ,
        ]);

        //attach tags to post
        $post->tags()->attach($request->tag_ids);

        //successfully message to user
        //redirect to post.index page
        return redirect()->route('admin.posts.index')->with(['message' =>  'post created successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        $tags = Tag::latest()->paginate();
        return  view('admins.posts.edit' , compact( 'post','tags'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     */
    public function update(Request $request, Post $post)
    {
         $post->update([
            'title' => $request->title ,
            'description' => $request->description ,
            'image' => $request->image ,
        ]);

        //sync tags to post
        $post->tags()->sync($request->tag_ids);

        //successfully message to user
        //redirect to post.index page
        return redirect()->route('admin.posts.index')->with(['message' =>  'post updated successfully']);
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
    }
}
