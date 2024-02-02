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
    




    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'user_id' => 'integer',
        'qb_id' => 'integer',
        'status' => 'integer',
        'is_unattempt_answer' => 'integer',  
    ];
}
