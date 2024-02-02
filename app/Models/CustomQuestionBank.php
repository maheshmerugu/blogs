<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomQuestionBank extends Model
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
        'year_id' => 'integer',
        'subject_id' => 'integer',
        'module_id' => 'integer',
        'topic_id' => 'integer',
        'qb_id' => 'integer',
    ];
}
