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


     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'year_id' => 'integer',
        'subject_id' => 'integer',
    ];
}
