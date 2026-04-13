<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\LogSampah;

class WasteController extends Controller
{
    protected $supabaseUrl;
    protected $supabaseKey;

    public function __construct()
    {
        $this->supabaseUrl = env('SUPABASE_URL');
        $this->supabaseKey = env('SUPABASE_KEY');
    }

    /**
     * Tampilkan dashboard
     */
    public function dashboard()
    {
        return view('dashboard');
    }

    /**
     * Get latest detections (hanya yang is_history = false)
     */
    public function getLatest(Request $request)
    {
        $limit = $request->get('limit', 20);
        
        $response = Http::withHeaders([
            'apikey' => $this->supabaseKey,
            'Authorization' => 'Bearer ' . $this->supabaseKey,
        ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
            'select' => '*',
            'order' => 'created_at.desc',
            'is_history' => 'eq.false',  // HANYA DATA LIVE
            'limit' => $limit
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Failed to fetch data'], $response->status());
        }

        return response()->json([
            'success' => true,
            'data' => $response->json()
        ]);
    }

    /**
     * Get history (hanya dari MySQL yang is_history = true)
     */
    public function getHistory(Request $request)
    {
        $page = $request->get('page', 1);
        $perPage = $request->get('per_page', 20);
        
        $history = LogSampah::where('is_history', true)
            ->orderBy('detected_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'success' => true,
            'data' => $history->items(),
            'page' => $history->currentPage(),
            'per_page' => $history->perPage(),
            'total' => $history->total(),
            'last_page' => $history->lastPage()
        ]);
    }

    /**
     * Get statistics
     */
    public function getStats()
    {
        // Ambil dari Supabase untuk data live
        $response = Http::withHeaders([
            'apikey' => $this->supabaseKey,
            'Authorization' => 'Bearer ' . $this->supabaseKey,
        ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
            'select' => 'confidence,label,created_at,is_history',
            'order' => 'created_at.desc',
            'limit' => 1000
        ]);

        $supabaseData = $response->successful() ? $response->json() : [];
        
        // Pisahkan live dan history dari Supabase
        $liveCount = count(array_filter($supabaseData, fn($item) => $item['is_history'] == false));
        
        // Ambil history count dari MySQL
        $historyCount = LogSampah::where('is_history', true)->count();
        
        $total = $liveCount + $historyCount;
        
        // Hitung rata-rata confidence dari live data
        $liveConfidence = array_column(array_filter($supabaseData, fn($item) => $item['is_history'] == false), 'confidence');
        $avgConfidence = !empty($liveConfidence) ? array_sum($liveConfidence) / count($liveConfidence) : 0;
        
        // Count by label dari live data
        $labelCount = [];
        foreach ($supabaseData as $item) {
            if ($item['is_history'] == false) {
                $label = $item['label'];
                $labelCount[$label] = ($labelCount[$label] ?? 0) + 1;
            }
        }
        
        // Today's count dari live data
        $today = date('Y-m-d');
        $todayCount = 0;
        foreach ($supabaseData as $item) {
            if ($item['is_history'] == false && substr($item['created_at'], 0, 10) == $today) {
                $todayCount++;
            }
        }
        
        $latestTime = !empty($supabaseData) ? $supabaseData[0]['created_at'] : null;

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $total,
                'live' => $liveCount,
                'history' => $historyCount,
                'today' => $todayCount,
                'avg_confidence' => round($avgConfidence * 100, 1),
                'latest_time' => $latestTime,
                'by_label' => $labelCount
            ]
        ]);
    }



public function moveToHistory($id)
{
    try {
        $serviceKey = env('SUPABASE_SERVICE_ROLE_KEY');
        
        $url = $this->supabaseUrl . '/rest/v1/waste_detections?id=eq.' . $id;
        
        $response = Http::withHeaders([
            'apikey' => $serviceKey,
            'Authorization' => 'Bearer ' . $serviceKey,
            'Content-Type' => 'application/json',
        ])->patch($url, [
            'is_history' => true
        ]);

        Log::info('Supabase Update Status: ' . $response->status());
        
        if ($response->successful()) {
            // Ambil data dari Supabase
            $getResponse = Http::withHeaders([
                'apikey' => $serviceKey,
                'Authorization' => 'Bearer ' . $serviceKey,
            ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
                'id' => 'eq.' . $id,
                'select' => '*'
            ]);
            
            $detection = $getResponse->json()[0] ?? null;
            
            if ($detection) {
                // Simpan/Update ke MySQL
                $saved = LogSampah::updateOrCreate(
                    ['supabase_id' => $detection['id']],
                    [
                        'foto' => $detection['photo_url'],
                        'skor' => $detection['confidence'],
                        'label' => $detection['label'],
                        'supabase_id' => $detection['id'],
                        'detected_at' => $detection['created_at'],
                        'is_history' => true
                    ]
                );
                
                Log::info('MySQL saved: ID=' . $saved->id . ', is_history=' . $saved->is_history);
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil dipindahkan ke history'
            ]);
        }
        
        return response()->json([
            'error' => 'Update gagal: ' . $response->body()
        ], 500);

    } catch (\Exception $e) {
        Log::error('Move error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

/**
 * Move all live data to history
 */
public function moveAllToHistory()
{
    try {
        $serviceKey = env('SUPABASE_SERVICE_ROLE_KEY');
        
        // Ambil semua data live
        $response = Http::withHeaders([
            'apikey' => $serviceKey,
            'Authorization' => 'Bearer ' . $serviceKey,
        ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
            'is_history' => 'eq.false',
            'limit' => 1000
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'Gagal ambil data'], 500);
        }

        $detections = $response->json();
        
        if (empty($detections)) {
            return response()->json([
                'success' => true,
                'moved_count' => 0,
                'message' => 'Tidak ada data live'
            ]);
        }

        $ids = array_column($detections, 'id');
        $idsString = implode(',', $ids);
        $updateUrl = $this->supabaseUrl . '/rest/v1/waste_detections?id=in.(' . $idsString . ')';
        
        $updateResponse = Http::withHeaders([
            'apikey' => $serviceKey,
            'Authorization' => 'Bearer ' . $serviceKey,
            'Content-Type' => 'application/json',
        ])->patch($updateUrl, [
            'is_history' => true
        ]);

        if ($updateResponse->successful()) {
            foreach ($detections as $detection) {
                LogSampah::updateOrCreate(
                    ['supabase_id' => $detection['id']],
                    [
                        'foto' => $detection['photo_url'],
                        'skor' => $detection['confidence'],
                        'label' => $detection['label'],
                        'supabase_id' => $detection['id'],
                        'detected_at' => $detection['created_at'],
                        'is_history' => true
                    ]
                );
            }
            
            return response()->json([
                'success' => true,
                'moved_count' => count($detections),
                'message' => 'Berhasil memindahkan ' . count($detections) . ' data ke history'
            ]);
        }

        return response()->json([
            'error' => 'Gagal update: ' . $updateResponse->body()
        ], 500);

    } catch (\Exception $e) {
        Log::error('Move all error: ' . $e->getMessage());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    /**
     * Sync data from Supabase to MySQL (opsional)
     */
    public function syncToMySQL(Request $request)
    {
        try {
            // Ambil data yang sudah di-history dari Supabase
            $response = Http::withHeaders([
                'apikey' => $this->supabaseKey,
                'Authorization' => 'Bearer ' . $this->supabaseKey,
            ])->get($this->supabaseUrl . '/rest/v1/waste_detections', [
                'is_history' => 'eq.true',
                'order' => 'created_at.desc',
                'limit' => 100
            ]);

            if (!$response->successful()) {
                return response()->json(['error' => 'Failed to fetch from Supabase'], 500);
            }

            $detections = $response->json();
            $syncedCount = 0;

            foreach ($detections as $detection) {
                $exists = LogSampah::where('supabase_id', $detection['id'])->exists();
                
                if (!$exists) {
                    LogSampah::create([
                        'foto' => $detection['photo_url'],
                        'skor' => $detection['confidence'],
                        'label' => $detection['label'],
                        'supabase_id' => $detection['id'],
                        'detected_at' => $detection['created_at'],
                        'is_history' => true
                    ]);
                    $syncedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'synced_count' => $syncedCount,
                'message' => "Berhasil sync {$syncedCount} data history ke MySQL"
            ]);

        } catch (\Exception $e) {
            Log::error('Sync failed: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}