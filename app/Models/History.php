<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class History extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function getContent(){
        try{
            return json_decode($this->content, false, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){
            return [];
        }

    }
}
