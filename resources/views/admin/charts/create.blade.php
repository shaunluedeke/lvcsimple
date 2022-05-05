@extends('layouts.app')

@section('content-header')
    <h3>Neue Charts erstellen</h3>
@endsection


@section('content')

    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>
    @enderror

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.jquery.min.js"></script>
    <link href="https://cdn.rawgit.com/harvesthq/chosen/gh-pages/chosen.min.css" rel="stylesheet"/>

    <form method="post" action="{{route('admin.charts.store')}}" style="width: 80%">
        @csrf
        <input type="hidden" name="song" id="songsave" value="">
        <div class="form-group">
            <label for="start">Startdatum</label>
            <input type="datetime-local" class="form-control" id="start" value="{{old('start')}}" name="start"
                   placeholder="Startdatum" required>
        </div>
        <hr>
        <div class="form-group">
            <label for="end">Enddatum</label>
            <input type="datetime-local" class="form-control" id="end" name="end" value="{{old('end')}}"
                   placeholder="Enddatum" required>
        </div>
        <hr>
        <div class="form-group">
            <label for="song">Song Id's</label><br>
            <select data-placeholder="Begin typing a name to filter..." id="song" multiple class="chosen-select"
                    required>
                <option value=""></option>
                @foreach(\App\Models\Song::all() as $song)
                    <option
                        value="{{$song->id}}"
                        @if(in_array($song->id, explode(',',old('song')), true)) selected @endif>{{\App\Http\Controllers\MainController::addSymbol($song->name)}}
                        | {{\App\Http\Controllers\MainController::addSymbol($song->getInfo()->author)}}</option>
                @endforeach
            </select>
        </div>
        <hr>
        <input type="submit" value="Speichern" class="btn-success btn">
    </form>
    <script>
        let songs = $(".chosen-select");
        songs.chosen({
            no_results_text: "Oops, nothing found!"
        });
        songs.chosen().change(function () {
            document.getElementById('songsave').value = songs.chosen().val();
        });
        songs.trigger({
            type: "chosen:updated"
        });
    </script>
@endsection
