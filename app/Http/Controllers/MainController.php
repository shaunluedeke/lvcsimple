<?php

namespace App\Http\Controllers;

use App\Http\Requests\FileUploadRequest;
use App\Models\NewSong;
use App\Models\Song;
use App\Models\SongLog;
use \Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;


class MainController extends Controller
{
    public static function removeSymbol($txt):string{
        return str_replace(array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'"),
            array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "","&sect;","&amp;","&apos;"), $txt);
    }

    public static function addSymbol($txt):string
    {
        return str_replace(array("&auml;", "&uuml;", "&ouml;", "&Auml;", "&Uuml;", "&Ouml;", "&szlig;", "´","&sect;","&amp;","&apos;"),
            array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "","§","&", "'"), $txt);
    }

    public static function deleteSymbol($txt):string
    {
        return str_replace(array("ä", "ü", "ö", "Ä", "Ü", "Ö", "ß", "´","§","&", "'"),
            array("", "", "", "", "", "", "", "","","",""), $txt);
    }

    public static function isLogin(){
        if(Auth::user() === null || Auth::user()->id === 0){
            return redirect()->route('login');
        }
        return true;
    }


    public function newsong(Request  $request){
        $request->validate([
            'name' => 'required:max:255',
            'author' => 'required:max:255',
            'datei'=> 'required:max:2048'
        ]);

        if($this->isLogin()!==true){return $this->isLogin();}
        $file = $request->file('datei');
        if($file === null){
            return redirect()->back()->withErrors(['error' => 'Datei nicht gefunden'])->withInput();
        }
        if (!in_array(pathinfo($file->getClientOriginalName())['extension'], array('aac', 'ac3', 'act', 'aif', 'aiff', 'mp3', 'mpa', 'wav', 'wma', 'ogg','flac', 'rm', 'mpeg'), true)) {
            return redirect()->back()->withErrors(['error' => 'Dateityp nicht erlaubt!'])->withInput();
        }
        $name = $this->removeSymbol($request->input('name','Kein Titel'));
        $info = [ 'author' => $this->removeSymbol($request->input('author','Kein Autor')),
                    'infotxt' => $this->removeSymbol($request->input('infotxt','Keine Info'))];
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
        $song->file = $fileName;
        $song->update();
        if(!Auth::user()->isAdmin()) {
            Storage::disk('public')->putFileAs("song/new", $file, $fileName);
        }else{
            Storage::disk('public')->putFileAs("song", $file, $fileName);
        }
        if(Auth::user()->isAdmin()) {
            SongLog::create(['song_id' => $song->id, 'status_id' => 1]);
        }
        return redirect()->back()->withErrors(['success' => 'Neue Datei erfolgreich hochgeladen!']);
    }
}
