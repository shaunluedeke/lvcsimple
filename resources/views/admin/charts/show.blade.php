@extends('layouts.app')



@section('content-header')

    <h3>Charts | {{$chart->id}}</h3>
    <a class="btn btn-danger"
       href="{{back()->getTargetUrl() === (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ? '/admin/charts' : back()->getTargetUrl()}}">Zurück</a>
@endsection

@section('content')

    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')
    <div class="alert alert-success">{{ $message }}</div>@enderror


    @if(!$chart->isActive())
        <div class="alert alert-warning">
            <p>Diese Charts sind deaktiviert <a
                    href="{{route('admin.charts.id.active',['chart'=>$chart,'action'=>'activate'])}}"
                    class="btn btn-success">Aktivieren</a></p>
        </div>
    @else
        <div class="alert alert-success">
            <p>Diese Charts sind aktiviert <a
                    href="{{route('admin.charts.id.active',['chart'=>$chart,'action'=>'deactivate'])}}"
                    class="btn btn-danger">Deaktivieren</a></p>
        </div>
    @endif
    <div class="alert alert-warning">{{count($chart->getVotes())}} haben schon gevoted</div>

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
                <td>{!!  \App\Http\Controllers\MainController::addSymbol($value["song"]->name)!!}</td>
                <td>{!!\App\Http\Controllers\MainController::addSymbol($value["song"]->getInfo()->author)!!}</td>
                <td style="width: 15%"><a class="btn btn-primary"
                                          href="{{route('song.show', $key)}}">Anhören</a>
                </td>
            </tr>
        @endforeach 
        </tbody>
    </table>
@endsection
