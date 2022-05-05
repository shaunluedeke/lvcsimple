@extends('layouts.app')

@section('content-header')
<h3>Admin Site</h3>
@endsection

@section('content')
    <h3>Admin Site | Abstimmung</h3>
    <hr>
    <a class="btn btn-primary" href="{{route('admin.charts')}}">Abstimmung verwalten</a>
    <hr>
    <br><br>
    <h3>Admin Site | Songs</h3>
    <hr>
    <a class="btn btn-primary" href="{{route('song.create')}}">Hinzufügen</a>
    <a class="btn btn-primary" href="{{route('admin.songs')}}">Ändern</a>
    <hr> <br><br>
    <h3>Admin Site | New Songs</h3>
    <hr>
    <a class="btn btn-primary" href="{{route('admin.newsong')}}">Liste</a>
    <hr><br><br>
    <h3>Admin Site | Broadcastdates</h3>
    <hr>
    <a class="btn btn-primary" href="{{route('admin.bcd')}}">Liste</a>
    <hr>
@endsection
