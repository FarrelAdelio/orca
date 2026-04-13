<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\LogSampah;
use App\Models\SupabaseSyncLog;

class SupabaseSyncService
{
    protected $supabaseUrl;
    protected $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    /**
     * Sync data dari Supabase ke MySQL
     */
    public function sync()
    {
        $lastSync = SupabaseSyncLog::latest()->first();
        $lastSyncTime = $lastSync ? $lastSync->last_sync_at : now()->subDays(7);
        
        try {
            // Ambil data dari Supabase yang belum disync
            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey,
            ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
                'order' => 'created_at.asc',
                'created_at' => 'gte.' . $lastSyncTime->toIso8601String(),
                'limit' => 100
            ]);
            
            if (!$response->successful()) {
                throw new \Exception('Failed to fetch from Supabase: ' . $response->body());
            }
            
            $detections = $response->json();
            $syncedCount = 0;
            
            foreach ($detections as $detection) {
                // Cek apakah sudah ada di MySQL berdasarkan supabase_id
                $exists = LogSampah::where('supabase_id', $detection['id'])->exists();
                
                if (!$exists) {
                    LogSampah::create([
                        'foto' => $detection['photo_url'],
                        'skor' => $detection['confidence'],
                        'label' => $detection['label'],
                        'supabase_id' => $detection['id'],
                        'detected_at' => $detection['created_at'],
                    ]);
                    $syncedCount++;
                }
            }
            
            // Log sync success
            SupabaseSyncLog::create([
                'last_sync_at' => now(),
                'synced_count' => $syncedCount,
                'status' => 'success',
            ]);
            
            Log::info("Supabase sync completed. Synced {$syncedCount} new records.");
            
            return [
                'success' => true,
                'synced_count' => $syncedCount,
                'message' => "Berhasil sync {$syncedCount} data baru"
            ];
            
        } catch (\Exception $e) {
            // Log sync error
            SupabaseSyncLog::create([
                'last_sync_at' => now(),
                'synced_count' => 0,
                'status' => 'error',
                'error_message' => $e->getMessage(),
            ]);
            
            Log::error('Supabase sync failed: ' . $e->getMessage());
            
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Get robot status (bisa dikembangkan)
     */
    public function getRobotStatus()
    {
        // Implementasi jika perlu ambil status robot dari ESP32 via Supabase
    }
}