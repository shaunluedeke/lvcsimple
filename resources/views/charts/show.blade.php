@extends('layouts.app')



@section('content-header')

    <h3>Charts | {{$chart->id}}</h3>
    <a class="btn btn-danger"
       href="{{back()->getTargetUrl() === (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ? '/song' : back()->getTargetUrl()}}">Zurück</a>
@endsection

@section('content')

    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')
    <div class="alert alert-success">{{ $message }}</div>@enderror

    @if($chart->isActive())

        @if($voted)
        <div class="alert alert-success">
            <p>Du hast diesen Chart bereits abgestimmt! Hier siehst du die ergebnisse für deine Votes</p>
        </div>
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table"
               data-pagination="false" data-search="false">
            <thead>
            <tr>
                <th></th>
                <th scope="col" data-field="Akte">Platzierung</th>
                <th scope="col" data-field="Akte">Name</th>
                <th scope="col" data-field="name">Author</th>
                <th scope="col" data-field="creator">Anhören</th>
            </tr>
            </thead>
            <tbody>

            @foreach($chart->getUserVotedSong() as $key => $value)
                <tr>
                    <td style="width: 5%">@php echo(\App\Models\Song::find($key)->isNewSong() ? '<span class="badge bg-primary">Neu</span>':''); @endphp</td>
                    <td>{{$value['place']}}</td>
                    <td>{{\App\Http\Controllers\MainController::addSymbol(\App\Models\Song::find($key)->name)}}</td>
                    <td>{{\App\Http\Controllers\MainController::addSymbol(\App\Models\Song::find($key)->getInfo()->author)}}</td>
                    <td style="width: 15%"><a class="btn btn-primary"
                                              href="{{route('song.show', \App\Models\Song::find($key)->id)}}">Anhören</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @else

        @if(count($chart->getSongs())>0)
            <form method="post" action="{{route('charts.vote',['chart'=>$chart])}}">
                @csrf
                <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table"
                       data-pagination="false" data-search="false">
                    <thead>
                    <tr>
                        <th></th>
                        <th scope="col" data-field="Akte">Name</th>
                        <th scope="col" data-field="name">Author</th>
                        <th scope="col" data-field="creator">Anhören</th>
                        @auth
                            <th>Abstimmen</th>
                        @endauth
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($chart->getSongs() as $song)
                        <tr>
                            <td style="width: 5%">@php echo($song->isNewSong() ? '<span class="badge bg-primary">Neu</span>':''); @endphp</td>
                            <td>{{\App\Http\Controllers\MainController::addSymbol($song->name)}}</td>
                            <td>{{\App\Http\Controllers\MainController::addSymbol($song->getInfo()->author)}}</td>
                            <td style="width: 15%"><a class="btn btn-primary" href="{{route('song.show', $song->id)}}">Anhören</a>
                            </td>
                            @auth
                                <td><input class="form-control" name="vote/{{$song->id}}" type="number" min="0" max="3"
                                           placeholder="Platzierung von 1 bis 3" value="{{old("vote/$song->id")??""}}">
                                </td>
                            @endauth
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" style="text-align: center">Keine Songs vorhanden</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
                @auth
                    <input type="submit" class="btn btn-success" value="Abstimmen">
                @endauth
            </form>
        @else
            <h1>No songs found</h1>
        @endif

    @endif

    @else

        <div class="alert alert-warning">
            <p>Diese Charts sind deaktiviert</p>
        </div>
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table">
            <thead>
            <tr>
                <th></th>
                <th scope="col" data-field="Akte">Platzierung</th>
                <th scope="col" data-field="Akte">Name</th>
                <th scope="col" data-field="name">Author</th>
                <th scope="col" data-field="creator">Anhören</th>
            </tr>
            </thead>
            <tbody>

            @foreach($chart->getTopSongs() as $key => $value)
                <tr>
                    <td style="width: 5%">@php echo($value["song"]->isNewSong() ? '<span class="badge bg-primary">Neu</span>':''); @endphp</td>
                    <td>{{$value['place']}}</td>
                    <td>{{\App\Http\Controllers\MainController::addSymbol($value["song"]->name)}}</td>
                    <td>{{\App\Http\Controllers\MainController::addSymbol($value["song"]->getInfo()->author)}}</td>
                    <td style="width: 15%"><a class="btn btn-primary"
                                              href="{{route('song.show', $key)}}">Anhören</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>

    @endif
@endsection
