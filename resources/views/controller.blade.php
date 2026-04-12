@extends('layouts.app')

@section('title', 'Controller')

@section('content')
<div class="flex flex-col justify-center items-center px-4 py-10 min-h-screen">

    <!-- HEADER -->
    <div class="w-full max-w-md flex justify-between items-center mb-8">
        <div>
            <h1 class="text-2xl font-bold">Controller</h1>
            <p class="text-gray-500 text-sm">Mode Manual</p>

                    <div class="mt-3 flex flex-wrap gap-2">
                <span class="px-3 py-1 bg-green-100 text-green-600 rounded-full text-xs md:text-sm">Online</span>
                <span class="px-3 py-1 bg-blue-100 text-blue-600 rounded-full text-xs md:text-sm">Mode Otomatis</span>
        </div>
        </div>

        

        <a href="{{ route('dashboard') }}" 
           class="text-sm px-3 py-2 border rounded-lg hover:bg-gray-100">
            Kembali
        </a>
    </div>
    

    <!-- MODE BUTTON -->
    <div class="mb-6 w-full flex gap-3 max-w-md">
        <button class="w-full px-4 py-3 bg-black text-white rounded-lg text-sm"> 
            {{-- comment aja --}}
            Aktifkan Manual Mode
        </button>


    </div>

    <!-- CONTROLLER PAD -->
    <div class="grid grid-cols-3 gap-3 w-full max-w-xs">

    <div></div>

    <!-- UP -->
    <button class="border rounded-xl py-3 flex flex-col items-center justify-center hover:bg-gray-100 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
        <span class="text-xs">Maju</span>
    </button>

    <div></div>

    <!-- LEFT -->
    <button class="border rounded-xl py-3 flex flex-col items-center justify-center hover:bg-gray-100 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
        </svg>
        <span class="text-xs">Kiri</span>
    </button>

    <!-- STOP -->
    <button class="border rounded-xl py-3 flex flex-col items-center justify-center bg-gray-100 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-1" fill="currentColor" viewBox="0 0 24 24">
            <rect x="6" y="6" width="12" height="12" rx="2"/>
        </svg>
        <span class="text-xs">Stop</span>
    </button>

    <!-- RIGHT -->
    <button class="border rounded-xl py-3 flex flex-col items-center justify-center hover:bg-gray-100 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
        </svg>
        <span class="text-xs">Kanan</span>
    </button>

    <div></div>

    <!-- DOWN -->
    <button class="border rounded-xl py-3 flex flex-col items-center justify-center hover:bg-gray-100 active:scale-95 transition">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-7 h-7 mb-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
        <span class="text-xs">Mundur</span>
    </button>

    <div></div>

</div>

</div>
@endsection