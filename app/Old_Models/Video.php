<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    public function videoStatus(){
        return $this->hasMany('App\Models\VideoPlayStatus', 'video_id', 'id');
    }
    
}
