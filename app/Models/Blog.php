<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'description'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'blog_category', 'blog_id', 'category_id')->withTimestamps();
    }
    public function blog_category()
    {
        return $this->belongsTo(BlogCategory::class, 'id', 'blog_id');
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
