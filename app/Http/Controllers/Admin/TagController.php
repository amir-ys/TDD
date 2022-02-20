<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\tag;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Application|Factory|View
     */
    public function index()
    {
        $tags = Tag::latest()->paginate();
        return view('admins.tags.index' , compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admins.tags.create' );
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        Tag::create(['name' => $request->name]);
        return redirect()->route('admin.tags.index')
            ->with(['message' => 'tag created successfully']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\tag  $tag
     * @return Application|Factory|View
     */
    public function edit(tag $tag): View|Factory|Application
    {
        return view('admins.tags.edit' , compact('tag'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\tag  $tag
     * @return RedirectResponse
     */
    public function update(Request $request, tag $tag): RedirectResponse
    {
        $tag->update([
           'name' => $request->name
        ]);
        return redirect()->route('admin.tags.index')
            ->with(['message' => 'tag updated successfully']);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\tag  $tag
     * @return RedirectResponse
     */
    public function destroy(tag $tag): RedirectResponse
    {
        $tag->delete();
        return redirect()->route('admin.tags.index')
            ->with(['message' => 'tag deleted successfully']);    }
}
