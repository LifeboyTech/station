<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $app_data['name'] }} | Please Reset Your Password</title>

    <!-- Bootstrap core CSS -->
    <link href="/packages/lifeboy/station/Flat-UI-Pro-1.2.2/bootstrap/css/bootstrap.css" rel="stylesheet">
    <link href="/packages/lifeboy/station/Flat-UI-Pro-1.2.2/css/flat-ui.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="/packages/lifeboy/station/css/login.css" rel="stylesheet">
    {!! $app_data['css_override_file'] != '' ? '<link href="'.$app_data['css_override_file'].'" rel="stylesheet">' : '' !!}

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
      <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->

    <!-- script for the login / forgot form -->
    <script src="/packages/lifeboy/station/js/jquery-1.10.2.min.js"></script>
    <script src="/packages/lifeboy/station/js/login.js"></script>
    <script src="/packages/lifeboy/station/Flat-UI-Pro-1.2.2/js/flatui-checkbox.js"></script>
  </head>

  <body>

    <div class="container">

      {!! Form::open(array('url' => $app_data['root_uri_segment'].'/password/reset/'.$token, 'class' => 'form-signin reset-password')) !!}

        <h4 class="form-signin-heading">Please reset your password</h4>

        @if(Session::has('success'))
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
        @endif

        @if(Session::has('error'))
            <div class="alert alert-danger">
              {{ Session::get('error') }}
            </div>
        @endif

        <input class="form-control" type="hidden" name="token" value="{{ $token }}">
        <input class="form-control" type="text" name="email" placeholder="Email Address" required autofocus>
        <input class="form-control" type="password" name="password" placeholder="New Password" required>
        <input class="form-control" type="password" name="password_confirmation" placeholder="New Password Again" required>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Reset My Password</button>

      {!! Form::close() !!}

    </div> <!-- /container -->

    @if (isset($app_data['html_append_file']) && $app_data['html_append_file'] != '')
      @include($app_data['html_append_file'])
    @endif
    
  </body>
</html>
