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
}
