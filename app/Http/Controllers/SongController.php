<?php

namespace App\Http\Controllers;

use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class SongController extends Controller
{

    public function index()
    {
        return view('song.index', [
            'songs' => Song::where('is_active', 1)->get(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(Song $song)
    {
        return view('song.show', [
            'song' => $song
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }


    public function updatethums(Song $song, string $action = "none")
    {
        if ($action !== "up" && $action !== "down") {
            return redirect()->back();
        }
        $userid = Auth::user()->id;
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
        $comments = $song->getComments();
        $random= 0;
        try {
            $random = random_int(1, 100000000000);
            while (array_key_exists($random, $comments)) {
                $random = random_int(1, 100000000000);
            }
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Der Comment konnte nicht gespeichert werden'] )->withInput();
        }

        $comments[$random] = [
            'user_id' => Auth::user()->id,
            'text' => MainController::removeSymbol($request->input('comment',"")),
            'created_at' => date('Y-m-d H:i:s')
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
