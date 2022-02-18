@extends('layouts.master')
@section('content')
    {{ $post->title }}

    <ul>
        @foreach($comments as $comment)
            <li>
                {{ $comment->text }}
            </li>
        @endforeach
    </ul>

    @auth
        <form method="post" action="{{ route('single.comment' , $post->id )}}">
            <textarea name="text" id="" cols="30" rows="10"></textarea>
        </form>
    @endauth
@endsection
