<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogSampah extends Model
{
    use HasFactory;

    protected $table = 'log_sampah';
    
    protected $fillable = [
        'foto', 'skor', 'label', 'supabase_id', 'detected_at', 'is_history',
    ];
    
    protected $casts = [
        'detected_at' => 'datetime',
        'skor' => 'float',
        'is_history' => 'boolean'
    ];
}