<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AdController extends Controller
{

    public static function getRandomAd()
    {
        $files = Storage::disk('public')->allFiles("ads");
        return storage_path('app/public/') .($files[array_rand($files)]);
    }

    public static function getArray($file): array
    {
        $txt = array();
        if (file_exists($file) && $fileman = fopen($file, 'rb')) {
            while (!feof($fileman)) {
                $txt[]=fgets($fileman);
            }
        }
        return $txt;
    }

    public static function getTitle($file): string
    {
        $txt = self::getArray($file);
        return $txt[0];
    }

    public static function getString($file, int $offset = 0): string
    {
        $a = self::getArray($file);
        $txt = '';
        for($i = $offset, $iMax = count($a); $i < $iMax; $i++) {
            $txt .= $a[$i] . '<br>';
        }
        return $txt;
    }
}
