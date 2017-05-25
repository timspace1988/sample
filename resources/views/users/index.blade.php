@extends('layouts.default')
@section('title', 'All users')

@section('content')
<div class="col-md-offset-2 col-md-8">
  <h1>All users</h1>
  <ul class="users">
    @foreach($users as $user)
      @include('users._user')
    @endforeach
  </ul>

  {!! $users->render() !!}
  <!--
     about codes will gives page navigation links defaultly using bootstrap style
     note: we must use this  format not the regular one
   -->
</div>
@stop
