<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    use HasFactory;

    protected $guarded=[];


    /**
 * The attributes that should be mutated to dates.
 *
 * @var array
 */
protected $dates = ['expiry_date'];
}
