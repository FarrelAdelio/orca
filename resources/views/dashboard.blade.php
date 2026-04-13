@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="max-w-6xl mx-auto px-4 md:px-6 py-8 md:py-10 space-y-12">

        <!-- HEADER -->
        <section class="flex flex-col md:flex-row md:justify-between md:items-center gap-4">
            <div>
                <h1 class="text-3xl md:text-4xl font-bold mb-1">Orca Monitoring</h1>
                <p class="text-gray-600 text-sm md:text-base">Autonomous Ocean Trash Collector System</p>

                <div class="mt-3 flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs md:text-sm">Online</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs md:text-sm">Mode Otomatis</span>
                </div>
            </div>

            <a href="{{ route('login') }}" class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full md:w-auto">
                Keluar
            </a>
        </section>

        <!-- STATUS (Dinamis dari Supabase) -->
        <section>
            <h2 class="text-xl md:text-2xl font-semibold mb-4">Status Sistem</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

                <!-- TOTAL DETEKSI -->
                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-2">Total Deteksi Sampah</p>
                    <p class="text-3xl font-bold" id="stat-total">0</p>
                    <p class="text-sm text-gray-500 mt-2">Data dari Supabase</p>
                </div>

                <!-- HARI INI -->
                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-2">Deteksi Hari Ini</p>
                    <p class="text-3xl font-bold" id="stat-today">0</p>
                    <p class="text-sm text-gray-500 mt-2">Update realtime</p>
                </div>

                <!-- RATA-RATA CONFIDENCE -->
                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-2">Rata-rata Confidence</p>
                    <p class="text-3xl font-bold" id="stat-avg">0%</p>
                    <p class="text-sm text-gray-500 mt-2">Akurasi deteksi</p>
                </div>

                <!-- SYNC STATUS -->
                <div class="border rounded-xl p-4">
                    <p class="text-sm text-gray-500 mb-2">Sinkronisasi</p>
                    <button onclick="manualSync()" class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full">
                        🔄 Sync ke History
                    </button>
                    <p class="text-xs text-gray-400 mt-2" id="sync-status">Terakhir sync: -</p>
                </div>

            </div>
        </section>

        <!-- REALTIME LOG (DARI SUPABASE) -->
        <section>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
                <h2 class="text-xl md:text-2xl font-semibold">
                    Log Realtime
                    <span class="text-xs bg-red-500 text-white px-2 py-1 rounded-full ml-2">LIVE</span>
                </h2>

                <div class="flex flex-wrap gap-2">
                    <button onclick="exportToCSV()"
                        class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full md:w-auto">
                        Export CSV
                    </button>

                    <button onclick="moveAllToHistory()"
                        class="px-4 py-2 border rounded-lg text-sm w-full md:w-auto hover:bg-gray-100 transition">
                        Pindahkan semua ke History
                    </button>
                </div>
            </div>

            <div class="border rounded-lg overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]" id="realtime-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Foto</th>
                            <th class="p-3 text-left">Jenis</th>
                            <th class="p-3 text-left">Skor</th>
                            <th class="p-3 text-left">Waktu</th>
                            <th class="p-3 text-left">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="realtime-tbody">
                        <tr class="border-t">
                            <td colspan="6" class="p-3 text-center text-gray-500">Loading data...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-3">
                <div class="text-sm text-gray-500" id="realtime-info"></div>
                <button onclick="loadMoreRealtime()"
                    class="px-4 py-2 border text-black rounded-lg text-sm hover:bg-gray-100 transition">
                    Load More
                </button>
            </div>
        </section>

        <!-- HISTORY (DARI MYSQL) -->
        <section>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
                <h2 class="text-xl md:text-2xl font-semibold">Log History (MySQL)</h2>

                <button onclick="exportHistoryToCSV()"
                    class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full md:w-auto">
                    Export CSV
                </button>
            </div>

            <div class="border rounded-lg overflow-x-auto">
                <table class="w-full text-sm min-w-[500px]" id="history-table">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-3 text-left">No</th>
                            <th class="p-3 text-left">Foto</th>
                            <th class="p-3 text-left">Jenis</th>
                            <th class="p-3 text-left">Skor</th>
                            <th class="p-3 text-left">Waktu</th>
                        </tr>
                    </thead>
                    <tbody id="history-tbody">
                        <tr class="border-t">
                            <td colspan="5" class="p-3 text-center text-gray-500">Loading history...</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="flex justify-between mt-3">
                <div class="text-sm text-gray-500" id="history-info"></div>
                <button onclick="loadMoreHistory()"
                    class="px-4 py-2 border text-black rounded-lg text-sm hover:bg-gray-100 transition">
                    Load More
                </button>
            </div>
        </section>

        <!-- NAVIGATE TO CONTROLLER -->
        <section>
            <div class="flex flex-col items-start gap-3">
                <h2 class="text-xl md:text-2xl font-semibold">Controller</h2>

                <a href="{{ route('controller') }}"
                    class="px-6 py-3 bg-black text-white rounded-lg text-sm hover:bg-gray-800 transition w-fit">
                    Buka Controller
                </a>
            </div>
        </section>

    </div>

    <script>
        // State management
        let realtimeData = [];
        let realtimePage = 1;
        let historyPage = 1;
        const perPage = 10;

        // CSRF Token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || '{{ csrf_token() }}';

        // ========== FORMAT WAKTU ==========
        function formatTime(timestamp) {
            if (!timestamp) return '-';
            const date = new Date(timestamp);
            return date.toLocaleString('id-ID', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });
        }

        // ========== FORMAT SKOR ==========
        function formatScore(score) {
            return (score * 100).toFixed(1) + '%';
        }

        // ========== LOAD STATISTIK ==========
        async function loadStats() {
            try {
                const response = await fetch('/api/waste/stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('stat-total').innerText = data.stats.total || 0;
                    document.getElementById('stat-today').innerText = data.stats.today || 0;
                    document.getElementById('stat-avg').innerText = (data.stats.avg_confidence || 0) + '%';

                    const syncStatus = document.getElementById('sync-status');
                    if (syncStatus) {
                        syncStatus.innerText = 'Total data: ' + (data.stats.total || 0) + ' record';
                    }
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // ========== LOAD REALTIME DATA ==========
        async function loadRealtime() {
            try {
                const response = await fetch(`/api/waste/latest?limit=${perPage * realtimePage}`);
                const data = await response.json();

                if (data.success) {
                    realtimeData = data.data;
                    renderRealtimeTable();

                    document.getElementById('realtime-info').innerText =
                        `Menampilkan ${realtimeData.length} data terbaru dari Supabase`;
                }
            } catch (error) {
                console.error('Error loading realtime:', error);
                document.getElementById('realtime-tbody').innerHTML =
                    '<tr class="border-t"><td colspan="6" class="p-3 text-center text-red-500">Gagal memuat data</td></tr>';
            }
        }

        // ========== RENDER REALTIME TABLE ==========
        function renderRealtimeTable() {
            const tbody = document.getElementById('realtime-tbody');

            if (!realtimeData || realtimeData.length === 0) {
                tbody.innerHTML =
                    '<tr class="border-t"><td colspan="6" class="p-3 text-center text-gray-500">Belum ada data deteksi</td></tr>';
                return;
            }

            tbody.innerHTML = realtimeData.map((item, index) => `
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">${index + 1}</td>
                <td class="p-3">
                    <img src="${item.photo_url}" alt="Foto" class="w-12 h-12 object-cover rounded" 
                         onerror="this.src='https://placehold.co/50x50?text=No+Image'">
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 bg-blue-100 text-blue-600 rounded-full text-xs">
                        ${item.label}
                    </span>
                </td>
                <td class="p-3">
                    <span class="font-semibold ${item.confidence >= 0.7 ? 'text-green-600' : 'text-yellow-600'}">
                        ${formatScore(item.confidence)}
                    </span>
                </td>
                <td class="p-3 text-gray-500 text-sm">${formatTime(item.created_at)}</td>
                <td class="p-3">
                    <button onclick="moveToHistory(${item.id})" 
                            class="px-3 py-1 bg-gray-100 text-gray-600 rounded text-xs hover:bg-gray-200">
                        Pindahkan
                    </button>
                </td>
            </tr>
        `).join('');
        }

        // ========== LOAD HISTORY (DARI MYSQL) ==========
        async function loadHistory() {
            try {
                const response = await fetch(`/api/waste/history?page=${historyPage}&per_page=${perPage}`);
                const data = await response.json();

                if (data.success) {
                    renderHistoryTable(data.data);

                    document.getElementById('history-info').innerText =
                        `Halaman ${historyPage} - Menampilkan ${data.data.length} data dari MySQL`;
                }
            } catch (error) {
                console.error('Error loading history:', error);
                document.getElementById('history-tbody').innerHTML =
                    '<tr class="border-t"><td colspan="5" class="p-3 text-center text-red-500">Gagal memuat history</td></tr>';
            }
        }

        // ========== RENDER HISTORY TABLE ==========
        function renderHistoryTable(data) {
            const tbody = document.getElementById('history-tbody');

            if (!data || data.length === 0) {
                tbody.innerHTML =
                    '<tr class="border-t"><td colspan="5" class="p-3 text-center text-gray-500">Belum ada data history</td></tr>';
                return;
            }

            tbody.innerHTML = data.map((item, index) => `
            <tr class="border-t hover:bg-gray-50">
                <td class="p-3">${((historyPage - 1) * perPage) + index + 1}</td>
                <td class="p-3">
                    <img src="${item.foto || item.photo_url}" alt="Foto" class="w-12 h-12 object-cover rounded" 
                         onerror="this.src='https://placehold.co/50x50?text=No+Image'">
                </td>
                <td class="p-3">
                    <span class="px-2 py-1 bg-gray-100 text-gray-600 rounded-full text-xs">
                        ${item.label}
                    </span>
                </td>
                <td class="p-3">
                    <span class="font-semibold">${formatScore(item.skor || item.confidence)}</span>
                </td>
                <td class="p-3 text-gray-500 text-sm">${formatTime(item.detected_at || item.created_at)}</td>
            </tr>
        `).join('');
        }

        // ========== MOVE TO HISTORY (SINGLE) ==========
        async function moveToHistory(id) {
            try {
                const response = await fetch(`/api/waste/move-to-history/${id}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                // Debug: lihat response lengkap
                console.log('Response:', result);

                if (result.success) {
                    alert('✅ ' + result.message);
                    loadRealtime();
                    loadHistory();
                    loadStats();
                } else {
                    alert('❌ Gagal: ' + (result.error || JSON.stringify(result)));
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Error: ' + error.message);
            }
        }

        // ========== MOVE ALL TO HISTORY ==========
        // ========== MOVE ALL TO HISTORY (UPDATE IS_HISTORY) ==========
        async function moveAllToHistory() {
            if (!confirm('Pindahkan SEMUA data live ke history?')) return;

            try {
                const response = await fetch('/api/waste/move-all-to-history', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ ' + result.message);
                    loadRealtime(); // Refresh live table
                    loadHistory(); // Refresh history table
                    loadStats(); // Refresh stats
                } else {
                    alert('❌ Gagal: ' + result.error);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            }
        }

        // ========== SYNC TO MYSQL (HISTORY ONLY) ==========
        async function manualSync() {
            const btn = event.target;
            const originalText = btn.innerText;
            btn.innerText = 'Syncing...';
            btn.disabled = true;

            try {
                const response = await fetch('/api/waste/sync', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                const result = await response.json();

                if (result.success) {
                    alert('✅ ' + result.message);
                    loadHistory(); // Refresh history table
                    loadStats(); // Refresh stats
                } else {
                    alert('❌ Gagal: ' + result.error);
                }
            } catch (error) {
                alert('Error: ' + error.message);
            } finally {
                btn.innerText = originalText;
                btn.disabled = false;
            }
        }

        // ========== EXPORT TO CSV ==========
        function exportToCSV() {
            if (!realtimeData.length) {
                alert('Tidak ada data untuk diexport');
                return;
            }

            const headers = ['ID', 'Label', 'Confidence', 'Waktu Deteksi', 'URL Foto'];
            const rows = realtimeData.map(item => [
                item.id,
                item.label,
                (item.confidence * 100).toFixed(2) + '%',
                formatTime(item.created_at),
                item.photo_url
            ]);

            const csvContent = [headers, ...rows].map(row => row.join(',')).join('\n');
            const blob = new Blob([csvContent], {
                type: 'text/csv'
            });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `waste_realtime_${new Date().toISOString().slice(0,19)}.csv`;
            a.click();
            URL.revokeObjectURL(url);
        }

        function exportHistoryToCSV() {
            alert('Fitur export history akan menyusul');
        }

        // ========== LOAD MORE ==========
        function loadMoreRealtime() {
            realtimePage++;
            loadRealtime();
        }

        function loadMoreHistory() {
            historyPage++;
            loadHistory();
        }

        // ========== AUTO REFRESH ==========
        let autoRefreshInterval;

        function startAutoRefresh() {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(() => {
                loadRealtime();
                loadStats();
            }, 10000);
        }

        // ========== INIT ==========
        document.addEventListener('DOMContentLoaded', () => {
            loadStats();
            loadRealtime();
            loadHistory();
            startAutoRefresh();
        });
    </script>
@endsection
