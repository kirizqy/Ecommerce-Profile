<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'image', 'excerpt', 'body', 'published_at', 'views'];
    protected $casts = ['published_at' => 'datetime'];

    public function scopePublished($q)
    {
        return $q->where('published_at', '<=', now());
    }
}
