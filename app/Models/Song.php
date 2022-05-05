<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Song extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'info', 'file', 'comments', 'likes', 'dislikes', 'is_active'];

    protected $casts = [
        'info' => 'string',
        'comments' => 'string',
        'likes' => 'string',
        'dislikes' => 'string',
        'is_active' => 'boolean'
    ];


    public function getInfo()
    {
        try {
            return json_decode($this->info, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        return [];
    }

    public function getComments():array
    {
        try {
            return json_decode($this->comments, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        return [];
    }

    public function getLikes()
    {
        $a = [];
        $return = [];
        try {
            $a = json_decode($this->likes, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        foreach ($a as $key => $value) {
            $return[User::find($key)->id] = $value;
        }

        return $return;
    }

    public function getDislikes()
    {
        $a = [];
        $return = [];
        try {
            $a = json_decode($this->dislikes, false, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        foreach ($a as $key => $value) {
            $return[User::find($key)->id] = $value;
        }

        return $return;
    }

    public function isNewSong(): bool
    {
        $allcharts = Chart::all();
        $i = 0;
        foreach ($allcharts as $chart) {
            $songs = $chart->getSongs();
            foreach ($songs as $song) {
                if ($song->id === $this->id) {
                    $i++;
                }
            }
        }
        return $i < 2;
    }

    public function getURL()
    {
        return Storage::disk('public')->url("song/".$this->file);
    }

    public static function getAllSongsSortedbyLikes()
    {
        $allcharts = Song::all();
        $songs = [];
        foreach ($allcharts as $chart) {
            $songs[$chart->id] = $chart->getLikes();
        }
        arsort($songs);
        return $songs;
    }

    public function getSongLog(){
        return $this->belongsTo('App\Models\SongLog');
    }
}
