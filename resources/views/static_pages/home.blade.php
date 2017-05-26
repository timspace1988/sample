@extends('layouts.default')
@section('title', 'Home')

@section('content')
  @if(Auth::check())<!-- we do a auth check, only display status post form  for signed in user  -->
    <div class="row">
      <div class="col-md-8">
        <section class="status_form">
          @include('shared._status_form')
        </section>
        <h3>Status list</h3>
        @include('shared.feed')
      </div>
      <aside class="col-md-4">
        <section class="user_info">
          @include('shared._user_info', ['user' => Auth::user()])
        </section>
      </aside>
    </div>
  @else
    <div class="jumbotron">
      <h1>Hello Laravel</h1>
      <p class="lead">
        What you see now is a sample project of <a href="https://fsdhub.com/books/laravel-essential-training-5.1">Laravel tutorial</a>
      </p>
      <p>Everything will just start from here.</p>
      <p><a class="btn btn-lg btn-success" href="{{route('signup')}}" role="button">Sign up</a></p>
      <!-- route('signup') means go route named 'signup' -->
      <!-- codes inside{{}} will be compiled on server -->
    </div>
  @endif
@stop
