@extends('layouts.app')

@section('content-header')

    <h3>Songs <small>List of songs</small></h3>
@endsection

@section('content')
    @if(count($songs)>0)
            <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true" data-search="true">
                <thead>
                <tr>
                    <th scope="col" data-sortable="true" data-field="Akte">Name</th>
                    <th scope="col" data-sortable="true" data-field="name">Author</th>
                    <th scope="col" data-sortable="true" data-field="date">Erstellt am</th>
                    <th scope="col" data-field="creator">Anhören</th>
                </tr>
                </thead>
                <tbody>
                @forelse($songs as $song)
                    <tr>
                        <td>{!!  \App\Http\Controllers\MainController::addSymbol((string)$song->name)!!}</td>
                        <td>{!! \App\Http\Controllers\MainController::addSymbol((string)$song->getInfo()->author)!!}</td>
                        <td>{{$song->created_at === null ? date('d.m.Y') : date('d.m.Y',strtotime($song->created_at))}}</td>
                        <td><a class="btn btn-primary" href="{{route('song.show', $song->id)}}">Öffnen</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center">Keine Songs vorhanden</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
            <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
            <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                    integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                    crossorigin="anonymous"></script>
            <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    @else
        <h1>No songs found</h1>
    @endif
@endsection
