<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Robot extends Model
{
    use HasFactory;

    protected $table = 'robots';
    
    protected $fillable = [
        'nama_robot', 'status', 'mode', 'baterai', 'status_wadah', 'last_update'
    ];
    
    protected $casts = [
        'last_update' => 'datetime',
    ];
}