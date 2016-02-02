<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>{{ $app_data['name'] }} | Please Login</title>

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

      {!! Form::open(array('url' => $app_data['root_uri_segment'].'/sessions', 'class' => 'form-signin main-login', 'autocomplete' => 'off')) !!}

        @if(Session::has('success'))
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
        @endif

        <h4 class="form-signin-heading">
          @if (Session::has('desired_uri'))
            Sign in and we'll send you along
          @else
            Please sign in
          @endif
        </h4>

        @if(Session::has('error'))
            <div class="alert alert-danger">
              {{ Session::get('error') }}
            </div>
        @endif

        <input type="text" class="form-control" placeholder="Username" name="username" autocomplete="off" required autofocus>
        <input type="password" class="form-control" placeholder="Password" name="password" required>
        <label class="checkbox" for="remember_me">
          <input type="checkbox" value="1" name="remember_me" id="remember_me" data-toggle="checkbox" checked> Remember me
          <a href="javascript:;" class="pull-right forgot-opener">Forgot My Info</a>
        </label>

        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <div class="text-center">
          <p>&nbsp;</p>
          <a href="{{ '/'.$app_data['root_uri_segment'].'/register' }}">Register a new Account</a>
        </div>
      {!! Form::close() !!}


      {!! Form::open(array('url' => $app_data['root_uri_segment'].'/forgot', 'class' => 'form-signin forgot')) !!}

        <h4 class="form-signin-heading">Forgot your info?</h4>

        <div>
          <input type="text" class="form-control" placeholder="Your email address" name="email" required>
        </div>
        <p>&nbsp;</p>
        <div>
          <button class="btn btn-lg btn-primary btn-block" type="submit"><i class="fui-mail"></i> Email me a Reset Link</button>
        </div>
        <div class="text-center">
          <p>&nbsp;</p>
          <a href="javascript:;" class="forgot-closer">Back to Login</a>
        </div>
      {!! Form::close() !!}

    </div> <!-- /container -->

    @if (isset($app_data['html_append_file']) && $app_data['html_append_file'] != '')
      @include($app_data['html_append_file'])
    @endif
    
  </body>
</html>
