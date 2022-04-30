<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MainController extends Controller
{
    public static function removeSymbol(string $txt):string{
        return str_replace(array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'"),
            array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "","&sect;","&amp;","&apos;"), $txt);
    }

    public static function addSymbol(string $txt):string
    {
        return str_replace(array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "´","&sect;","&amp;","&apos;"),
            array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "","§","&", "'"), $txt);
    }

    public static function deleteSymbol(string $txt):string
    {
        return str_replace(array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'"),
            array("", "", "", "", "", "", "", "","","",""), $txt);
    }
}
