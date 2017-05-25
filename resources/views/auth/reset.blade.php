@extends('layouts.default')
@section('title', 'Password update')

@section('content')
<div class="container-fluid">
  <div class="row">
    <div class="col-md-8 col-md-offset-2">
      <div class="panel panel-default">
        <div class="panel-heading">Password update</div>
        <div class="panel-body">
          @include('shared._errors')

          <form action="{{route('password.update')}}" method="post">
            <input type="hidden" name="_token" value="{{csrf_token()}}">
            <input type="hidden" name="token" value="{{$token}}"><!-- this hidden field will post a reset token to controller -->

            <div class="form-group">
              <label class="col-md-4 control-label">Email address: </label>
              <div class="col-md-6">
                <input type="email" class="form-control" name="email" value="{{old('email')}}">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Password: </label>
              <div class="col-md-6">
                <input type="password" name="password" class="form-control">
              </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Confirm password: </label>
              <div class="col-md-6">
                <input type="password" class="form-control" name="password_confirmation">
              </div>
            </div>

            <div class="form-group">
              <div class="col-md-6 col-md-offset-4">
                <button type="submit" class="btn btn-primary">Update</button>
              </div>
            </div>
          </form>
        </div>

      </div>
    </div>
  </div>
</div>
@stop
