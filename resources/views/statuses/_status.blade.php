<li id="status-{{$status->id}}">
  <a href="{{route('users.show', $user->id)}}">
    <img src="{{$user->gravatar()}}" alt="{{$user->name}}" class="gravatar">
  </a>
  <span class="user">
    <a href="{{route('users.show', $user->id)}}">{{$user->name}}</a>
  </span>
  <span class="timestamp">
    {{$status->created_at->diffForHumans()}}
    <!--
        diffForHumans() will return timestamp in format like "3 years ago"
        You can set it to other language in app/Providers/AppServiceProvider.php
        just modify this Carbon::setLocale('zh');
    -->
  </span>
  <span class="content">{{$status->content}}</span>
  <!-- only status's owner could see the following delete button -->
  @can('destroy', $status)
    <form action="{{ route('statuses.destroy', $status->id) }}" method="post">
      {{csrf_field()}}
      {{method_field('DELETE')}}
      <button type="submit" class="btn btn-sm btn-danger status-delete-btn">Delete</button>
    </form>
  @endcan
</li>
