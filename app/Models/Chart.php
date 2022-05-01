<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Chart extends Model
{
    use HasFactory;

    protected $fillable = [
        'song_ids', 'votes', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'song_ids' => 'string',
        'votes' => 'string',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean'
    ];

    public function songs()
    {
        return $this->belongsToMany('App\Models\Song');
    }

    public function getSongs()
    {
        $a = [];
        $return = [];
        try {
            $a = json_decode($this->song_ids, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }
        foreach ($a as $key) {
            $return[] = Song::find($key);
        }

        return $return;
    }

    public function getVotes()
    {
        $a = [];
        try {
            $a = json_decode($this->votes, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
        }

        return $a;
    }

    public function userhasVoted()
    {
        if (Auth::user() === null) {
            return false;
        }
        $votes = $this->getVotes();
        if (isset($votes[Auth::user()->id])) {
            return true;
        }
        return false;
    }

    public function getUserVoted(): array
    {
        if (!$this->userhasVoted()) {
            return [];
        }
        return $this->getVotes()[Auth::user()->id] ?? [];
    }

    public function getTopSongs(): array
    {
        $ar = array();
        $votes = $this->getVotes();
        foreach ($votes as $key => $value) {
            foreach ($value as $key2 => $value2) {
                $ar[$key2] = (($ar[$key2]) ?? 0) + $value2;
            }
        }
        $songs = $this->getSongs();
        foreach ($songs as $key) {
            if (((int)($ar[$key->id] ?? 0)) === 0) {
                $ar[$key->id] = 0;
            }
        }
        arsort($ar);
        $place = 1;
        $return = [];
        foreach ($ar as $key => $value) {
            $return[$key] = [
                'place' => $place,
                'song' => Song::find($key),
                'votes' => $value
            ];
            $place++;
        }
        return $return;
    }

    public function isActive():bool{
        return $this->is_active && $this->autoset === 1;
    }

    public function getUserVotedSong():array
    {
        $top = $this->getTopSongs();
        arsort($top, SORT_NUMERIC);
        $votes = $this->getUserVoted();
        arsort($votes, SORT_NUMERIC);
        $s = [];
        $re = [];
        $place = 1;
        foreach ($top as $key => $value) {
            $s[$key] = $place;
            $place++;
        }
        foreach ($votes as $key => $value) {
            $re[$key] = ['id' => $key, 'votes' => $value, 'place' => $s[$key]];
        }
        return $re;
    }
}
