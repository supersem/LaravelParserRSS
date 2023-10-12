<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Feed extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'feed_url', 'description'];

    public function posts() {
        return $this->hasMany(Post::class);
    }
}
