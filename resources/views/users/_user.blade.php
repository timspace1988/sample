<li>
  <img src="{{$user->gravatar()}}" alt="{{$user->name}}" class="gravatar">
  <a href="{{route('users.show', $user->id)}}" class="username">{{$user->name}}</a>
  <!--
     following 'can' allow us to use authority policy function in blade template file, use it just like 'authorize()' in controller
     the html code inside 'can' would only be executed if current user has the delete authority
  -->
  @can('destroy', $user)
    <form action="{{route('users.destroy', $user->id)}}" method="post">
      {{csrf_field()}}
      {{method_field('DELETE')}}
      <button type="submit" class="btn btn-danger delete-btn">Delete</button>
    </form>
  @endcan
</li>
