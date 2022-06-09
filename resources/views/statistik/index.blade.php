@extends('layouts.app')

@section('content-header')
 <h3>Statistik</h3>
@endsection

@section('content')
    <table id="Table" class="table table-striped" style="width: 100%;" data-toggle="table" data-pagination="true" data-search="true">
        <thead>
        <tr>
            <th scope="col" data-field="Akte" data-sortable="true">Name</th>
            <th scope="col" data-field="name" data-sortable="true">Author</th>
            <th scope="col" data-field="likes" data-sortable="true">Likes</th>
            <th scope="col" data-field="dislikes" data-sortable="true">Dislikes</th>
            <th scope="col" data-field="creator">Anhören</th>
        </tr>
        </thead>
        <tbody>

        @foreach(\App\Models\Song::getAllSongsSortedbyLikes() as $key => $value)
            <tr>
                <td>{!! \App\Http\Controllers\MainController::addSymbol(\App\Models\Song::find($key)->name)!!}</td>
                <td>{!! \App\Http\Controllers\MainController::addSymbol(\App\Models\Song::find($key)->getInfo()->author)!!}</td>
                <td>{{count(\App\Models\Song::find($key)->getLikes())}}</td>
                <td>{{count(\App\Models\Song::find($key)->getDislikes())}}</td>
                <td style="width: 15%"><a class="btn btn-primary" href="{{route('song.show', $key)}}">Anhören</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
    <script src="https://cdn.jsdelivr.net/npm/jquery/dist/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
            integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
            crossorigin="anonymous"></script>
    <script src="https://unpkg.com/bootstrap-table@1.18.3/dist/bootstrap-table.min.js"></script>
@endsection
