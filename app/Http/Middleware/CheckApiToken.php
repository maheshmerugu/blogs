<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle($request, Closure $next)
    {
        $user = auth('api_user')->user();
    
        if (!empty($user->id)) {
            $is_exists = User::where('id', $user->id)->where('status', 1)->exists();
            
            if ($is_exists) {
                return $next($request);
            }
        }
    
        $response = [
            'code'=>0,
            'status' => 200,
            'message' => "Unauthenticated.",
            'data' => (object) []
        ];
    
        return response()->json($response, 401);
    }

}
