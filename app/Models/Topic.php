<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;
     protected $fillable = ['id','topic'];
     public function video(){
        return $this->hasMany('App\Models\Video', 'topic_id', 'id');
    }
     public function videos()
    {
        return $this->hasMany(Video::class);
    }
     public function questionBank(){
        return $this->hasMany('App\Models\QuestionBank', 'topic_id', 'id');
    }
    public function topics(){
        return $this->hasMany('App\Models\QuestionBank', 'topic_id', 'id');
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
    ];
}
