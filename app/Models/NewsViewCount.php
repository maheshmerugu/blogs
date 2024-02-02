<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NewsViewCount extends Model
{
    use HasFactory;
    protected $table = "news_view";
    protected $fillable = ['id','user_id','news_id'];
      /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        
        'news_id' => 'integer',
        'user_id' => 'integer',
    ]; 
}
