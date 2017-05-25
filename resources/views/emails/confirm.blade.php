<!-- this is a view template which will be displayed in user's confirmation email -->
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Signup confirmation</title>
  </head>
  <body>
    <h1>Thank you for your signup on sample</h1>

    <p>
      Please click the following link to complete your signup
      <a href="{{route('confirm_email', $user->activation_token)}}">
        {{route('confirm_email', $user->activation_token)}}<!-- second parameter will be the parameter to be tansited to action function -->
      </a>
    </p>

    <p>
      If this is not an operation from you, please ignore this letter.
    </p>
  </body>
</html>
