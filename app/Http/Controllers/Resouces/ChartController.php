<?php

namespace App\Http\Controllers\Resouces;

use App\Http\Controllers\Controller;
use App\Http\Controllers\MainController;
use App\Models\Chart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChartController extends Controller
{
    public function index()
    {
        $charts = Chart::all();
        $activeCharts = [];
        $charts->where('is_active', true)->where('autoset', 1);
        $inactiveCharts = [];
        foreach ($charts as $chart) {
            if ($chart->is_active && $chart->autoset === 1 && !$chart->userhasVoted()) {
                $activeCharts[] = $chart;
            } else {
                $inactiveCharts[] = $chart;
            }
        }

        return view('charts.index', ['active' => $activeCharts, 'inactive' => $inactiveCharts]);
    }

    public function show(Chart $chart)
    {
        return view('charts.show', ['chart' => $chart, 'voted' => $chart->userhasVoted()]);
    }


    public function vote(Request $request, Chart $chart)
    {
        if (MainController::isLogin() !== true) {
            return redirect()->back()->withErrors(['error' => 'Sie müssen eingeloggt sein'])->withInput();
        }
        if ($chart->userhasVoted()) {
            return redirect()->back()->withErrors(['error' => 'Sie haben bereits für dieses Charts abgestimmt'])->withInput();
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
                        return redirect()->back()->withErrors(['error' => 'Sie können für jeden Song nur einmal abstimmen'])->withInput();
                    }
                }else if($in !== 0){
                    return redirect()->back()->withErrors(['error' => 'Sie können nur zwischen 1 und 3 abstimmen'])->withInput();
                }
            }
        }
        if (count($data) !== 3) {
            return redirect()->back()->withErrors(['error' => 'Sie müssen für alle drei Songs stimmen'])->withInput();
        }

        $votes = $chart->getVotes();
        $votes[Auth::user()->userID] = $data;

        try {
            $chart->votes = json_encode($votes, JSON_THROW_ON_ERROR);
            $chart->update();
        } catch (\JsonException $e) {
            return redirect()->back()->withErrors(['error' => 'Beim Speichern Ihrer Stimme ist ein Fehler aufgetreten'])->withInput();
        }

        return redirect()->back()->withErrors(['success' => 'Sie haben erfolgreich für dieses Charts abgestimmt']);
    }
}
