<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'description', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id')->withTimestamps();
    }

    public function blogImages()
    {
        return $this->hasMany(BlogImage::class, 'blog_id', 'id');
    }
    public function TagNames()
    {
        return $this->tags->pluck('name')->join(',');
    }
}
