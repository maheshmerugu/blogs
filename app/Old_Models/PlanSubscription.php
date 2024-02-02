<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanSubscription extends Model
{
    use HasFactory;

    protected $fillable = ['plan_name','months','access_to_video','access_to_notes','access_to_question_bank','amount','discount','watch_hours','payble_amount'
      
    ];
}
