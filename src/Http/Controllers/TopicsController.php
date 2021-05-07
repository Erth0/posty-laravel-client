<?php

namespace Mukja\Posty\Http\Controllers;

use Illuminate\Http\Request;
use Mukja\Posty\Models\Topic;
use Illuminate\Routing\Controller;

class TopicsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Topic::all();
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

        return Topic::create([
            'name' => $request->name,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(string $slug)
    {
        $topic = Topic::where('slug', $slug)->first();

        abort_if(! $topic, 404, "Topic with slug ({$slug}) was not found.");

        return $topic;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $topic = Topic::where('slug', $slug)->first();

        abort_if(! $topic, 404, "Topic with slug ({$slug}) was not found.");

        $request->validate([
            'name' => 'required',
        ]);

        $topic->update([
            'name' => $request->name,
        ]);

        return $topic->refresh();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $topic = Topic::where('slug', $slug)->first();

        abort_if(! $topic, 404, "Topic with slug ({$slug}) was not found.");

        $topic->delete();

        return response()->noContent();
    }
}
