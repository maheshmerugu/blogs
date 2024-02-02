<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduleClass extends Model
{
    use HasFactory;



    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'year_id' => 'integer',
        'subject_id' => 'integer',
        'teacher_id' => 'integer',
        'topic_id' => 'integer',
    ];
}
