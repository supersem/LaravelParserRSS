<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'published_at', 'author', 'url', 'category', 'guid', 'feed_id'];

    public function feed() {
        return $this->belongsTo(Feed::class);
    }
}
