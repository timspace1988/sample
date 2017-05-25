@foreach(['danger', 'warning', 'success', 'info', 'status'] as $msg)
  @if(session()->has($msg)) ,<!-- check if the value assigned by key $msg is empty in session  -->
    <div class="flash-message">
      <p class="alert alert-{{ $msg }} alert-dismissable">
        {{ session()->get($msg) }} <!-- return the value pointed by key $msg -->
      </p>
    </div>
  @endif
@endforeach
