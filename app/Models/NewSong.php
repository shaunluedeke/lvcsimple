<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
            return json_decode($this->info, false, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){
            return [];
        }
    }
}
