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

    <!-- STATUS -->
    <section>
        <h2 class="text-xl md:text-2xl font-semibold mb-4">Status Sistem</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-6">

            <!-- KAPASITAS -->
            <div class="border rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-2">Kapasitas Sampah</p>
                <div class="flex justify-between text-sm mb-1">
                    <span>Terisi</span>
                    <span class="font-semibold">75%</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-black h-2 rounded-full" style="width: 75%"></div>
                </div>
            </div>

            <!-- BATTERY -->
            <div class="border rounded-xl p-4">
                <p class="text-sm text-gray-500 mb-2">Battery</p>
                <div class="flex justify-between text-sm mb-1">
                    <span>Status</span>
                    <span class="font-semibold">91%</span>
                </div>

                <div class="w-full bg-gray-200 rounded-full h-2">
                    <div class="bg-black h-2 rounded-full" style="width: 91%"></div>
                </div>
            </div>

        </div>
    </section>

    <!-- REALTIME LOG -->
    <section>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
            <h2 class="text-xl md:text-2xl font-semibold">Log Realtime</h2>

            <div class="flex flex-wrap gap-2">
                <button class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full md:w-auto">
                    Export
                </button>

                <button class="px-4 py-2 border rounded-lg text-sm w-full md:w-auto hover:bg-gray-100 transition">
                    Pindahkan ke History
                </button>
            </div>
        </div>

        <div class="border rounded-lg overflow-x-auto">
            <table class="w-full text-sm min-w-[500px]">
                <thead class="">
                    <tr>
                        <th class="p-3 text-lg text-left">No</th>
                        <th class="p-3 text-lg text-left">Jenis</th>
                        <th class="p-3 text-lg text-left">Berat</th>
                        <th class="p-3 text-lg text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="p-3">1</td>
                        <td class="p-3">Plastik</td>
                        <td class="p-3">1 kg</td>
                        <td class="p-3">10:30</td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="flex justify-end mt-3">
    <a href="#" 
       class="px-4 py-2 border text-black rounded-lg text-sm w-full md:w-auto hover:bg-gray-100 transition">
        Lihat Semua 
    </a>
</div>
    </section>

    <!-- HISTORY -->
    <section>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-3 mb-4">
            <h2 class="text-xl md:text-2xl font-semibold">Log History</h2>

            <button class="px-4 py-2 bg-black text-white rounded-lg text-sm w-full md:w-auto">
                Export
            </button>
        </div>

        <div class="border rounded-lg overflow-x-auto">
            <table class="w-full text-sm min-w-[500px] ">
                <thead class="">
                    <tr>
                        <th class="p-3 text-lg text-left">No</th>
                        <th class="p-3 text-lg text-left">Jenis</th>
                        <th class="p-3 text-lg text-left">Berat</th>
                        <th class="p-3 text-lg text-left">Waktu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="p-3">1</td>
                        <td class="p-3">Botol</td>
                        <td class="p-3">1 kg</td>
                        <td class="p-3">09:10</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="flex justify-end mt-3">
    <a href="#" 
       class="px-4 py-2 border text-black rounded-lg text-sm w-full md:w-auto hover:bg-gray-100">
        Lihat Semua 
    </a>
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
@endsection

<!-- telah memperbaiki isue -->
<!-- komentar issues -->