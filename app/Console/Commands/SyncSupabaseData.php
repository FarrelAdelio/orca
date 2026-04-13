<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\SupabaseSyncService;

class SyncSupabaseData extends Command
{
    protected $signature = 'supabase:sync';
    protected $description = 'Sync data from Supabase to MySQL';

    protected $syncService;

    public function __construct(SupabaseSyncService $syncService)
    {
        parent::__construct();
        $this->syncService = $syncService;
    }

    public function handle()
    {
        $this->info('Starting Supabase sync...');
        
        $result = $this->syncService->sync();
        
        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error('Sync failed: ' . $result['error']);
        }
        
        return $result['success'] ? 0 : 1;
    }
}