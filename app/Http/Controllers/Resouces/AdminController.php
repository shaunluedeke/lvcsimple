<?php

namespace App\Http\Controllers\Resouces;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Brodcastdate;
use App\Models\Chart;
use App\Models\NewSong;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');

    }

    public function index()
    {
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.index');
    }

    #region Chart
    public function charts($chart = 0)
    {
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        if ($chart === 0) {
            $charts = Chart::all();
            $activeCharts = [];
            $charts->where('is_active', true)->where('autoset', 1);
            $inactiveCharts = [];
            foreach ($charts as $chartid) {
                if ($chartid->is_active && $chartid->autoset === 1) {
                    $activeCharts[] = $chartid;
                } else {
                    $inactiveCharts[] = $chartid;
                }
            }

            return view('admin.charts.index', ['active' => $activeCharts, 'inactive' => $inactiveCharts]);
        }
        return view('admin.charts.show', ['chart' => Chart::findorfail($chart)]);
    }

    public function chartsactive(Chart $chart, string $action = "none")
    {
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        if ($action === 'activate') {
            $chart->is_active = true;
        } elseif ($action === 'deactivate') {
            $chart->is_active = false;
        } else {
            return redirect()->route('admin.charts');
        }
        $chart->update();
        return redirect()->back();
    }

    public function chartspoints(Chart $chart)
    {
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.charts.points', ['chart' => $chart]);
    }

    public function chartsvote(Request $request, Chart $chart)
    {
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->back()->withErrors(['error' => 'You must be logged in as an admin to vote'])->withInput();
        }
        $input = $request->input();
        $exist = [];
        $data = [];
        foreach ($input as $key => $value) {
            if (str_contains($key, 'vote')) {
                $in = (int)($value ?? 0);
                if (!($in < 1 || $in > 3)) {
                    if (!in_array($in, $exist, true)) {
                        $exist[] = $in;
                        $data[explode('/', $key)[1]] = ($in === 3 ? 1 : ($in === 1 ? 3 : $in));
                    } else {
                        return redirect()->back()->withErrors(['error' => 'You can only vote once for each place'])->withInput();
                    }
                }
            }
        }
        if (count($data) !== 3) {
            return redirect()->back()->withErrors(['error' => 'You must vote for all three options'])->withInput();
        }

        $votes = $chart->getVotes();

        try {
            $random = random_int(100000000000, 999999999999);
            while ($chart->hasVoted($random)) {
                $random = random_int(100000000000, 999999999999);
            }
            $votes[$random] = $data;
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while trying to vote'])->withInput();
        }
        try {
            $chart->votes = json_encode($votes, JSON_THROW_ON_ERROR);
            $chart->update();
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving your vote'])->withInput();
        }

        return redirect()->back()->withErrors(['success' => 'Your vote has been recorded']);
    }

    public function chartscreate(){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.charts.create');
    }

    public function chartsstore(Request $request){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        $request->validate([
            'start' => 'required',
            'end' => 'required',
            'song'=> 'required'
        ]);
        try {
            $start = $request->input('start', date('Y-m-dTH:i:s'));
            $end = $request->input('end', date('Y-m-dTH:i:s'));
            if(strtotime($start) > strtotime($end)){
                return redirect()->back()->withErrors(['error' => 'The start date must be before the end date']);
            }
            Chart::create([
                "song_ids" => json_encode(explode(',', $request->input('song')), JSON_THROW_ON_ERROR),
                "votes" => "[]",
                "start_date" => $start,
                "end_date" => $end
            ]);
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while creating the chart'])->withInput();
        }

        return redirect()->route('admin.charts');
    }

    #endregion

    #region Songs

    public function songs(){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        $songs = Song::all();
        return view('admin.song.index', compact('songs'));
    }

    public function songedit(Song $song){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.song.edit', compact('song'));
    }

    public function songeditsave(Request $request, Song $song){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        $song->name = $request->input('name', $song->name);
        $info = $song->getInfo();
        $info->author = $request->input('author', $info->author);
        $info->infotxt = $request->input('infotxt', $info->infotxt);
        try {
            $song->info = json_encode($info, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving the song'])->withInput();
        }
        $song->is_active = ((int)($request->input('status') ?? 0) === 1);
        $song->update();

        return redirect()->back()->withErrors(['success' => 'The song has been updated']);
    }

    public function songdelete(Song $song){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        Storage::disk('public')->delete("song/".$song->file);
        $song->delete();
        return redirect()->back()->withErrors(['success' => 'The song has been deleted']);
    }

    #endregion

    #region newsong

    public function newsong(){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.newsong.index', ['songs' => NewSong::all()]);
    }

    public function newsongaccept(NewSong $song){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        try {
            $newsong = Song::create([
                "name" => $song->name,
                "info" => json_encode($song->getInfo(), JSON_THROW_ON_ERROR),
                "is_active" => true,
                "file" => "test.mp3"
            ]);
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving the song'])->withInput();
        }
        $fileName = $newsong->id . '-'. $song->name . '.' . pathinfo($song->file)['extension'];
        $song->file = $fileName;
        $song->update();
        Storage::disk('public')->move("song/new/".$song->file,"song/". $fileName);
        Storage::disk('public')->download("song/".$song->file);
        $song->delete();
        return redirect()->back()->withErrors(['success' => 'The song has been accepted']);
    }

    public function newsongdelete(NewSong $song){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        Storage::disk('public')->delete("song/new/".$song->file);
        $song->delete();
        return redirect()->back()->withErrors(['success' => 'The song has been deleted']);
    }

    #endregion

    #region bcd

    public function bcd(){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.bcd.index', ['bcds' => Brodcastdate::all()]);
    }

    public function bcdcreate(){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        return view('admin.bcd.create');
    }

    public function bcdstore(Request $request){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
            Brodcastdate::create([
                "name" => $request->input('name'),
                "link" => $request->input('link'),
                "weekday" => $request->input('weekday'),
                "delay" => $request->input('delay'),
                "time" => $request->input('time'),
                "last_broadcast" => $request->input('last'),
                "NEXT" => false,
                "ACTIVE" => true
            ]);
        return redirect()->route('admin.bcd');
    }

    public function bcddelete(Brodcastdate $bcd){
        if (Auth::user() === null || !Auth::user()->isAdmin()) {
            return redirect()->route('home');
        }
        $bcd->delete();
        return redirect()->back()->withErrors(['success' => 'The broadcast date has been deleted']);
    }

    #endregion
}
