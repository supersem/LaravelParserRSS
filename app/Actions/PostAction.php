<?php

namespace App\Actions;

use App\Models\Post;
use App\Models\Feed;
use Illuminate\Http\Request;
use function _PHPStan_4dd92cd93\React\Promise\Stream\first;

class PostAction
{
    public function handle(Request $request)
    {
        $query = Post::query();

        if ($request->has('category')) {
            $query->where('category', $request->input('category'));
        }

        if ($request->has('author')) {
            $query->where('author', $request->input('author'));
        }

        if ($request->has('q')) {
            $searchTerm = $request->input('q');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('title', 'like', "%$searchTerm%")
                    ->orWhere('content', 'like', "%$searchTerm%");
            });
        }

        if ($request->has('sort')) {
            $sort = $request->input('sort');
            $query->orderBy($sort, $request->input('order', 'desc'));
        }

        return $query->paginate(10);
    }

    public function store(Request $request)
    {
        $post = new Post();
        $post->title = $request->input('title');
        $post->content = $request->input('content');
        $post->published_at = now();
        $post->author = $request->input('author');
        $post->url = $request->input('url');
        $post->category = $request->input('category');
        $post->feed_id = Feed::first()->id;
        $post->save();

        return $post;
    }
}
