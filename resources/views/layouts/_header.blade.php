<header class="navbar navbar-fixed-top navbar-inverse">
  <div class="container">
    <div class="col-md-offset-1 col-md-10">
      <a href="{{route('home')}}" id="logo">sample</a>
      <nav>
        <ul class="nav navbar-nav navbar-right">
          <!-- Auth::check() will check if user is signed in -->
          @if(Auth::check())
            <li><a href="{{route('users.index')}}">Users list</a></li>
            <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                {{Auth::user()->name}}<b class="caret"></b>
              </a>
              <ul class="dropdown-menu">
                <li><a href="{{route('users.show', Auth::user()->id)}}">Account</a></li>
                <li><a href="{{route('users.edit', Auth::user()->id)}}">Edit profile</a></li><!-- Auth:user() will get current signed-in user  -->
                <li>
                  <a id="logout" href="#">
                    <form action="{{route('logout')}}" method="post">
                      {{csrf_field()}}
                      {{method_field('DELETE')}}
                      <!-- we click the logout button, browser will send a post request
                           However, for using RESTful, we need to send DELETE request to delete(destroy) user's resource(session)
                           But browser did not support DELETE request, so we need to use an hidden field to send simulated DELETE request
                           We use method_field('method') to create a hidden field
                           {{method_field('DELETE')}} will be transformed into html codes as:
                           <input type="hidden" name="method" value="DELETE">
                     -->
                      <button type="submit" class="btn btn-block btn-danger" name="button">Sign out</button>
                    </form>
                  </a>
                </li>
              </ul>
            </li>
          @else
            <li><a href="{{route('help')}}}}">Help</a></li>
            <li><a href="{{route('login')}}">Sign in</a></li>
          @endif
        </ul>
      </nav>
    </div>
  </div>
</header>
