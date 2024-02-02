<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    public function video(){
        return $this->hasMany('App\Models\Video', 'teacher_id', 'id');
    }

}
