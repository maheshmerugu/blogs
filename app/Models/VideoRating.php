<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VideoRating extends Model
{
    use HasFactory;

    protected $fillable = ['video_id','user_id','rating','content'];

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
        'rating' => 'double',
    ];
}
