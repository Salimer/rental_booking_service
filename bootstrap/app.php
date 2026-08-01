<?php

use App\Http\Middleware\AuthenticateViaMonolith;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        apiPrefix: 'api/v1',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.monolith' => AuthenticateViaMonolith::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );

        $exceptions->render(function (\Illuminate\Http\Exceptions\PostTooLargeException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'message' => 'حجم الملفات المرفقة كبير جداً ويتجاوز الحد المسموح به للم خادم (Post Data Too Large).'
                ], 413);
            }

            return back()->with('error', 'حجم الملفات أو الصور المرفقة كبير جداً ويتجاوز الحد المسموح به للم خادم. يرجى اختيار صور بأحجام أصغر وإعادة المحاولة.');
        });
    })->create();
