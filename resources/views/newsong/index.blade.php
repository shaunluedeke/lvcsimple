@extends('layouts.app')

@section('content-header')
    <h3>Neuen Song hinzufügen</h3>
@endsection

@section('content')
    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')
    <div class="alert alert-success">{{ $message }}</div>@enderror
    <form action="{{route('newsong.index')}}" method="post" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Name</label>
            <input type="text" class="form-control" name="name" value="{{old('name')??""}}" placeholder="Name" required>
        </div>
        <hr>
        <div class="form-group">
            <label>Author</label>
            <input type="text" class="form-control" name="author" value="{{old('author')??""}}" placeholder="Author" required>
        </div>
        <hr>
        <div class="form-group">
            <label>Datei</label>
            <input type="file" accept="audio/mp3,audio/wav,audio/aac,audio/wma,audio/ogg"
                   class="form-control" name="datei" value="{{old('datei')??""}}" placeholder="Datei" required>
        </div>
        <hr>
        <div class="form-group">
            <label>Info Text</label>
            <textarea class="form-control" name="infotxt" placeholder="Info Text">{{old('infotxt')??""}}</textarea>
        </div>
        <hr>
        <input type="submit" value="Absenden" class="btn btn-success">
    </form>

@endsection
