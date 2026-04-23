<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // إذا كان المستخدم غير مسجل دخول أو رتبته لا تطابق الرتبة المطلوبة
        if (!$request->user() || $request->user()->role !== $role) {
            return redirect('/dashboard')->with('error', 'ليس لديك صلاحية للدخول لهذه الصفحة');
        }

        return $next($request);
    }
}