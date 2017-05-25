<!-- this is a view template for password reset email -->
<p>Click following link to reset your password</p>

<a href="{{route('password.update') . '/'. $token}}">
  {{route('password.update') . '/' . $token}}
  <!-- I thought this equals to "route('password.edit'), $token" -->
</a>
