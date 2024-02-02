<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBank extends Model
{
    use HasFactory;
     public function module(){
        return $this->hasMany('App\Models\Module', 'subject_id', 'id');
    } 

     public function option(){
        return $this->hasMany('App\Models\QuestionBankOption', 'qb_id', 'id');
    } 

    public function bookmark(){
        return $this->hasOne('App\Models\BookmarkQuestionBank', 'qb_id', 'id');
    } 


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'status' => 'integer',
        'difficulty_level_id' => 'integer',
    ]; 
   
}
