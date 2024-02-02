<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    use HasFactory;



    public function topics(){
        return $this->hasMany('App\Models\Topic', 'module_id', 'id');
    }
    public function video(){
        return $this->hasMany('App\Models\Video', 'module_id', 'id');
    }
    public function QuestionBank(){
        return $this->hasMany('App\Models\QuestionBank', 'module_id', 'id');
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
    ];  
}
