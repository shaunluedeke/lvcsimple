<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_ids', 'votes', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'song_ids' => 'array',
        'votes' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function songs()
    {
        return $this->belongsToMany('App\Models\Song');
    }

    public function getSongs(){
        $a = [];
        $return = [];
        try{
            $a = json_decode($this->song_ids, false, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){}
        foreach ($a as $key){
            $return[] = Song::find($key);
        }

        return $return;
    }

    public function getVotes(){
        $a = [];
        $return = [];
        try{
            $a = json_decode($this->votes, false, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){}
        foreach ($a as $key => $value){
            $return[User::find($key)] = $value;
        }

        return $return;
    }
}
