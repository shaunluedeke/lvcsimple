@extends('layouts.app')

@section('content-header')
    <h3>Admin | Broadcastdates</h3>
@endsection

@section('content')
    @if(count($bcds)>0)
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true" data-search="true">
            <thead>
            <tr>
                <th scope="col" data-sortable="true" data-field="Akte">Name</th>
                <th scope="col" data-sortable="true" data-field="name">Link</th>
                <th scope="col" data-sortable="true" data-field="date">Wochentag</th>
                <th scope="col" data-sortable="true" data-field="creator">Woche im Monat</th>
                <th scope="col" data-sortable="true" data-field="creat">Uhrzeit</th>
                <th scope="col" data-field="crea">Löschen</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td colspan="7"><a href="{{route('admin.bcd.create')}}" class="btn-success btn">Neuen Hinzufügen</a></td>
            </tr>
            @forelse($bcds as $bcd)
                <tr>
                    <td>{{\App\Http\Controllers\MainController::addSymbol($bcd->name)}}</td>
                    <td>{{$bcd->link}}</td>
                    <td>{{$bcd->getDay()}}</td>
                    <td>{{$bcd->delay}}</td>
                    <td>{{$bcd->time}}</td>
                    <td>
                        <form action="{{route('admin.bcd.delete',$bcd)}}" method="post">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Löschen</button>
                        </form>
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
        <h1>No BCD found</h1>
    @endif
@endsection
