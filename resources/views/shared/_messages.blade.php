@foreach(['danger', 'warning', 'success', 'info'] as $msg)
  @if(session()->has($msg)) ,<!-- check if the value assigned by key $msg is empty in session  -->
    <div class="flash-message">
      <p class="alert alert-{{ $msg }}">
        {{ session()->get($msg) }} <!-- return the value pointed by key $msg -->
      </p>
    </div>
  @endif
@endforeach
