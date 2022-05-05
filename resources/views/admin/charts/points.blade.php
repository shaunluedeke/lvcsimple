@extends('layouts.app')

@section('content-header')
<h3>Punkte hinzufügen!</h3>
<a class="btn btn-danger" href="{{back()->getTargetUrl() === (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ? '/admin/charts' : back()->getTargetUrl()}}">Zurück</a>

@endsection

@section('content')
    @error('error')<div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')<div class="alert alert-success">{{ $message }}</div>@enderror

    <form method="post" action="{{route('admin.charts.id.points.execute',['chart'=>$chart])}}">
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
            <input type="submit" class="btn btn-success" value="Absenden">
    </form>
@endsection
