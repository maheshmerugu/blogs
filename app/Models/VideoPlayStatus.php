<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPlayStatus extends Model
{
    use HasFactory;

    protected $fillable = ['video_id','subcription_id','user_id','watch_time','status','is_progress_video'];
    //video relation
     public function video(){
        return $this->hasMany('App\Models\Video', 'id', 'video_id');
    }
     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'subcription_id' => 'integer',
        'video_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
        'is_progress_video'=>'integer',
    ];
    
}
