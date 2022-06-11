<?php

namespace App\Http\Controllers;

use App\Models\NewSong;
use App\Models\Song;
use App\Models\SongLog;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class MainController extends Controller
{
    public static function removeSymbol(string $txt):string{
        $character = array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'");
        $replace = array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "","&sect;","&amp;","&apos;");
        for ($i = 0, $iMax = count($character); $i < $iMax; $i++) {
            $txt = str_replace($character[$i], $replace[$i], $txt);
        }
        return $txt;
    }

    public static function addSymbol(string $txt):string
    {
        $character= array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "","&sect;","&amp;","&apos;");
        $replace = array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'");
        for ($i = 0, $iMax = count($character); $i < $iMax; $i++) {
            $txt = str_replace($character[$i], $replace[$i], $txt);
        }
        return $txt;
    }

    public static function deleteSymbol(string $txt):string
    {
        $character = array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'");
        foreach ($character as $i) {
            $txt = str_replace($i, "", $txt);
        }
        return $txt;
    }

    public static function isLogin(){
        if(Auth::user() === null || Auth::user()->userID === 0){
            return redirect()->route('login');
        }
        return true;
    }


    public function newsong(Request  $request){
        $request->validate([
            'name' => 'required:max:255',
            'author' => 'required:max:255',
            'datei'=> 'required:max:4000'
        ]);

        if($this->isLogin()!==true){return $this->isLogin();}
        $file = $request->file('datei');
        if($file === null){
            return redirect()->back()->withErrors(['datei' => 'Datei nicht gefunden'])->withInput();
        }
        if (!in_array(pathinfo($file->getClientOriginalName())['extension'], array('aac', 'ac3', 'act', 'aif', 'aiff', 'mp3', 'mpa', 'wav', 'wma', 'ogg','flac', 'rm', 'mpeg'), true)) {
            return redirect()->back()->withErrors(['datei' => 'Dateityp nicht erlaubt!'])->withInput();
        }
        $name = $this->removeSymbol($request->input('name','Kein Titel'));
        $info = [ 'author' => $this->removeSymbol((string)$request->input('author','Kein Autor')),
                    'infotxt' => $this->removeSymbol((string)$request->input('infotxt','Keine Info'))];
        try {
            if(!Auth::user()->isAdmin()) {
                $song = NewSong::create([
                    'name' => $name,
                    'info' => json_encode($info, JSON_THROW_ON_ERROR),
                    'file' => "test.mp3"]);
            }else{
                $song = Song::create([
                    'name' => $name,
                    'info' => json_encode($info, JSON_THROW_ON_ERROR),
                    'file' => "test.mp3"
                ]);
            }
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'Fehler beim Speichern der Info!'])->withInput();
        }
        $fileName = $song->id . '-'. $name . '.' . pathinfo($file->getClientOriginalName())['extension'];

        if(!Auth::user()->isAdmin()) {
            Storage::disk('public')->putFileAs("song/new", $file, $fileName);
            $song->file = Storage::disk('public')->url("song/new".$fileName);
        }else{
            Storage::disk('public')->putFileAs("song", $file, $fileName);
            $song->file = Storage::disk('public')->url("song/".$fileName);
        }
        $song->update();
        if(Auth::user()->isAdmin()) {
            SongLog::create(['song_id' => $song->id, 'status_id' => 1]);
        }
        return redirect()->back()->withErrors(['success' => 'Neue Datei erfolgreich hochgeladen!']);
    }

    public static function sendAPIrequest($method, $data): array
    {
        $result = false;
        $result1 = [];

        if ($method === "get") {
            $d = "";
            if (is_array($data)) {
                for ($i = 0, $iMax = count($data); $i < $iMax; $i + 2) {
                    $d .= ($i === 0 ? "?" : "&") . $data[$i] . "=" . $data[$i + 1];
                }
            } else {
                $d = $data;
            }
            $result = file_get_contents("https://lvcharts.de/api.php" . $d);
        } else
            if ($method === "post") {
                $options = array(
                    'http' => array(
                        'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                        'method' => 'POST',
                        'content' => http_build_query($data)
                    )
                );
                $result = file_get_contents("https://lvcharts.de/api.php", false, stream_context_create($options));
            }
        if ($result !== FALSE) {
            try {
                $result1 = (array)json_decode($result, true, 512, JSON_THROW_ON_ERROR);
            } catch (\JsonException $e) {

            }
        }
        return $result1;
    }
}
