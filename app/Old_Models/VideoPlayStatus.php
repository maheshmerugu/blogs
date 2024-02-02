<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoPlayStatus extends Model
{
    use HasFactory;

    protected $fillable = ['video_id','subcription_id','user_id','watch_time','status'];

    
    
}
