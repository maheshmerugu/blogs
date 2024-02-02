<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqQuestion extends Model
{
    use HasFactory;
     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'mcq_question',
        'mcq_answer',
        'mcq_explanation'
    ];
    
     public function mcqAnalytic(){

        return $this->hasMany('App\Models\McqAnalytic', 'mcq_id', 'id');
    }
     public function mcqOption(){

        return $this->hasMany('App\Models\McqOption', 'mcq_id', 'id')->select('id','mcq_id','option');
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
