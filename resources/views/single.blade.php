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
@endsection
