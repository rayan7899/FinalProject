<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        try {
            $manager = $request->user()->manager;
            $student = $request->user()->student;
            $trainer = $request->user()->trainer;
            switch ($role) {
                case "خدمة المجتمع":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->hasRole($role)) {
                        return redirect(route('home'));
                    }
                    break;

                case "مدرب":
                    if ($trainer === null) {
                        return redirect(route('home'));
                    }
                    break;

                case "شؤون المتدربين":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->hasRole($role)) {
                        return redirect(route('home'));
                    }
                    break;

                case "الإدارة العامة":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->hasRole($role)) {
                        return redirect(route('home'));
                    }
                    break;

                case "الإرشاد":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->hasRole($role)) {
                        return redirect(route('home'));
                    }
                    break;

                case "رئيس قسم":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->isDepartmentManager()) {
                        return redirect(route('home'));
                    }
                    break;

                case "المشرف العام":
                    if ($manager === null) {
                        return redirect(route('home'));
                    }
                    if (!$manager->hasRole($role)) {
                        return redirect(route('home'));
                    }
                    break;

                case "متدرب":
                    if ($student === null) {
                        return redirect(route('home'));
                    }
                    break;
            }

            return $next($request);
        } catch (Exception $e) {
            Log::error($e->getMessage() . ' ' . $e);
            return back()->with("errer", "حدث خطأ غير معروف");
        }
    }
}
