<?php

namespace Mukja\Posty\Http\Controllers;

use Mukja\Posty\Models\Tag;
use Illuminate\Http\Request;
use Mukja\Posty\Models\Topic;
use Mukja\Posty\Models\Article;
use Illuminate\Routing\Controller;

class ArticlesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Article::all();
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
            'title' => 'required',
        ]);

        return Article::create([
            'title' => $request->title,
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        abort_if(! $id, 422, "Article ID is required!");

        $article = Article::with('topics', 'tags')->find($id);

        abort_if(! $article, 404, "Article with ID ({$id}) does not exists.");

        return $article;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'status' => 'required',
        ]);

        $article->update([
            'title' => $request->title,
            'summary' => $request->summary,
            'body' => $request->body,
            'published_at' => $request->status === 'published' ? ($article->published_at ?? now()) : null,
            'featured_image' => $request->featured_image,
            'featured_image_caption' => $request->featured_image_caption,
            'meta' => $request->meta,
        ]);

        $topics = explode(',', $request->topics);
        $topicsIds = [];
        foreach ($topics as $topicSlug) {
            $topic = Topic::where('slug', $topicSlug)->first();
            if (! $topic) {
                $topic = Topic::create([
                    'name' => ucwords($topicSlug),
                ]);
            }
            $topicsIds[] = $topic->id;
        }

        $tags = explode(',', $request->tags);
        $tagsIds = [];
        foreach ($tags as $tagSlug) {
            $tag = Tag::where('slug', $tagSlug)->first();
            if (! $tag) {
                $tag = Tag::create([
                    'name' => ucwords($tagSlug),
                ]);
            }
            $tagsIds[] = $tag->id;
        }

        $article->topics()->sync($topicsIds);
        $article->tags()->sync($tagsIds);

        return response()->json($article->refresh());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $article = Article::findOrFail($id);

        $article->delete();

        return response()->json([
            'message' => 'Article was deleted successfully.',
        ], 200);
    }
}
