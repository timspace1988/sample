@extends('layouts.default')
@section('title', 'Home')

@section('content')
  <div class="jumbotron">
    <h1>Hello Laravel</h1>
    <p class="lead">
      What you see now is a sample project of <a href="https://fsdhub.com/books/laravel-essential-training-5.1">Laravel tutorial</a>
    </p>
    <p>Everything will just start from here.</p>
    <p><a class="btn btn-lg btn-success" href="{{route('signup')}}" role="button">Sign up</a></p>
  </div>
@stop
