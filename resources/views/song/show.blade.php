@extends('layouts.app')

@section('content-header')
    <h3>Song | {{\App\Http\Controllers\MainController::addSymbol($song->name)}}</h3>
@endsection

@section('content')
    @error('error')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div>
        <audio controls><source src="{{ $song->file }}"></audio>
        <br>
        <hr>
        <h4>Infos:</h4>
        <table class="table">
            <tr>
                <td>Name:</td>
                <td>{{\App\Http\Controllers\MainController::addSymbol($song->name)}}</td>
            </tr>
            <tr>
                <td>Artist:</td>
                <td>{{\App\Http\Controllers\MainController::addSymbol($song->getInfo()->author)}}</td>
            </tr>
            @if($song->getInfo()->infotxt !== "")
                <tr>
                    <td>Infotext:</td>
                    <td>{{\App\Http\Controllers\MainController::addSymbol($song->getInfo()->infotxt)}}</td>
                </tr>
            @endif
        </table>
        <hr><br>
        @auth
            <a class="btn {{(array_key_exists(Auth::user()->id,$song->getLikes())) ? 'btn-success': 'btn-primary'}} " href="{{route('song.updatethums',['song'=>$song->id,'action'=>'up'])}}">Likes: {{count($song->getLikes())}}</a>

            <a class="btn {{(array_key_exists(Auth::user()->id,$song->getDislikes())) ? 'btn-danger': 'btn-secondary'}} " href="{{route('song.updatethums',['song'=>$song->id,'action'=>'down'])}}">Dislikes: {{count($song->getDislikes())}}</a>
        @endauth
        @guest
            <button class="btn btn-secondary " disabled>Likes: {{count($song->getLikes())}}</button>

            <button class="btn btn-secondary " disabled>Dislikes: {{count($song->getDislikes())}}</button>

        @endguest
        <br>
        <hr><br><br>
        <h4>Comments:</h4>
        <form method="post" action="{{route('song.addcommants',['song'=>$song])}}">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="comment" value="{{old('comment')??""}}" placeholder="Comment" required>
            </div><br>
            <input class="btn btn-success" type="submit" name="Senden">
        </form>
        <hr>
        @foreach($song->getComments() as $comment)
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">Von {{\App\Models\User::find($comment['user_id'])->name}} um {{date('H:i',strtotime($comment['created_at'])). " am ".date('d.m.Y',strtotime($comment['created_at']))}}</div>

                            <div class="card-body">
                                {{\App\Http\Controllers\MainController::addSymbol($comment['text'])}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
    </div>

@endsection


