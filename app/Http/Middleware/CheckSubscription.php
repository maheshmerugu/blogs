<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Helper;

class CheckSubscription
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
         //Get subscription status of the user
        $subscriptionCheck = Helper::checkUserSubscriptionForVideo();
        $check = json_decode($subscriptionCheck);
           
        if($check->code ==0 || $check->code == 2){
            echo $subscriptionCheck; die;
        }
        return $next($request);
    
       
    }
}
