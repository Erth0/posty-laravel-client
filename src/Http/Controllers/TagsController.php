<?php

namespace Mukja\Posty\Http\Controllers;

use Illuminate\Http\Request;
use Mukja\Posty\Models\Tag;
use Illuminate\Routing\Controller;

class TagsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Tag::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        return Tag::create([
            'name' => $request->name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        abort_if(! $tag, 404, "Tag with slug ({$slug}) was not found.");

        return $tag;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, string $slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        abort_if(! $tag, 404, "Tag with slug ({$slug}) was not found.");

        $request->validate([
            'name' => 'required',
        ]);

        $tag->update([
            'name' => $request->name,
        ]);

        return $tag->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function destroy(string $slug)
    {
        $tag = Tag::where('slug', $slug)->first();

        abort_if(! $tag, 404, "Tag with slug ({$slug}) was not found.");

        $tag->delete();

        return response()->noContent();
    }
}
