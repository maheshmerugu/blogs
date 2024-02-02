<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HandWrittingNotes extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','video_id','content','video_time'];
}
