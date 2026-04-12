@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4">

    <div class="w-full max-w-md">

        <!-- TITLE -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Orca System</h1>
            <p class="text-gray-500 text-sm">
                Autonomous Ocean Trash Collector
            </p>
        </div>

        <!-- CARD -->
        <div class=" rounded-xl p-6 space-y-5">

            <h2 class="text-xl font-semibold text-center">Login</h2>

            <!-- FORM -->
            <form action="{{ route('dashboard') }}" method="GET" class="space-y-4">

                <!-- EMAIL -->
                <div>
                    <label class="text-sm text-gray-600">Email</label>
                    <input 
                        type="email" 
                        placeholder="orca@gmail.com"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-black"
                    >
                </div>

                <!-- PASSWORD -->
                <div>
                    <label class="text-sm text-gray-600">Password</label>
                    <input 
                        type="password" 
                        placeholder="••••••••"
                        class="w-full mt-1 px-4 py-2 border rounded-lg focus:outline-none focus:ring-1 focus:ring-black"
                    >
                </div>

                <!-- BUTTON -->
                <button 
                    type="submit"
                    class="w-full py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition">
                    Masuk
                </button>

            </form>

        </div>

        <!-- FOOTER -->
        <!-- <p class="text-center text-xs text-gray-400 mt-6">
            © 2026 Orca
        </p> -->

    </div>

</div>
@endsection


{{-- tes komen fix issue --}}