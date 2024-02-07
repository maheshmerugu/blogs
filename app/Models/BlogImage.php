<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class BlogImage extends Model
{
    protected $fillable = ['image_path'];

    public function blog()
    {
        return $this->belongsTo(Blog::class);
    }
    public function getImage()
    {
        return Storage::url($this->image_path);
    }
}
