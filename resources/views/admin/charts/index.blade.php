@extends('layouts.app')

@section('content-header')

    <h3>Charts <small>List of all Charts</small></h3>
@endsection

@section('content')
    @if(count($active)>0 || count($inactive)>0)
        <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true"
               data-search="false">
            <thead>
            <tr>
                <th scope="col" data-sortable="true" data-field="Akte">ID</th>
                <th scope="col" data-sortable="true" data-field="name">Start Datum</th>
                <th scope="col" data-sortable="true" data-field="date">End Datum</th>
                <th scope="col" data-field="creaor"></th>
                <th scope="col" data-field="creator"></th>
            </tr>
            </thead>
            <tbody>
            <tr><td colspan="5"><a href="{{route('admin.charts.create')}}" class="btn-success btn">Neue Erstellen</a></td></tr>
            @if(count($active)>0)
                <tr>
                    <td colspan="5">Neue Charts</td>
                </tr>
                @foreach($active as $chart)
                    <tr>
                        <td>{{$chart->id}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->start_date))}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->end_date))}}</td>
                        <td><a class="btn btn-success" href="{{route('admin.charts.id',['chart'=>$chart])}}">Abstimmen verwalten</a></td>
                        <td><a class="btn btn-success" href="{{route('admin.charts.id.points',['chart'=>$chart])}}">Punkte hinzufügen</a></td>
                    </tr>
                @endforeach
            @endif
            @if(count($inactive)>0)
                <tr>
                    <td colspan="5">Alte Charts</td>
                </tr>
                @foreach($inactive as $chart)
                    <tr>
                        <td>{{$chart->id}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->start_date))}}</td>
                        <td>{{date('d.m.Y',strtotime($chart->end_date))}}</td>
                        <td><a class="btn btn-primary" href="{{route('admin.charts.id',['chart'=>$chart])}}">Abstimmung ansehen</a></td>
                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
                integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
                crossorigin="anonymous"></script>
        <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
    @else
        <h1>No Charts found! You can create a new Chart</h1>
        <a href="{{route('admin.charts.create')}}" class="btn-success btn">Neue Erstellen</a>
    @endif
@endsection
