<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class NewSong extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'info', 'file'];

    protected $casts = [
        'info' => 'string',
    ];

    public function getInfo()
    {
        try{
            return json_decode($this->info, true, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){
            return [];
        }
    }

    public function getURL()
    {
        return Storage::disk('public')->url("song/new/".$this->file);
    }
}
