@extends('layouts.app')

@section('content-header')
    <h3>LVCharts</h3>
@endsection

@section('content')
    @guest
        <p class="alert alert-danger">
            <strong>You are not logged in!</strong>
            <a class="btn btn-danger" href="{{ route('login') }}">Login</a>
        </p>
    @endguest
    <h4>Herzlich willkommen bei den Low Vision Charts,<br>
        den Charts von Sehbehinderten für den Rest der Welt.</h4>
    <hr>
    <p>Da sich so viele Musikerinnen und Musiker beim International Low Vision Songcontest des Jugendclubs des DBSV
        beworben haben,
        dass sogar welche aussortiert werden mussten, war für uns schnell klar, da muss was Regelmäßiges her.<br>
        Damit war an einem schönen Frühlingstag die Low Vision Charts Idee geboren.<br>
        Wenn ihr euch bis hier hergefunden habt, seid ihr schon fast ein Teil dieser Idee.<br>
        Stimmt jetzt für euren Favoriten ab, macht einen oder eine Künstlerin glücklich, und last die Idee leben.</p>
    <hr>
    <br><br>
    <h4>Die letzten Charts</h4>
    <hr>
    @if(count($active)>0 || count($inactive)>0)
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true"
               data-search="false">
            <thead>
            <tr>
                <th scope="col" data-sortable="true" data-field="Akte">ID</th>
                <th scope="col" data-sortable="true" data-field="name">Start Datum</th>
                <th scope="col" data-sortable="true" data-field="date">End Datum</th>
                <th scope="col" data-field="creator"></th>
            </tr>
            </thead>
            <tbody>
            @if(count($active)>0)
                <tr>
                    <td colspan="4">Neue Charts</td>
                </tr>
                @foreach($active as $chart)
                    <tr>
                        <td>{{$chart->id}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->start_date))}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->end_date))}}</td>
                        @auth
                            <td><a class="btn btn-success" href="{{route('charts.show',['chart'=>$chart->id])}}">Abstimmen</a>
                            </td>
                        @endauth
                        @guest
                            <td><a class="btn btn-primary" href="{{route('charts.show',['chart'=>$chart->id])}}">Abstimmen
                                    ansehen</a></td>
                        @endguest
                    </tr>
                @endforeach
            @endif
            @if(count($inactive)>0)
                <tr>
                    <td colspan="4">Alte Charts</td>
                </tr>
                @foreach($inactive as $chart)
                    <tr>
                        <td>{{$chart->id}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->start_date))}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->end_date))}}</td>
                        <td><a class="btn btn-primary" href="{{route('charts.show',['chart'=>$chart->id])}}">Abstimmung
                                ansehen</a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
    @else
        <h1>No songs found</h1>
    @endif
    @if(\App\Models\Brodcastdate::getNext() !==null)
        <hr>
        <br><br>
        <h4>Nächster Sendetermin</h4>
        <hr>
        <p>Der nächste Sendetermin ist am {{\App\Models\Brodcastdate::getNext()->getDay()}}
            um {{\App\Models\Brodcastdate::getNext()->time}} Uhr auf
            <a href="{{\App\Models\Brodcastdate::getNext()->link}}" target="_blank">{{\App\Models\Brodcastdate::getNext()->name}}</a></p>
    @endif
    @if($ad !== null)
        <hr>
        <br><br>
        <h4>Werbung</h4>
        <hr>
        <h5>{{\App\Http\Controllers\AdController::getTitle($ad)}}</h5>
        <p>@php echo(\App\Http\Controllers\AdController::getString($ad,1)) @endphp</p>
    @endif
@endsection
