<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Agreement
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
        // dd(Auth::user()->agreement);
        if(Auth::user()->agreement != 0){
            return $next($request);
        }
        else{
            return redirect(route('AgreementForm'))->with('error', 'يجب الموافقة على الشروط اولا');
        }

    }
}
