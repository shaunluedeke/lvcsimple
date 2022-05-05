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
        'link',
        'NEXT',
        'ACTIVE'
    ];

    protected $casts = [
        'ACTIVE' => 'boolean',
        'last_broadcast' => 'integer',
        'delay' => 'integer',
        'time' => 'string',
        'weekday' => 'integer'
    ];

    public static function getNext(){
        $next = Brodcastdate::where('ACTIVE', 1)->where('NEXT', 1)->first();
        if($next){
            return $next;
        }
        return null;
    }

    public function getDay():string
    {
        $day = [
            "1" => "Montag",
            "2" => "Dienstag",
            "3" => "Mittwoch",
            "4" => "Donnerstag",
            "5" => "Freitag",
            "6" => "Samstag",
            "7" => "Sonntag"
        ];
        return $day[$this->weekday];
    }
}
