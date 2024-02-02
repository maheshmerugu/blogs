<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class McqAnalytic extends Model
{
    use HasFactory;

   
     protected $fillable = [
        'id',
        'user_id',
        'mcq_id',
        'explanation',
        'is_answer',
        'user_answer',
        'explanation',
        
    ];

     public function mcqQuestion(){

        return $this->hasMany('App\Models\McqQuestion', 'id', 'mcq_id');
    }


     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'mcq_id' => 'integer',
        'user_id' => 'integer', 
        'status' => 'integer', 
    ];
    
}
