@extends('layouts.app')
@section('title', '注册')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card">
                    <h5 class="card-header">注册</h5>
                    <div class="card-body">
                        <form method="POST" action={{route('register')}}>
                            @csrf
                            <div class="form-group row">
                                <label for="username">账号</label>
                                <input id="username" name="username" type="text"
                                       class="form-control @error('username') is-invalid @enderror"
                                       value="{{ old('username') }}"
                                       required autocomplete="name" autofocus>
                                @error('username')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label for="nickname">昵称</label>
                                <input id="nickname" name="nickname" type="text"
                                       class="form-control @error('nickname') is-invalid @enderror"
                                       value="{{ old('nickname') }}"
                                       required autocomplete="nickname" autofocus>
                                @error('nickname')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label for="password">密码</label>
                                <input id="password" name="password" type="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       value="{{old('password')}}"
                                       required autocomplete="password">
                                @error('password')
                                <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-group row">
                                <label for="password_confirm">确认密码</label>
                                <input id="password_confirm" name="password_confirmation" type="password"
                                       class="form-control  @error('password_confirm') is-invalid @enderror"
                                       required autocomplete="password">
                                @error('password_confirm')
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
    </div>
@stop
