<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoDownload extends Model
{
    use HasFactory;
    protected $guarded=[];

     public function video(){
        return $this->hasMany('App\Models\Video', 'id', 'video_id');
    }
}
