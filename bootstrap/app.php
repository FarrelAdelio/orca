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
        //
    })

    // Alternatif cron job
    // ->withSchedule(function (Schedule $schedule) {
    //     // Sync dari Supabase setiap 5 menit
    //     $schedule->call(function () {
    //         $syncService = app(App\Services\SupabaseSyncService::class);
    //         $syncService->sync();
    //     })->everyFiveMinutes()->name('supabase-sync');
    // })

    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
