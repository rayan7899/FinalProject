<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TrainerUpdated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // dd($request->getRequestUri());
        if (Auth::user()->trainer !== null) {
            if (Auth::user()->trainer->data_updated == false) {
                return redirect(route('updateNewTrainerForm'));
            }
        }
        return $next($request);
    }
}
