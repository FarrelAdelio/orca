<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Operator extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'operators';
    
    protected $fillable = [
        'username', 'nama', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];
}