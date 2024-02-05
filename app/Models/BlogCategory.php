<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BlogCategory extends Model
{
    use HasFactory;
    protected $table = "blog_category";
    public function category(){
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function getTitleByCategoryId($categoryId)
{
    $blogPost = Category::where('id', $categoryId)->first();

    if ($blogPost) {
        $title = $blogPost->name;
        return $title;
    } else {
        return "No blog post found for category ID: $categoryId";
    }
}


public function getDescByCategoryId($categoryId)
{
    $blogPost = Blog::where('id', $categoryId)->first();

    if ($blogPost) {
        $title = $blogPost->name;
        return $title;
    } else {
        return "No blog post found for category ID: $categoryId";
    }
}




}
