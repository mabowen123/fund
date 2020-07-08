@extends('layouts.app')
@section('title', '首页')
@guest
@section('content')
    <h1>右上角注册,登陆即可</h1>
@endsection
@else
@section('table')
    <fund-component></fund-component>
@endsection
@endguest


