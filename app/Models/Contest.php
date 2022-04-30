<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'user_ids', 'start_date', 'end_date', 'is_active'
    ];

    protected $casts = [
        'user_ids' => 'array',
        'is_active' => 'boolean'
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function getUsers(){
        $a = [];
        $return = [];
        try{
            $a = json_decode($this->user_ids, false, 512, JSON_THROW_ON_ERROR);
        }catch (\JsonException $e){}
        foreach ($a as $key){
            $return[] = User::find($key);
        }

        return $return;
    }

}
