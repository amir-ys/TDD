<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class SingleController extends Controller
{
    public function index(Post $post)
    {
        $comments = $post->comments()->latest()->paginate(15);
        return view('single' , compact('post' , 'comments'));
    }

    public function commentStore(Request $request ,Post $post)
    {
        $post->comments()->create([
            'user_id' => auth()->id() ,
            'text' => $request->text ,
         ]);
        return redirect()->route('home');
    }
}
