<a href="{{ route('users.show', $user->id)}}">
  <!-- user.show is the route's name (check in routes.php), second one is url parameter(could be an array if more than one parameter) -->
  <img src="{{ $user->gravatar('140')}}" alt="{{$user->name}}" class="gravatar">
</a>
<h1>{{$user->name}}</h1>
