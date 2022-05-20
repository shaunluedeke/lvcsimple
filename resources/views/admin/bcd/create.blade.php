@extends('layouts.app')

@section('content-header')
    <h2 class="sectionTitle">Add Broadcast Date</h2>
@endsection

@section('content')
    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    <form method="post" action="{{route('admin.bcd.store')}}">
        @csrf
        <dl class="form-group">
            <dt><label for="name">Name</label></dt>
            <dd><input required type="text" id="name" name="name" value="{{old('name')}}" class="form-control"></dd>
        </dl>
        <dl class="form-group">
            <dt><label for="link">Link</label></dt>
            <dd><input required type="url" id="link" name="link" value="{{old('link')}}" class="form-control"></dd>
        </dl>
        <dl class="form-group">
            <dt><label for="weekday">Wochentag</label></dt>
            <dd><select id="weekday" name="weekday" class="form-control">
                    <option value="1" selected="">Mo</option>
                    <option value="2">Di</option>
                    <option value="3">Mi</option>
                    <option value="4">Do</option>
                    <option value="5">Fr</option>
                    <option value="6">Sa</option>
                    <option value="7">So</option>
                </select>
            </dd>
        </dl>
        <dl class="form-group">
            <dt><label for="delay">Woche im Monat</label></dt>
            <dd><input class="form-control" required type="number" id="delay" name="delay" min="1" max="5" step="1"
                       value="{{old('delay')??0}}"><small></small></dd>
        </dl>
        <dl class="form-group">
            <dt><label for="time">Gebe die Uhrzeit an (15:00)</label></dt>
            <dd><input class="form-control" required type="text" id="time" name="time" value="{{old('time')}}"></dd>
        </dl>
        <dl class="form-group">
            <dt><label for="time">Letzte Sendung</label></dt>
            <dd><input class="form-control" required type="number" id="time" name="last" value="{{old('last')}}"></dd>
        </dl>
        <button class="btn btn-success" accesskey="s" id="button" value="bcdadd" name="button">
            Absenden
        </button>
    </form>
@endsection
