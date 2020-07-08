@extends('layouts.app')
@section('title', '登陆')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">

                <div class="card-header">登陆</div>
                <div class="card-body">
                    <form method="POST" action="{{ route('login')}}">
                        @csrf
                        @include('shared._messages')
                        <div class="form-group row">
                            <label for="username">用户账号</label>
                            <input id="username" name="username" type="text" class="form-control"
                                   value="{{old('username')}}" required autocomplete="name" autofocus>
                            @error('username')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <div class="form-group row">
                            <label for="password">用户密码</label>
                            <input id="password" name="password" type="password" class="form-control" required
                                   autocomplete="name" autofocus>
                            @error('password')
                            <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">确定</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
