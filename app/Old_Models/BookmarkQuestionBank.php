<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookmarkQuestionBank extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function QuestionBank(){
        return $this->hasMany('App\Models\QuestionBank', 'id', 'qb_id');
    }
    
}
