<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QBAnalytic extends Model
{
    use HasFactory;


    protected $guarded = [];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'qb_id' => 'integer',
        'user_id' => 'integer',
        'status' => 'integer',
        'is_unattempt_answer' => 'integer',
    ];
}
