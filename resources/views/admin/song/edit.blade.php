@extends('layouts.app')

@section('content-header')
<h3>Song | {{$song->id}}</h3>
@endsection

@section('content')
    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')
    <div class="alert alert-success">{{ $message }}</div>@enderror
    <form action="{{route('admin.songs.edit.save',compact('song'))}}" method="post">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Name</label>
            <input name="name" value="{{\App\Http\Controllers\MainController::addSymbol( old('name') ?? $song->name)}}" required class="form-control">
        </div>
        <hr>
        <div class="form-group">
            <label>Author</label>
            <input name="author" required value="{{\App\Http\Controllers\MainController::addSymbol( old('author') ?? $song->getInfo()->author)}}" class="form-control">
        </div>
        <hr>
        <div class="form-group">
            <label>Info</label>
            <textarea name="infotxt" class="form-control">{{\App\Http\Controllers\MainController::addSymbol( old('infotxt') ?? $song->getInfo()->infotxt)}}</textarea>
        </div>
        <hr>
        <div class="form-group">
            <label class="form-check-label#">Status</label>
            <input name="status" class="form-check" type="checkbox" value="1" @if($song->is_active) checked @endif>
        </div>
        <hr>
        <input type="submit" value="Speichern" class="btn btn-success">
    </form>

@endsection
