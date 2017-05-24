@extends('layouts.default')
@section('title', $user->name)
@section('content')
<div class="row">
  <div class="col-md-offset-2 col-md-8">
    <div class="col-md-12">
      <div class="col-md-offset-2 col-md-8">
        <section class="user_info">
          @include('shared._user_info', ['user' => $user])
          <!-- user include to acquire external page, assign the value for variable '$user' in user_info page by 'user'=>xx -->
        </section>

      </div>
    </div>
  </div>
</div>
@stop
