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

     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'qb_id' => 'integer',
        'user_id' => 'integer',
        'is_selected' => 'integer',
    ];
    
}
