<?php

namespace App\Http\Controllers\Resouces;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{

    public function index()
    {
        return view('song.index', [
            'songs' => Song::where('is_active', 1)->get(),
        ]);
    }

    public function show(Song $song)
    {
        return view('song.show', [
            'song' => $song
        ]);
    }

    public function updatethums(Song $song, string $action = "none")
    {
        if(MainController::isLogin() !== true){return MainController::isLogin();}
        if ($action !== "up" && $action !== "down") {
            return redirect()->back();
        }
        $userid = Auth::user()->userID;
        $likes = $song->getLikes();
        $dislikes = $song->getDislikes();
        try {
            if ($action === "up") {
                if(array_key_exists($userid, $dislikes)){
                    unset($dislikes[$userid]);
                }
                if (array_key_exists($userid, $likes)) {
                    unset($likes[$userid]);
                }else{
                    $likes[$userid] = 1;
                }
            }
            if($action === "down"){
                if(array_key_exists($userid, $likes)){
                    unset($likes[$userid]);
                }
                if (array_key_exists($userid, $dislikes)) {
                    unset($dislikes[$userid]);
                }else{
                    $dislikes[$userid] = 1;
                }
            }
            $song->likes = json_encode($likes, JSON_THROW_ON_ERROR);
            $song->dislikes = json_encode($dislikes, JSON_THROW_ON_ERROR);
            $song->update($likes);
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'Die Anfrage konnte nicht bearbeitet werden!'] );
        }
        return redirect()->route('song.show', $song);
    }

    public function addcommants(Request $request, Song $song){
        if(MainController::isLogin() !== true){return MainController::isLogin();}
        $comments = $song->getComments();
        try {
            $random = random_int(1, 100000000000);
            while (array_key_exists($random, $comments)) {
                $random = random_int(1, 100000000000);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Der Comment konnte nicht gespeichert werden'] )->withInput();
        }

        $comments[$random] = [
            'name' => Auth::user()->username,
            'user_id' => Auth::user()->userID,
            'comment' => MainController::removeSymbol($request->input('comment',"")),
            'time' => date('H:i d.m.Y'),
        ];
        try {
            $song->comments = json_encode($comments, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'Der Comment konnte nicht gespeichert werden'] )->withInput();
        }
        $song->update();
        return redirect()->route('song.show', $song);
    }
}
