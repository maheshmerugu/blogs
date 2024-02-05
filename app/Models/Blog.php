<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    protected $fillable = ['title', 'description'];

    public function categories()
    {
<<<<<<< HEAD
        // return $this->belongsToMany(Category::class, 'blog_category', 'blog_id', 'category_id')->withTimestamps();
=======
        return $this->belongsToMany(Category::class, 'blog_category', 'blog_id', 'category_id')->withTimestamps();
>>>>>>> origin/venkatesh
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'blog_tag', 'blog_id', 'tag_id')->withTimestamps();
    }

    public function blogImages()
    {
<<<<<<< HEAD
        return $this->hasMany(BlogImage::class);
=======
        return $this->hasMany(BlogImage::class, 'blog_id', 'id');
>>>>>>> origin/venkatesh
    }
}
