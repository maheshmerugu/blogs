<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandWrittingNotes extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','video_id','content','video_time'];
        
        public function video()
    {
        return $this->belongsTo(Video::class);
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
