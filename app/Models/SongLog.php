<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SongLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_id',
        'status_id'
    ];

    protected $casts = [
        'song_id' => 'integer',
        'status_id' => 'integer'
    ];

    public function song()
    {
        return $this->belongsTo('App\Models\Song');
    }

}
