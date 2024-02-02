<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ ];

     public function news(){
        return $this->hasMany('App\Models\NewsViewCount', 'news_id', 'id');
    }
     public function bookmark(){
        return $this->hasMany('App\Models\Bookmark', 'news_id', 'id')->select('user_id','news_id');
    }



     /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'type' => 'integer',
        'status' => 'integer',
    ]; 
}
