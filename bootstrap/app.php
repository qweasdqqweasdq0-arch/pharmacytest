<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandleInertiaRequests; // استدعاء الملف هنا
use App\Http\Middleware\RoleMiddleware;        // استدعاء الملف هنا

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        
        // تفعيل تمرير البيانات إلى Vue
        $middleware->web(append: [
            HandleInertiaRequests::class,
        ]);

        // تعريف صلاحيات الأدوار
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();