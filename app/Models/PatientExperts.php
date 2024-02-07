<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientExperts extends Model
{
    use HasFactory;


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
    
}
