<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Video extends Model
{
    use HasFactory;
    
    protected $guarded = [];
    
    public function videoStatus(){
        return $this->hasMany('App\Models\VideoPlayStatus', 'video_id', 'id');
    }
    
    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'year_id' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'module_id' => 'integer',
        'topic_id' => 'integer',
        'status' => 'integer',
    ];
}
