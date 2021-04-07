<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        $manager = $request->user()->manager;
        $student = $request->user()->student;
        switch ($role) {
            case "خدمة المجتمع":
            case "شؤون المتدربين":
            case "الإرشاد":
                if ($manager === null) {
                    return redirect(route('home'));
                }
                if (!$manager->hasRole($role)) {
                    return redirect(route('home'));
                }
                break;
            case "رئيس قسم":
                // FIXME: impelement this role
                return redirect(route('home'));
                break;
            case "متدرب":
                if ($student === null) {
                    return redirect(route('home'));
                }
                break;
        }
        return $next($request);
    }
}
