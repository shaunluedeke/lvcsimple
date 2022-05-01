<?php

namespace App\Http\Controllers;

use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    public function index()
    {
        $charts = Chart::all();
        $activeCharts = []; $charts->where('is_active', true)->where('autoset',1);
        $inactiveCharts = [];
        foreach ($charts as $chart){
            if($chart->is_active && $chart->autoset === 1 && !$chart->userhasVoted()){
                $activeCharts[] = $chart;
            }else{
                $inactiveCharts[] = $chart;
            }
        }

        return view('charts.index', ['active' => $activeCharts, 'inactive' => $inactiveCharts]);
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Chart $chart)
    {
        return view('charts.show', ['chart' => $chart,'voted' => $chart->userhasVoted()]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function vote(Request $request, Chart $chart)
    {
        if($chart->userhasVoted()){
            return redirect()->back()->withErrors(['error' => 'You have already voted for this chart'])->withInput();
        }
        if(MainController::isLogin() !== true){
            return redirect()->back()->withErrors(['error' => 'You must be logged in to vote'])->withInput();
        }

        $input = $request->input();
        $exist = [];
        $data = [];
        foreach ($input as $key => $value){
            if(str_contains($key, 'vote')){
                $in = (int)($value ?? 0);
                if(!($in <1 || $in >3)){
                    if(!in_array($in, $exist, true)){
                        $exist[] = $in;
                        $data[explode('/', $key)[1]] = ($in === 3 ? 1 : ($in === 1 ? 3 : $in));
                    }else{
                        return redirect()->back()->withErrors(['error' => 'You can only vote once for each place'])->withInput();
                    }
                }
            }
        }
        if(count($data) !== 3){
            return redirect()->back()->withErrors(['error' => 'You must vote for all three options'])->withInput();
        }

        $votes = $chart->getVotes();
        $votes[Auth::user()->id] = $data;
        try {
            $chart->votes = json_encode($votes, JSON_THROW_ON_ERROR);
            $chart->update();
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while saving your vote'])->withInput();
        }

        return redirect()->back()->withErrors(['success' => 'Your vote has been recorded']);
    }
}
