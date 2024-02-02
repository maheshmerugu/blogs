<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomQBAnalytic extends Model
{
    use HasFactory;
    protected $table = "custom_q_b_analytics";
    protected $guarded=[];

     public function questionBank(){
        return $this->hasMany('App\Models\QuestionBank', 'id', 'qb_id');
    }
    
}
