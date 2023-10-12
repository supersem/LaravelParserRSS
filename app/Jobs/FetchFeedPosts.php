<?php

namespace App\Jobs;

use App\Models\Feed;
use App\Models\Post;
use Feeds;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class FetchFeedPosts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $feed = Feed::first();
        $crawledFeed = Feeds::make(feedUrl: [$feed->feed_url]);

        $items = $crawledFeed->get_items();

        foreach ($items as $item) {
            $existingPost = Post::where('guid', $item->get_id())->first();
            if(!$existingPost) {
                $post = new Post();
                $post->title = $item->get_title();
                $post->content = $item->get_description();
                $post->published_at = date('Y-m-d H:i:s', strtotime($item->get_date()));
                $post->author = $item->get_author()?->name;
                $post->url = $item->get_permalink();
                $post->category = $item->get_category()->term;
                $post->guid = $item->get_id();
                $post->feed_id = $feed->id;
                $post->save();
            }
        }
    }
}
