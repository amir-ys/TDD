<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostRequest;
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
    public function store(PostRequest $request)
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
     * @param  Post $post

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
     * @param  Post $post

     */
    public function update(PostRequest $request, Post $post)
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
     * @param  Post $post
     */
    public function destroy(Post $post)
    {
        //detach tags
        $post->tags()->detach();

        //delete post comments
        $post->comments()->delete();

        //delete post
        $post->delete();

        //redirect user with success message
        return redirect()->route('admin.posts.index')
            ->with(['message' => 'post deleted successfully']);
    }
}
