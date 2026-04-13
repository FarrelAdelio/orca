<?php

use App\Services\SupabaseSyncService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $syncService = app(SupabaseSyncService::class);
    $result = $syncService->sync();
    
    \Illuminate\Support\Facades\Log::info('Supabase sync result', $result);
})->everyFiveMinutes()->name('supabase-sync');
