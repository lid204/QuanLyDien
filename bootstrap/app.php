<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Thêm cấu hình tắt CSRF cho các route cụ thể tại đây
        $middleware->validateCsrfTokens(except: [
            '/khach-hang/*', 
            // Bạn có thể thêm các route khác vào đây nếu cần, ví dụ: '/hoa-don/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();