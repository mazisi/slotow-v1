<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class UserLastActivity
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next){
        // Cache::flush();
        try {  
            if(Auth::check()){
                $isUserOnline = Cache::get('user-online-'.auth()->id());
            if(!$isUserOnline){
                Cache::put('user-online-'.auth()->id(), true, now()->addMinutes(20));
               auth()->user()->update(['last_activity_at' => now()]);
            }
            }
            
        } catch (\Throwable $th) {
            //throw $th;
            
        }
        return $next($request);
    }
}
