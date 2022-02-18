<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;

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
        return response([
            'created' => true
        ] , ResponseAlias::HTTP_CREATED);
    }
}
