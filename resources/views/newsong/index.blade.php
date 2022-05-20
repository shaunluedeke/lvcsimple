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
            <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{old('name')??""}}" placeholder="Name" required>
            @error('name')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <hr>
        <div class="form-group">
            <label>Author</label>
            <input type="text" class="form-control @error('author') is-invalid @enderror" name="author" value="{{old('author')??""}}" placeholder="Author" required>
            @error('author')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <hr>
        <div class="form-group">
            <label>Datei</label>
            <input type="file" accept="audio/mp3,audio/wav,audio/aac,audio/wma,audio/ogg"
                   class="form-control @error('datei') is-invalid @enderror" name="datei" value="{{old('datei')??""}}" placeholder="Datei" required>
            @error('datei')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <hr>
        <div class="form-group">
            <label>Info Text</label>
            <textarea class="form-control" name="infotxt" placeholder="Info Text">{{old('infotxt')??""}}</textarea>
            @error('infotxt')<div class="alert alert-danger">{{ $message }}</div>@enderror
        </div>
        <hr>
        <input type="submit" value="Absenden" class="btn btn-success">
    </form>

@endsection
