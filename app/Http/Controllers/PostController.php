<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Resources\PostResource;
use App\Jobs\FetchFeedPosts;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Actions\PostAction;

class PostController extends Controller
{
    public function __construct(PostAction $postAction)
    {
        $this->postAction = $postAction;
    }

    public function index(Request $request)
    {
        dispatch(new FetchFeedPosts());
        $posts = $this->postAction->handle($request);

        return PostResource::collection($posts);
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);

        return new PostResource($post);
    }

    public function store(PostStoreRequest $request)
    {
        $post = $this->postAction->store($request);

        return new PostResource($post);
    }

    public function update(PostUpdateRequest $request, $id)
    {
        $post = Post::findOrFail($id);

        $post->update($request->all());

        return new PostResource($post);
    }

    public function destroy($id)
    {
        $post = Post::findOrFail($id);
        $post->delete();

        return response()->json(['message' => 'Post deleted']);
    }
}
