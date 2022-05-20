@extends('layouts.app')

@section('content-header')
    <h3>Song | {{\App\Http\Controllers\MainController::addSymbol($song->name)}}</h3>
    <a class="btn btn-danger" href="{{back()->getTargetUrl() === (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]" ? '/song' : back()->getTargetUrl()}}">Zurück</a>
@endsection

@section('content')
    @error('error')
        <div class="alert alert-danger">{{ $message }}</div>
    @enderror
    <div>
        <audio controls><source src="{{ $song->getURL() }}"></audio>
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
            <a class="btn {{(array_key_exists(Auth::user()->userID,$song->getLikes())) ? 'btn-success': 'btn-primary'}} " href="{{route('song.updatethums',['song'=>$song->id,'action'=>'up'])}}">Likes: {{count($song->getLikes())}}</a>

            <a class="btn {{(array_key_exists(Auth::user()->userID,$song->getDislikes())) ? 'btn-danger': 'btn-secondary'}} " href="{{route('song.updatethums',['song'=>$song->id,'action'=>'down'])}}">Dislikes: {{count($song->getDislikes())}}</a>
        @endauth
        @guest
            <button class="btn btn-secondary " disabled>Likes: {{count($song->getLikes())}}</button>

            <button class="btn btn-secondary " disabled>Dislikes: {{count($song->getDislikes())}}</button>

        @endguest
        <br>
        <hr><br><br>
        <h4>Comments:</h4>
        @auth
        <form method="post" action="{{route('song.addcommants',['song'=>$song])}}">
            @csrf
            <div class="form-group">
                <input type="text" class="form-control" name="comment" value="{{old('comment')??""}}" placeholder="Comment" required>
            </div><br>
            <input class="btn btn-success" type="submit" name="Senden">
        </form>
        <hr>
        @endauth
        @foreach($song->getComments() as $comment)
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <div class="card">
                            @if(isset($comment['user_id'])))
                                <div class="card-header">Von {{\App\Models\User::find($comment['user_id'])->username}} um {{date('H:i',strtotime($comment['time'])). " am ".date('d.m.Y',strtotime($comment['time']))}}</div>
                            @else
                                <div class="card-header">Von {{$comment['name']}} um {{date('H:i',strtotime($comment['time'])). " am ".date('d.m.Y',strtotime($comment['time']))}}</div>
                            @endif

                            <div class="card-body">
                                {{\App\Http\Controllers\MainController::addSymbol($comment['comment'])}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <br>
        @endforeach
    </div>

@endsection



