@extends('layouts.app')

@section('content-header')

    <h3>Songs <small>List of songs</small></h3>
@endsection

@section('content')
    @if(count($logs)>0)
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true"
               data-search="true">
            <thead>
            <tr>
                <th scope="col" data-sortable="true" data-field="Akte">Name</th>
                <th scope="col" data-sortable="true" data-field="name">Author</th>
                <th scope="col" data-sortable="true" data-field="date">Datum</th>
                <th scope="col" data-field="creator"></th>
            </tr>
            </thead>
            <tbody>
            @forelse($logs as $log)
                <tr>
                    <td>{!!  \App\Http\Controllers\MainController::addSymbol($log->song()->first()->name)!!}</td>
                    <td>{!! \App\Http\Controllers\MainController::addSymbol($log->song()->first()->getInfo()->author)!!}</td>
                    @if($log->status_id === 1 )
                        <td>{{$log->created_at === null ? date('d.m.Y') : date('d.m.Y',strtotime($log->created_at))}}</td>
                        <td><a class="btn btn-primary" href="{{route('song.show', $log->song_id)}}">Neu Hinzugefügt</a></td>
                    @else
                        <td>{{$log->updated_at === null ? date('d.m.Y') : date('d.m.Y',strtotime($log->updated_at))}}</td>
                        <td><button class="btn btn-danger">Wurde entfernt</button></td>
                    @endif

                </tr>
            @empty
                <tr>
                    <td colspan="6" style="text-align: center">Keine Song Logs vorhanden</td>
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
        <h1>No Song Logs found</h1>
    @endif
@endsection
