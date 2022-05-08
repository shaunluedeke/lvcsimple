@extends('layouts.app')

@section('content-header')
    <h3>Settings | {{ $user->name }}</h3>
@endsection

@section('content')
    @error('error')
    <div class="alert alert-danger">{{ $message }}</div>@enderror
    @error('success')
    <div class="alert alert-success">{{ $message }}</div>@enderror
    @if($user !== null)

        <h3>Password ändern</h3>
        <div class="modal fade" id="exampleModalToggle" aria-hidden="true" aria-labelledby="exampleModalToggleLabel" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <form action="{{route('user.settings.password',compact('user'))}}" method="post">
                        @csrf @method('PUT')
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalToggleLabel">Neues Password</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label for="password">Neues Password</label>
                                <input type="password" name="password" id="password" class="form-control" placeholder="Neues Password">
                            </div>
                            <div class="form-group">
                                <label for="password">Neues Password wiederholen</label>
                                <input type="password" name="password_confirmation" id="password" class="form-control" placeholder="Neues Password wiederholen">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <input type="submit" class="btn btn-success" name="Ändern">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <a class="btn btn-warning" data-bs-toggle="modal" href="#exampleModalToggle" role="button">Ändern</a>

        <hr>
        <h3>Sprache</h3>
        <form method="post" action="{{route('user.settings.language',compact('user'))}}" id="formlang">
            @csrf @method('PUT')
            <div class="form-group">
                <select name="lang" onchange="document.getElementById('formlang').submit()" class="form-select">
                    <option value="de" @if($user->language==="de") selected @endif>Deutsch</option>
                    <option value="en" @if($user->language==="en") selected @endif>English</option>
                </select>
            </div>
        </form>
    @endif
@endsection
