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


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'video_id' => 'integer',
        'user_id' => 'integer',
    ];
}
