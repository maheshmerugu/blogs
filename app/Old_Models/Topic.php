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

     public function questionBank(){
        return $this->hasMany('App\Models\QuestionBank', 'topic_id', 'id');
    }
    public function topics(){
        return $this->hasMany('App\Models\QuestionBank', 'topic_id', 'id');
    }

}
