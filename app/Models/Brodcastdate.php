<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brodcastdate extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'weekday',
        'delay',
        'time',
        'last_broadcast',
        'ACTIVE'
    ];

    protected $casts = [
        'ACTIVE' => 'boolean',
        'last_broadcast' => 'integer',
        'delay' => 'integer',
        'time' => 'string',
        'weekday' => 'integer'
    ];
}
