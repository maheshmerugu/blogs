<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionBankResult extends Model
{
    use HasFactory;

    protected $guarded = [];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'user_id' => 'integer',
        'subject_id' => 'integer',
        'topic_id' => 'integer',
        'total_question' => 'integer',
        'attempted_question' => 'integer',
        'incorrect_answer' => 'integer',
        'correct_answer' => 'integer',
        'accuracy' => 'integer',
    ]; 
}
