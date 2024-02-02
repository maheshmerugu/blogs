<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Helpers\Helper;
use App\Models\Subscription;
use App\Models\PlanSubscription as Plan;
use Carbon\Carbon;


class CheckAccessQB
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
        $accessToQb = Subscription::where(['user_id' => auth('api_user')->user()->id, 'subcription_for' => 1])->orderBy('id', 'desc')->first();
        if(Carbon::now()->greaterThan($accessToQb->expiry_date))
        {

            return response()->json([
                'code' => 0,
                'status' => 420,
                'message' => "Your watch hour or months are completed. Please subscribe the our plan.",
                'data' => (object) []
            ], 200);
        }
        $plan_check = Plan::where('id', $accessToQb->plan_id)->first();

        if ($plan_check->access_to_question_bank == 0) {
            return response()->json([
                'code' => 0,
                'status' => 420,
                'message' => "You do not have access to the question bank.",
                'data' => (object) []
            ], 200);
        }

        return $next($request);
    }
}
