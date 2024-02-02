<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContinueVideo extends Model
{
    use HasFactory;
    
     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'user_id' => 'integer',
        'category_id' => 'integer',
        'video_id' => 'integer',
    ];
}
