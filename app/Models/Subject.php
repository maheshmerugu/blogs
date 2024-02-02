<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;


    public function video(){
        return $this->hasMany('App\Models\Video', 'subject_id', 'id');
    }
    public function module(){
        return $this->hasMany('App\Models\Module', 'subject_id', 'id');
    } 
    public function topics(){
        return $this->hasMany('App\Models\Module', 'subject_id', 'id');
    }
     public function topicsInQB(){
        return $this->hasMany('App\Models\QuestionBank', 'subject_id', 'id');
    }



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'year_id' => 'integer',
        'video_type' => 'integer',
    ];
}
