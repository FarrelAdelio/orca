<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LogController extends Controller
{
    public function move()
    {
        // sementara dummy dulu
        // nanti ini isi database logic

        return back()->with('success', 'Data berhasil dipindahkan!');
    }
}