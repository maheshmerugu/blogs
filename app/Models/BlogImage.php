<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogImage extends Model
{
    protected $fillable = ['image_path'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
    public function getImageUrl()
    {
        return asset('storage/' . $this->image_path);
    }
}
